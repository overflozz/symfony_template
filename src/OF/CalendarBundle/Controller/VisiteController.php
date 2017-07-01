<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OF\CalendarBundle\Form\ajoutquestionnaireType;
use OF\CalendarBundle\Form\EventType;
use OF\CalendarBundle\Form\Etape1Type;
use OF\CalendarBundle\Form\Etape2Type;
use OF\CalendarBundle\Form\questionnaireType;
use OF\CalendarBundle\Entity\Event;
use OF\CalendarBundle\Entity\EventUser;
use OF\CalendarBundle\Entity\Satisfaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OF\UserBundle\Entity\User;
class VisiteController extends Controller
{
    public function viewAction($id, $etape, Request $request)
    {	
    	$em = $this->getDoctrine()->getManager();
    	$form = null;
    	$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');

		$event = $repositoryEvent->findOneBy(array('id' => $id));
		if ($event == null){
			throw new NotFoundHttpException("Visite non trouvée");
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
			if ($event->getSatisfactiongenere() == False){
	      		$satisfaction = new Satisfaction();
				$satisfaction->setVisite($event);
				$event->addenquete($satisfaction);
				$event->setSatisfactiongenere(True);

				$em->persist($satisfaction);
				$em->persist($event);
				$em->flush();
	      	}else{
	      		$satisfaction = $event->getEnquetes()[0];
	      	}
			
			$form   = $this->get('form.factory')->create(questionnaireType::class, $satisfaction);
		}


	    if($form == null){

		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event, 'Participants' => $applications, 'userParticipe' => $userParticipe, 'steptoshow' => $etape));
	    }

    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      if ($etape==4)
	      {
	      	$em->persist($satisfaction);
	      }
	      // on accepte les modif de visite que si elle est dévérouillée
	      if ($event->getVerrou() == 0){

		      $em->persist($event);
		      foreach($event->getElementsVisites() as $elementvisite)
		      {
		      	$elementvisite->setVisite($event); // car l'id de la visite associée ne se met pas à jour tout seul ( bug )
		      }

		      $em->flush();
	      }


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

				//on verrouille si l'etape est la quatrième
				if ($event->getStep() == 4){
					$event->setVerrou(1);
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



	// Pôle qualité:
	public function showQualiteVisitesAction(Request $req){
		$visitequalite = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findBy(array('respoQuali' => $this->getUser()));

		return $this->render('OFCalendarBundle:Visite:Quali/show.html.twig',array('listeVisites' => $visitequalite));
	}
	public function showQualiteVisiteAction($id, Request $req){

		$visitequalite = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findOneBy(array('id' => $id));
		if($visitequalite->getRespoQuali() != $this->getUser()){
			
			throw new NotFoundHttpException("Vous n'êtes pas reponsable qualité !");

		}

		$form   = $this->get('form.factory')->create(ajoutquestionnaireType::class, $visitequalite);
		if ($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
			$em = $this->getDoctrine()->getManager();

		foreach($visitequalite->getEnquetes() as $enquete)
	      {
	      	$enquete->setVisite($visitequalite); // car l'id de la visite associée ne se met pas à jour tout seul ( bug )

			$em->persist($enquete);
	      }
			$em->persist($visitequalite);
			$em->flush();

		}

		return $this->render('OFCalendarBundle:Visite:Quali/showVisite.html.twig',array('form' => $form->createView(), 'visite' => $visitequalite));
	}

	public function showQuestionnaireHTMLAction($id){

		$visitequalite = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findOneBy(array('id' => $id));
		$enquete = new Satisfaction();
		$form   = $this->get('form.factory')->create(questionnaireType::class, $enquete);
		return $this->render('OFCalendarBundle:Visite:Quali/Questionnaire.html.twig',array('form' => $form->createView(), 'visite' => $visitequalite));
	}

	public function showrecapHTMLAction($id){

		$visitequalite = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findOneBy(array('id' => $id));
		return $this->render('OFCalendarBundle:Visite:recap.html.twig',array('event' => $visitequalite));
	}
	// PANEL User Visite :


	public function showVisitesAction(Request $req){
		$visites = $this->getUser()->getEvents();
		$visitequalite = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findBy(array('respoQuali' => $this->getUser()));
		$visitesToDo = array();
		$visitesDone = array();

	    $userApplications =$this->getUser()->getEvents();
	    	foreach ( $userApplications as $application){
			if ($application->getstartDatetime() >= new \Datetime()){
				array_push($visitesToDo,$application);
			}else{
				array_push($visitesDone, $application);
			}
		}


		return $this->render('OFCalendarBundle:Visite:panelUser/show.html.twig',array('listeVisitesQualite' => $visitequalite, 'listeVisites' => $visites,'visitesToDo' => $visitesToDo, 'visitesDone' => $visitesDone));
	}
}
