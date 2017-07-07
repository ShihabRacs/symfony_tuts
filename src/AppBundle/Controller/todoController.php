<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class todoController extends Controller{
    /**
     * @Route("/", name="todo_list")
     */
    public function listAction(){
      $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();
         // replace this example code with whatever you need
        return $this->render('todo/index.html.twig', array(
          'todos' => $todos
        ));
    }

    /**
     * @Route("/todos/create", name="todo_create")
     */
    public function createAction(Request $request){

      $todo = new Todo;
      $form = $this->createFormBuilder($todo)->add('name',TextType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                                            ->add('Category',TextType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                                            ->add('Description',TextareaType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                                            ->add('Priority',ChoiceType::class,array('choices' => array('Low'=>'Low','Medium'=>'Medium','High'=>'High'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                                            ->add('Due_Date',DateTimeType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                                            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          die('Submitted');
        }
        return $this->render('todo/create.html.twig');
    }
    /**
     * @Route("/todos/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request){
         // replace this example code with whatever you need
        return $this->render('todo/edit.html.twig');
    }

    /**
     * @Route("/todos/details/{id}", name="todo_details")
     */
    public function detailsAction($id){
         // replace this example code with whatever you need
        return $this->render('todo/details.html.twig');
    }

}
