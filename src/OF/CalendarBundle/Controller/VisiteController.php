<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OF\CalendarBundle\Form\EventType;
use OF\CalendarBundle\Form\Etape1Type;
use OF\CalendarBundle\Form\Etape2Type;
use OF\CalendarBundle\Form\Etape4Type;
use OF\CalendarBundle\Entity\Event;
use OF\CalendarBundle\Entity\EventUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OF\UserBundle\Entity\User;
class VisiteController extends Controller
{
    public function viewAction($id, $etape, Request $request)
    {	

    	$form = null;
    	$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');

		$event = $repositoryEvent->findOneBy(array('id' => $id));
		if ($event == null){
			throw new NotFoundHttpException("Page not found");
		}
		if(abs($event->getStep())< $etape){ // il veut voir la prochaine etape sans avoir valider les anciennes
			$etape  = $event->getStep();
		}
		$applications = $event->getApplications();

		if($this->getUser() != NULL){
			$userParticipe = $event->getUsers()->contains($this->getUser());
		}else{
			$userParticipe = false;
		}
		if($etape == 1 ){
			$form   = $this->get('form.factory')->create(Etape1Type::class, $event);
		}
		if($etape == 2 ){
			$form   = $this->get('form.factory')->create(Etape2Type::class, $event);
		}
		if($etape == 4 ){
			$form   = $this->get('form.factory')->create(Etape4Type::class, $event);
		}

	    if($form == null){

		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event, 'Participants' => $applications, 'userParticipe' => $userParticipe, 'steptoshow' => $etape));
	    }

    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      $em = $this->getDoctrine()->getManager();
	      
	      $em->persist($event);
	      foreach($event->getElementsVisites() as $elementvisite){
	      	$elementvisite->setVisite($event); // car l'id de la visite associée ne se met pas à jour tout seul ( bug )
	      }

	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Event ajouté.');

	      return $this->redirectToRoute('of_calendar_view_visite', array('id' => $id, 'etape' => $etape));
	    }


		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event, 'nbParticipants' => count($applications), 'userParticipe' => $userParticipe, 'steptoshow' => $etape, 'form' => $form->createView()));
    		

	}
    public function viewModalAction(Request $request)
    {	

    	$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
		$event = $repositoryEvent->findOneBy(array('id' => $request->get("idVisite")));
		if ($event == null){
			throw new NotFoundHttpException("Page not found");
		}

		$applications = $event->getApplications();
		$userParticipe = $event->getUsers()->contains($this->getUser());
		
		return $this->render('OFCalendarBundle:Visite:showModal.html.twig', array('event' => $event, 'Participants' => $applications, 'userParticipe' => $userParticipe));
    		

	}
	public function ajoutUserAction($id, Request $req){

		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
    	$repositoryUser = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User');
    	
		if ($req->isXMLHttpRequest()){
			$idUser = $req->get('idUser');
			$user = $repositoryUser->findOneBy(array('id' => $idUser));
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			$listeUser = $event->getUsers();
			
			
			
			if ($listeUser->contains($user) or count($listeUser) >= $event->getNbUserMax()){
				return new Response("Dejà enregistré ou trop de monde.", 200 );
			}else{
				$eventUser = new EventUser();
				$eventUser->setUser($user);
				$eventUser->setEvent($event);
				$eventUser->setRoleUser('conferencier');
				$em->persist($eventUser);
				$em->flush();
				return new Response("Enregistré.", 200 );
			}


		}else{
			return new Response("Erreur, ce n'est pas une requete Ajax.", 400 );
		}

	}
	public function supprUserAction($id, Request $req){

		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
    	$repositoryUser = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User');
    	
		if ($req->isXMLHttpRequest()){
			$idUser = $req->get('idUser');
			$user = $repositoryUser->findOneBy(array('id' => $idUser));
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			$listeUser = $event->getUsers();
			
			
			
			if ($listeUser->contains($user)){
				
				$userevent = $event->getApplication($user);
				$user->removeVisite($userevent);
				$event->removeApplication($userevent);
				$em->remove($userevent);
				$em->flush();
				
				return new Response("suppprimé.", 200 );
			}else{
				
				return new Response("pas enregistré.", 200 );
			}


		}else{
			return new Response("Erreur, ce n'est pas une requete Ajax.", 400 );
		}

	}

	public function validerEtapeAction($id, $etape, Request $req){

		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
		if ($req->isXMLHttpRequest()  ){
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			if (abs($event->getStep()) == $etape){
				if($event->getStep() < 0){// il y a eu une demande de validation
					$event->setStep(-($event->getStep()) + 1);

				}else{
					$event->setStep(($event->getStep()) + 1);
				}
				$em->persist($event);
				$em->flush();
			}


	   	return new Response("Validé.", 200 );
		}
		return new Response("pas validé.", 400 );

	}
	public function miseenvalidationEtapeAction($id, $etape, Request $req){
		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
		if ($req->isXMLHttpRequest()){
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			if (abs($event->getStep()) == $etape){
				if($event->getStep() > 0){// il y a eu une demande de validation
					$event->setStep(-($event->getStep()));
					$em->persist($event);
					$em->flush();
				}
			}

			return new Response("demande effectuée.", 200 );
		}
		return new Response("demande non effectuée.", 200 );

	}
	public function envoyerMailAction($id, $etape){

    	$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
		$event = $repositoryEvent->findOneBy(array('id' => $id));	


		if($this->getUser() != NULL){
			$userParticipe = $event->getUsers()->contains($this->getUser());
		}else{
			$userParticipe = false;
		}
		$applications = $event->getApplications();


	    $message = \Swift_Message::newInstance()
	        ->setSubject('Recapitulatif')
	        ->setFrom('overflozz@gmail.com')
	        ->setTo('overflozz@gmail.com')
	        ->setBody(
	            $this->renderView('OFCalendarBundle:Visite:Emails/recap1.html.twig',
	                array('event' => $event, 'Participants' => $applications, 'userParticipe' => $userParticipe, 'steptoshow' => $etape)
	            ),
	            'text/html'
	        )
	        /*
	         * If you also want to include a plaintext version of the message
	        ->addPart(
	            $this->renderView(
	                'Emails/registration.txt.twig',
	                array('name' => $name)
	            ),
	            'text/plain'
	        )
	        */
	    ;
	    $this->get('mailer')->send($message);

	   	return $this->render('OFCalendarBundle:Visite:Emails/recap1.html.twig',array('event' => $event, 'Participants' => $applications, 'userParticipe' => $userParticipe, 'steptoshow' => $etape));

	}
}
