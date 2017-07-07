<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

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
                                            ->add('Due_Date',DateTimeType::class,array('attr' => array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
                                            ->add('save',SubmitType::class,array('label' => 'Create todo','attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                                            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          //Getdata
            $name = $form['name']->getData();
            $category = $form['Category']->getData();
            $description = $form['Description']->getData();
            $priority = $form['Priority']->getData();
            $due_date = $form['Due_Date']->getData();

            $now = new\DateTime('now');

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();


            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('todo_list');
        }
        return $this->render('todo/create.html.twig',array(
            'form'=> $form->createView()
        ));
    }
    /**
     * @Route("/todos/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request){
        $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
        $todo = new Todo;

        $todo->setName($todos->getName());
        $todo->setCategory($todos->getCategory());
        $todo->setDescription($todos->getDescription());
        $todo->setPriority($todos->getPriority());
        $todo->setDueDate($todos->getDueDate());
        $todo->setCreateDate($todos->getCreateDate());

        $form = $this->createFormBuilder($todo)->add('name',TextType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('Category',TextType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('Description',TextareaType::class,array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('Priority',ChoiceType::class,array('choices' => array('Low'=>'Low','Medium'=>'Medium','High'=>'High'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('Due_Date',DateTimeType::class,array('attr' => array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
            ->add('save',SubmitType::class,array('label' => 'Update todo','attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Getdata
            $name = $form['name']->getData();
            $category = $form['Category']->getData();
            $description = $form['Description']->getData();
            $priority = $form['Priority']->getData();
            $due_date = $form['Due_Date']->getData();

            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $todo= $em->getRepository('AppBundle:Todo')->find($id);

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);



            $em->flush();


            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('todo_list');
        }
        return $this->render('todo/create.html.twig',array(
            'todos' => $todos,
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/todos/details/{id}", name="todo_details")
     */
    public function detailsAction($id){
        $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
        // replace this example code with whatever you need
        return $this->render('todo/details.html.twig', array(
            'todos' => $todos
        ));
    }


/**
     * @Route("/todos/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id){


    $em = $this->getDoctrine()->getManager();
    $todo= $em->getRepository('AppBundle:Todo')->find($id);
    $em->remove($todo);
    $em->flush();
        $this->addFlash(
            'notice',
            'Todo Deleted'
        );
        return $this->redirectToRoute('todo_list');

    }

}
