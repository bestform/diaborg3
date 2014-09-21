<?php

namespace Diaborg3Bundle\Controller;


use DateTime;
use Diaborg3Bundle\Data\DiaborgRepositoryInterface;
use Diaborg3Bundle\Entity\DiaborgEntry;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\EngineInterface;

class Diaborg3Controller
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;
    /**
     * @var \Symfony\Component\Routing\Router
     */
    private $router;
    /**
     * @var \Diaborg3Bundle\Data\DiaborgRepositoryInterface
     */
    private $repository;
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    function __construct(EngineInterface $templating, Router $router, DiaborgRepositoryInterface $repository, Registry $doctrine)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->repository = $repository;
    }

    public function indexAction()
    {
        return new RedirectResponse($this->router->generate('list'));
    }

    public function testAction()
    {
        $entries = $this->doctrine
            ->getRepository('Diaborg3Bundle:DiaborgEntry')
            ->findAll();

        return new Response($this->templating->render('Diaborg3Bundle:List:test.html.twig', array("entries" => $entries)));

    }

    public function listAction()
    {
        $data = $this->repository->getList();

        $entries = array();
        $dayId = 0;
        foreach($data as $entry){
            $timestamp = $entry->getTimestamp()->getTimestamp();
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($timestamp);
            $dateTime->setTime(0,0);
            $currentDay = $dateTime->getTimestamp();
            if(!isset($entries[$currentDay])){
                //init day
                $entries[$currentDay] = array();
                $entries[$currentDay]['entries'] = array();
                $entries[$currentDay]['id'] = $dayId++;
                $entries[$currentDay]['date'] = date('l, d. F', $currentDay);
                $entries[$currentDay]['grapharray'] = "{}";
            }

            $entries[$currentDay]['entries'][] = array(
                "id" => $entry->getId(),
                "timestamp" =>  $timestamp,
                "time" => date('H:i', $timestamp),
                "value" => $entry->getValue(),
                "insulin" => $entry->getInsulin(),
                "BE" => $entry->getBE(),
                "key" => $entry->getTimestamp()
            );
        }

        $entries = $this->augmentGraphData($entries);

        $entries = array_reverse($entries);

        return new Response($this->templating->render('Diaborg3Bundle:List:list.html.twig', array("entries" => $entries)));
    }


    private function augmentGraphData($entries)
    {
        $days = array_keys($entries);
        foreach($entries as $key => $dayentry){
            $daystart = $key;
            $dayend = $key + (24 * 60 * 60);
            $bzarray = array();
            $insulinarray = array();
            $bearray = array();
            $lastValueOfDayBefore = $this->getBorderValue($entries, $key, true);
            if(null !== $lastValueOfDayBefore){
                $bzarray[] = array("date"=>$lastValueOfDayBefore['timestamp'], "value"=>$lastValueOfDayBefore['value'], "daystart"=> $daystart, "dayend"=> $dayend);
            }
            foreach($dayentry['entries'] as $timeentry){
                if(!empty($timeentry['value'])){
                    $bzarray[] = array("id" => $timeentry["id"], "date"=>$timeentry['timestamp'], "value"=>$timeentry['value'], "daystart"=> $daystart, "dayend"=> $dayend, "key" => $timeentry["key"]);
                }
                if(!empty($timeentry['insulin'])){
                    $insulinarray[] = array("id" => $timeentry["id"], "date"=>$timeentry['timestamp'], "insulin" => $timeentry['insulin'], "key" => $timeentry["key"]);
                }
                if(!empty($timeentry['BE'])){
                    $bearray[] = array("id" => $timeentry["id"], "date"=>$timeentry['timestamp'], "BE" => $timeentry["BE"], "key" => $timeentry["key"]);
                }

            }
            $nextValue = $this->getBorderValue($entries, $key, false);
            if(null !== $nextValue){
                $bzarray[] = array("date"=>$nextValue['timestamp'], "value"=>$nextValue['value'], "daystart"=> $daystart, "dayend"=> $dayend);
            }
            $dayentry['bzarray'] = json_encode($bzarray);
            $dayentry['insulinarray'] = json_encode($insulinarray);
            $dayentry['bearray'] = json_encode($bearray);

            $entries[$key] = $dayentry;

        }

        return $entries;
    }


    private function getBorderValue($entries, $key, $before = true)
    {
        $entryKeys = array_keys($entries);
        $foundEntry = null;
        if(!$before){
            $entryKeys = array_reverse($entryKeys);
        }

        foreach($entryKeys as $entrykey){
            if($entrykey === $key){
                $entryWithValue = null;
                if(null === $foundEntry){
                    return null;
                }
                if(!$before){
                    $foundEntry['entries'] = array_reverse($foundEntry['entries']);
                }
                foreach($foundEntry['entries'] as $dayEntry){
                    if(!empty($dayEntry['value'])){
                        $entryWithValue = $dayEntry;
                    }
                }
                if(null !== $entryWithValue){
                    $timestamp1 = $entryWithValue['timestamp'];
                    $timestamp2 = $key;
                    if(abs($timestamp1 - $timestamp2) > 24*60*60*2){
                        $entryWithValue = null;
                    }
                }
                return $entryWithValue;
            }
            $foundEntry = $entries[$entrykey];
        }

        return null;
    }

    public function deleteAction(Request $request, $id)
    {
        $this->repository->deleteEntry($id);
        return new RedirectResponse($this->router->generate('list'));
    }
} 