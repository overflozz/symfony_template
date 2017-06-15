<?php

// src/OF/CalendarBundle/Controller/EventController.php


namespace OF\FaqBundle\Controller;

use OF\FaqBundle\Entity\Question;
use OF\FaqBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class FaqController extends Controller
{
	public function showAction(Request $request)
    {	

	    $question = new Question();
	    // on génère le form pour le placer dans le add button.


	    $form   = $this->get('form.factory')->create(QuestionType::class, $question);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      $em = $this->getDoctrine()->getManager();
	      $em->persist($question);

	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Question ajoutée.');

	      return $this->redirectToRoute('of_faq_homepage');
	    }
	    $listeQuestions = $this->getDoctrine()->getManager()->getRepository('OFFaqBundle:Question')->createQueryBuilder('alias')->getQuery()->getResult();
	    
        return $this->render('OFFaqBundle:Faq:show.html.twig', array('form' => $form->createView(), 'listeQuestions' => $listeQuestions));
    }
    public function supprFaqAction($id, Request $request)
    {	
 		$em = $this->getDoctrine()->getManager();
    	$question = $this->getDoctrine()->getManager()->getRepository('OFFaqBundle:Question')->findOneBy(array('id' => $id));
        if($question != null){
            $em->remove($question);
            $em->flush();
        }
        return $this->showAction($request);
    } 
    public function showQuestionAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$question = $this->getDoctrine()->getManager()->getRepository('OFFaqBundle:Question')->findOneBy(array('id' => $id));
    	if ($question == null){
    		 throw $this->createNotFoundException('Cette question est introuvable.');
    	}
    	//on créé un form pour enregistrer la réponse :
    	$form   =  $this->createFormBuilder($question)->add('answer', TextType::class)->add('save', SubmitType::class)->getForm();

 		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      $em = $this->getDoctrine()->getManager();
	      $em->persist($question);

	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Question ajoutée.');

	    }
        return $this->render('OFFaqBundle:Faq:showQuestion.html.twig', array('question' => $question,'form' => $form->createView()));
    }


}
