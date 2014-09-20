<?php

namespace Diaborg3Bundle\Data;


use Diaborg3Bundle\Entity\DiaborgEntry;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Validator\Constraints\DateTime;

class DiaborgRepositoryDatabase implements DiaborgRepositoryInterface
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    private function getDoctrineReporistory()
    {
        return $this->doctrine->getRepository('Diaborg3Bundle:DiaborgEntry');
    }

    /**
     * @return EntryModelInterface[]
     */
    public function getList()
    {
        return $this->getDoctrineReporistory()->findAll();
    }

    public function getEntry($id)
    {
        return $this->getDoctrineReporistory()->findBy(array('timestamp' => $id));
    }

    public function addEntry($timestamp, $value, $insulin, $be)
    {
        $entry = new DiaborgEntry();
        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);
        $entry->setTimestamp($datetime);
        $entry->setValue($value);
        $entry->setInsulin($insulin);
        $entry->setBe($be);

        $this->addEntity($entry);
    }

    public function addEntity(DiaborgEntry $entry)
    {
        $this->doctrine->getManager()->persist($entry);
        $this->doctrine->getManager()->flush();
    }

    public function deleteEntry($id)
    {
        $entry = $this->getEntry($id);
        $this->doctrine->getManager()->remove($entry);
    }
}