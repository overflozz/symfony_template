<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OF\CalendarBundle\Form\EventType;
use OF\CalendarBundle\Entity\Event;
use OF\CalendarBundle\Entity\EventUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OF\UserBundle\Entity\User;
class VisiteController extends Controller
{
    public function viewAction($id, $etape, Request $request)
    {	

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
		$userParticipe = $event->getUsers()->contains($this->getUser());

		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event, 'nbParticipants' => count($applications), 'userParticipe' => $userParticipe, 'steptoshow' => $etape));
    		

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
		
		return $this->render('OFCalendarBundle:Visite:showModal.html.twig', array('event' => $event, 'nbParticipants' => count($applications), 'userParticipe' => $userParticipe));
    		

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

	public function validerEtapeAction($id, Request $req){
		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
		if ($req->isXMLHttpRequest()){
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			if($event->getStep() < 0){// il y a eu une demande de validation
				$event->setStep(-($event->getStep()) + 1);

			}else{
				$event->setStep(($event->getStep()) + 1);
			}
			$em->persist($event);
			$em->flush();
			return new Response("Validé.", 200 );
		}
		return new Response("pas validé.", 200 );

	}
	public function miseenvalidationEtapeAction($id, Request $req){
		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
		if ($req->isXMLHttpRequest()){
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			if($event->getStep() > 0){// il y a eu une demande de validation
				$event->setStep(-($event->getStep()));
				$em->persist($event);
				$em->flush();
			}

			return new Response("demande effectuée.", 200 );
		}
		return new Response("demande non effectuée.", 200 );

	}
}
