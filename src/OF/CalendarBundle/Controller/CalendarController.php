<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OF\CalendarBundle\Form\EventType;
use OF\CalendarBundle\Entity\Event;
use OF\CalendarBundle\Repository\EventRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class CalendarController extends Controller
{
    public function showAction(Request $request)
    {	

	    $event = new Event();
	    // on génère le form pour le placer dans le add button.

	    $em = $this->getDoctrine()->getManager();
	    $form   = $this->get('form.factory')->create(EventType::class, $event);
	    $queryBuilder = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('f');

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      
	      $event->updateValues();
	      $em->persist($event);

	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Event ajouté.');

	      return $this->redirectToRoute('of_calendar_homepage');
	    }

	    if($this->getUser() == null){
	    	$userApplications = null;
	    	$visitesToDo = null;
	    }else{
	    	$visitesToDo = array();
	    	$userApplications =$this->getUser()->getEvents();
	    	foreach ( $userApplications as $application){
			if ($application->getstartDatetime() > new \Datetime()){
				array_push($visitesToDo,$application);
			}
			}

	    }
        return $this->render('OFCalendarBundle:Calendar:show.html.twig', array('form' => $form->createView(), 'userApplications' => $userApplications, 'visitesToDo' => $visitesToDo));
    }
    
}
