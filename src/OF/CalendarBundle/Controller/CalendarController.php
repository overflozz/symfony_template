<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OF\CalendarBundle\Form\EventType;
use OF\CalendarBundle\Entity\Event;
class CalendarController extends Controller
{
    public function showAction(Request $request)
    {	

	    $event = new Event();
	    // on génère le form pour le placer dans le add button.


	    $form   = $this->get('form.factory')->create(EventType::class, $event);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      $em = $this->getDoctrine()->getManager();
	      $event->updateValues();
	      $em->persist($event);

	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Event ajouté.');

	      return $this->redirectToRoute('of_calendar_homepage');
	    }

	    if($this->getUser() == null){
	    	$userApplications = null;
	    }else{
	    	$userApplications =$this->getUser()->getEvents();
	    }
        return $this->render('OFCalendarBundle:Calendar:show.html.twig', array('form' => $form->createView(), 'userApplications' => $userApplications));
    }
    
}
