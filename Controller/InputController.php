<?php

namespace Diaborg3Bundle\Controller;


use DateTime;
use Diaborg3Bundle\Data\DiaborgRepositoryInterface;
use Diaborg3Bundle\Entity\DiaborgEntry;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\EngineInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route(service="diaborg3.inputcontroller")
 */
class InputController
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;
    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var \Symfony\Component\Routing\Router
     */
    private $router;
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;
    /**
     * @var \Diaborg3Bundle\Data\DiaborgRepositoryInterface
     */
    private $repository;

    function __construct(FormFactory $formFactory, EngineInterface $templating, Router $router, DiaborgRepositoryInterface $repository)
    {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->router = $router;
        $this->repository = $repository;
    }

    public function addAction(Request $request)
    {
        $entry = new DiaborgEntry();

        $oldtimestamp = $request->get('oldtimestamp', null);
        if(null !== $oldtimestamp){
            $oldtime = new DateTime();
            $oldtime->setTimestamp($oldtimestamp);
            $entry->setTimestamp($oldtime);
        }

        $form = $this->formFactory->createBuilder('form', $entry)
            ->add('value', 'integer', array('required' => false))
            ->add('be', 'number', array('required' => false))
            ->add('insulin', 'number', array('required' => false))
            ->add('timestamp', 'datetime')
            ->add('save', 'submit'  )
            ->getForm();


        $form->handleRequest($request);

        if($form->isValid()){

            $this->repository->addEntity($entry);

            return new RedirectResponse($this->router->generate('add', array('oldtimestamp' => $entry->getTimestamp()->getTimestamp())));
        }

        $formView = $form->createView();

        return new Response($this->templating->render('Diaborg3Bundle:Form:form3.html.twig', array('form' => $formView)));
    }


} 