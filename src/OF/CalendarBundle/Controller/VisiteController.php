<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OF\CalendarBundle\Form\ajoutquestionnaireType;
use OF\CalendarBundle\Form\EventType;
use OF\CalendarBundle\Form\Etape1Type;
use OF\CalendarBundle\Form\Etape2Type;
use OF\CalendarBundle\Form\validAdminType;
use OF\CalendarBundle\Form\questionnaireType;
use OF\CalendarBundle\Entity\Event;
use OF\CalendarBundle\Entity\EventUser;
use OF\CalendarBundle\Entity\Satisfaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Query\Expr\OrderBy;
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

		$applications = $event->getApplications();

		if(strtotime(date_format($event->getendDateTime(), 'Y-m-d H:i:s') )< strtotime( date('Y-m-d H:i:s'))){
			$event->setStep(5);
			$em->persist($event);
				$em->flush();
		}
		if ($event == null){
			throw new NotFoundHttpException("Visite non trouvée");
		}
		if(abs($event->getStep())< $etape){ // il veut voir la prochaine etape sans avoir valider les anciennes
			$etape  = $event->getStep();
		}

		$visitesToDo = array();
		if($this->getUser() != NULL){
			$userParticipe = $event->getUsers()->contains($this->getUser());

	    	$userApplications =$this->getUser()->getEvents();
	    	foreach ( $userApplications as $application){
				if ($application->getstartDatetime() >= new \Datetime()){
					array_push($visitesToDo,$application);
				}
			}
		}else{
			$userParticipe = false;
		}
		if($etape == 1 ){
			$form   = $this->get('form.factory')->create(Etape1Type::class, $event);
		}
		if($etape == 2 ){
			$form   = $this->get('form.factory')->create(Etape2Type::class, $event);
		}
		if($etape == -48 ){
			$form   = $this->get('form.factory')->create(validAdminType::class, $event);
		}

		if($etape == 4 ){
			if ($event->getSatisfactiongenere() == False){
	      		$satisfaction = new Satisfaction();
				//$satisfaction->setVisite($event);
				//$event->addenquete($satisfaction);
				//$event->setSatisfactiongenere(True);

				//$em->persist($satisfaction);
				$em->persist($event);
				$em->flush();
	      	}else{
	      		$satisfaction = $event->getEnquetes()[0];
	      	}
			
			$form   = $this->get('form.factory')->create(questionnaireType::class, $satisfaction);
		}


	    if($form == null){

		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event,'visitesToDo' =>$visitesToDo, 'Participants' => $applications, 'userParticipe' => $userParticipe, 'steptoshow' => $etape));
	    }

    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      if ($etape==4)
	      {
	      	$em->persist($satisfaction);
	      }
	      if($event->getStep() == -3 && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) // validation EDF requise
	      {
	      	// si le form a été envoyé c'est que l'admin a appuyé sur refuser.
	      	$event->setRefusEDF(1);
	      	$event->setStep(3); // on enlève la demande.

	      }

	      // on accepte les modif de visite que si elle est dévérouillée
	      if ($event->getVerrou() == 0){

		      $em->persist($event);
		      $halle = 0;
		      foreach($event->getElementsVisites() as $elementvisite)
		      {

		      	$elementvisite->setVisite($event); // car l'id de la visite associée ne se met pas à jour tout seul ( bug )
		      	if ($elementvisite->getTitre()=="Halle d’essais"){
		      		$halle = 1;
		      	}
		      	$em->persist($elementvisite);
		      }
		    if ($halle == 1){
	   			$event->setHalleessais(1);

		    }else{
		    	$event->setHalleessais(0);
		    }
	
			$em->persist($event);
		      $em->flush();
	      }


	      return $this->redirectToRoute('of_calendar_view_visite', array('id' => $id,'visitesToDo' =>$visitesToDo, 'etape' => abs($etape)));
	    }


		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event,'visitesToDo' =>$visitesToDo, 'nbParticipants' => count($applications), 'userParticipe' => $userParticipe, 'steptoshow' => $etape, 'form' => $form->createView()));
    		

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
			
			
			
			if ($listeUser->contains($user) || count($listeUser) >= $event->getNbUserMax()){
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

			// la visite avait été mise en demande de validation mais la halle d'essais n'est plus voulue
			if (abs($event->getStep()) == 3 && $etape == 2 && $event->getHalleessais() != 1){
				//le gars a changé et a enlevé la halle d'essais.
				// Dans ce cas on valide
				$event->setStep(4);
				$em->persist($event);
				$em->flush();
				return new Response("Validé.", 200 );

			}
			if ($event->getStep() == 2 && $etape == 2 && $event->getHalleessais() != 1){
				//le gars veut pas la halle d'essais
				$event->setStep(4);
				$em->persist($event);
				$em->flush();
				return new Response("Validé.", 200 );

			}			

			if (abs($event->getStep()) >= 3 && $etape == 2 && $event->getHalleessais() == 1){
				//le gars a changé et veut la halle d'essais.
				$event->setStep(3);
				$em->persist($event);
				$event->setRefusEDF(0);
				$em->flush();
				return new Response("Validé.", 200 );

			}



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
				return new Response("Validé.", 200 );
			}
			if ($etape == -48 && $event->getStep() < 0 && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
				// alors c'est que l'admin a reçu le mail et cherche à valider 
				$event->setStep(-($event->getStep()) + 1);
				$event->setRefusEDF(0);
				$event->setVerrou(1); // on verrouille
				$em->persist($event);
				$em->flush();
				return new Response("Validé.", 200 );
			}

			return new Response("Non Validé.", 200 );


		}



		return new Response("pas validé.", 400 );


	}
	public function miseenvalidationEtapeAction($id, $etape, Request $req){
		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
		if ($req->isXMLHttpRequest()){
			$event = $repositoryEvent->findOneBy(array('id' => $id));
			// si l'etape est la 3eme, alors tout dépend de halle d'essais
			if (abs($event->getStep()) == $etape){
				if($event->getStep() > 0){// il y a pas eu de demande de validation
					$event->setStep(-($event->getStep()));
					$em->persist($event);
					$em->flush();
					if($etape == 3){
						

							// si halle d'essais
							$this->envoyerMailAction($id); // si on est à l'étape 3, c'est que l'admin EDF doit recevoir son mail.
						
						
					}
				}
			}
	

			return new Response("demande effectuée.", 200 );
		}
		return new Response("demande non effectuée.", 200 );

	}
	public function envoyerMailAction($id){

    	$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
		$event = $repositoryEvent->findOneBy(array('id' => $id));	


	    $message = \Swift_Message::newInstance()
	        ->setSubject('Recapitulatif')
	        ->setFrom('overflozz@gmail.com')
	        ->setTo('overflozz@gmail.com')
	        ->setBody(
	            $this->renderView('OFCalendarBundle:Visite:Emails/mailAdminEDF.html.twig',
	                array('event' => $event, 'demandeur' => $this->getUser())
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

	}



	// Pôle qualité:
	public function showQualiteVisitesAction(Request $req){
		$visitequalite = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findBy(array('respoQuali' => $this->getUser()));

		return $this->render('OFCalendarBundle:Visite:Quali/show.html.twig',array('listeVisites' => $visitequalite));
	}


	public function majVisiteAction(){

    	$em = $this->getDoctrine()->getManager();
    	$events = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findAll();
    	foreach ($events as $event){
    		if(strtotime(date_format($event->getendDateTime(), 'Y-m-d H:i:s') )< strtotime( date('Y-m-d H:i:s'))){
			$event->setStep(5);
			$em->persist($event);
			$em->flush();
			}

		}
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

	public function statsAllAction(){

		
		$visites = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findBy(array('satisfactiongenere' => 1));
		
   		$MonthAgo = date('Y-m-d', strtotime('-31 days'));
   		$allVisitesmonth = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->where('event.startDate BETWEEN ?1 and CURRENT_DATE()')->setParameter(1, $MonthAgo)->orderBy('event.startDate', 'ASC')->getQuery()->getResult();

			
   		$statsVisitesMonth = array_fill(0, 32, 0);
   		$statsVisitesMonthlabels = array_fill(0, 32, "");
   		$statsMoyenneMonth = array_fill(0, 32, 0);
   		// on part du monthago et on remonte
   		$datecourante =  date('Y-m-d', strtotime('-31 days'));
   		$i = 0;
   		$nombreMemeJour = 0;
   		foreach ($allVisitesmonth  as $visite){


   			while (date_format($visite->getstartDate(), 'Y-m-d') != $datecourante){
   				if ($nombreMemeJour > 1){
   					$statsMoyenneMonth[$i]= $statsMoyenneMonth[$i]  / $nombreMemeJour;
   				}

   				$nombreMemeJour = 0;
   				$i = $i + 1;

   				$statsVisitesMonthlabels[$i] = date('d/m',strtotime($datecourante. ' +1 day'));
   				$datecourante = date('Y-m-d', strtotime($datecourante . ' +1 day'));
   				


   			}
   			$nombreMemeJour += 1;
   			$statsMoyenneMonth[$i] += $visite->getNotesMoyenne();
   			$statsVisitesMonth[$i] +=1;
   				
   		}
   		

		$stats = $this->statsSommeVisitesAction($visites);
		$visites2 = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->findOneBy(array('id' => 2));
		return $this->render('OFCalendarBundle:Admin:stats.html.twig',array('stats' => $stats,'visitesMonth'=> $statsVisitesMonth,'statsMoyenneMonth' => $statsMoyenneMonth, 'visitesMonthLabels' => $statsVisitesMonthlabels));

	}

	public function statsSommeVisitesAction($visites){
		$result = array(array(0,0,0,0), array(0,0,0,0), array(0,0,0,0), array(0,0,0,0), array(0,0,0,0), array(0,0,0,0), array(0,0,0,0));
		foreach ($visites as $visite){
			$tableauNotes = $visite->getNotes();
			// on somme le tableau result avec le tableau tableauNotes
			$i = 0;
			foreach ($tableauNotes as $notes){
				$j = 0;
				foreach ($notes as $note){

					$result[$i][$j] = $result[$i][$j] + $note;
					$j +=1;
				}
				$i += 1;
			}

		}
		return $result;


	}

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
