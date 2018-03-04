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
use OF\CalendarBundle\Entity\Parcours;
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
			$userParticipe = ($event->getUsers()->contains($this->getUser()) || $event->getRespoQuali() == $this->getUser());

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

		if($etape == 3  ){
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
	      if ($etape==3)
	      {
	      	$em->persist($satisfaction);
	      }


    	 if($event->getStep() == 1 ){
	      	// l'envoi du formulaire à l'étape ne se fait pas sous ajax donc pas de validation automatique
	      	$event->setStep(2);
	      	$em->persist($event);
			
		    $em->flush();
	      	return $this->redirectToRoute('of_calendar_view_visite', array('id' => $id,'visitesToDo' =>$visitesToDo, 'etape' => 2));
		  
	      } 
  
		      foreach($event->getParcours() as $parcour)
		      {
		      	
		      	$parcour->setVisite($event); // car l'id de la visite associée ne se met pas à jour tout seul ( bug )
		      	$em->persist($parcour);
				foreach($parcour->getElementsVisites() as $elementvisite){
				
		      		$elementvisite->setParcours($parcour);
				}
		      	
		      	
		      }


			$event->setHalleessais(0);
			$em->persist($event);
		    $em->flush();
	      

		    if($event->getStep() == 1){

	      		return $this->redirectToRoute('of_calendar_view_visite', array('id' => $id,'visitesToDo' =>$visitesToDo, 'etape' => 2));
		    }
	      return $this->redirectToRoute('of_calendar_view_visite', array('id' => $id,'visitesToDo' =>$visitesToDo, 'etape' => abs($etape)));
	    }


		return $this->render('OFCalendarBundle:Visite:show.html.twig', array('event' => $event,'visitesToDo' =>$visitesToDo, 'nbParticipants' => count($applications), 'userParticipe' => $userParticipe, 'steptoshow' => $etape, 'form' => $form->createView()));
    		

	}
	public function deleteElementParcoursAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	
		$event = $repositoryEvent->findOneBy(array('id' => $request->get("idEvent")));
		$parcours = $event->getParcours()[$request->get("idParcours")];
		$element = $parcours->getElementsVisites()[$request->get("idElement")];
		
		if($parcours == null){
			return new Response();
		
		}
		if($element == null){
			return new Response();
		
		}

		$em->remove($element);
		$em->flush();
		return new Response();
		
	}
	public function deleteParcoursAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	
		$event = $repositoryEvent->findOneBy(array('id' => $request->get("idEvent")));
		$parcours = $event->getParcours()[$request->get("idParcours")];

		if($parcours == null){
			return new Response();
		

		}
		
		

		$em->remove($parcours);
		$em->flush();
		return new Response();
		
	}

	public function premiercontactAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
    	$form = null;
    	$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');

		$event = $repositoryEvent->findOneBy(array('id' => $id));

		$applications = $event->getApplications();

		
		if ($event == null){
			throw new NotFoundHttpException("Visite non trouvée");
		}

		$event->setPremierContact("ok");
		$em->persist($event);
		$em->flush();

		return $this->viewAction($id, 0, $request);

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
		$userParticipe = ($event->getUsers()->contains($this->getUser()) || $event->getRespoQuali() == $this->getUser());
		
		return $this->render('OFCalendarBundle:Visite:showModal.html.twig', array('event' => $event, 'Participants' => $applications, 'userParticipe' => $userParticipe));
    		

	}
	public function ajoutUserAction(Request $req){

		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
    	$repositoryUser = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User');
    	
		if ($req->isXMLHttpRequest()){
			$idUser = $req->get('idUser');
			$id = $req->get('visite');
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
	public function supprUserAction(Request $req){

		$em = $this->getDoctrine()->getManager();
		$repositoryEvent = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event');
    	$repositoryEventUser = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:EventUser');
    	$repositoryUser = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User');
    	
		if ($req->isXMLHttpRequest()){
			$idUser = $req->get('idUser');
			$id = $req->get('visite');
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
			if($event == null){

			return new Response("Event null.", 200 );
			}

			if (abs($event->getStep()) == $etape){

					$event->setStep(($event->getStep()) + 1);
				

			
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
							//$this->envoyerMailAction($id); // si on est à l'étape 3, c'est que l'admin EDF doit recevoir son mail.
						
						
					}
				}
			}
	

			return new Response("demande effectuée.", 200 );
		}
		return new Response("demande non effectuée.", 200 );

	}
	public function envoyerMailAction($id){
	

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
		$html = $this->renderView('OFCalendarBundle:Visite:recap.html.twig',array('event' => $visitequalite));
		$html2pdf = $this->get('html2pdf_factory')->create('P','A4','fr'); // Get the html2pdf instance properly configured
		$html2pdf->writeHTML($html); // Import the html to convert
		$html2pdf->Output('Facture.pdf'); // Write the generated pdf somewhere
	return new Response();


		//return $this->render('OFCalendarBundle:Visite:recap.html.twig',array('event' => $visitequalite));
	}
	// PANEL User Visite :

	public function statsAllAction(){

		
		$visites = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->getQuery()->getResult();
		$tableauMois=array(date('F', strtotime('-13 month')), date('F', strtotime('-12 month')),date('F', strtotime('-11 months')),date('F', strtotime('-10 months')),date('F', strtotime('-9 months')),date('F', strtotime('-8 months')),date('F', strtotime('-7 months')),date('F', strtotime('-6 months')),date('F', strtotime('-5 months')),date('F', strtotime('-4 months')),date('F', strtotime('-3 months')),date('F', strtotime('-2 months')),date('F', strtotime('-1 months')));


		// on part du monthago et on remonte
   		// data courante commence au premier de notre mois
		$datecourante = date('Y-m-d', strtotime('-'.date('d', strtotime('now')).' days'));
		$datecourante = date('Y-m-d', strtotime($datecourante.' + 1 days'));
   		$MonthAgo = $datecourante;


   		$allVisitesmonth = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->where('event.startDate BETWEEN ?1 and CURRENT_DATE()')->setParameter(1, $MonthAgo)->orderBy('event.startDate', 'ASC')->getQuery()->getResult();

			
   		$statsVisitesMonth = array_fill(0, 32, 0);
   		$statsVisitesMonthlabels = array_fill(0, 32, "");
   		$statsMoyenneMonth = array_fill(0, 32, 0);
   		
   		$nombreMemeJour = 0;
   		
   		foreach ($allVisitesmonth  as $visite){
   			$i= 0;

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
   		
   		//stats sur les 12 derniers mois :
   		$moisenarriere = 12;
   		$datecourante = date('Y-m-d', strtotime('-'.date('d', strtotime('now')).' days'));
		$datecourante = date('Y-m-d', strtotime($datecourante.' + 1 days'));
		
   		$dateStart = date('Y-m-d', strtotime($datecourante.'-13 months'));
		$dateEnd = date('Y-m-d', strtotime($datecourante.'-12 months'));

   		$statsnbvisites = array_fill(0, 12, 0);
   		$statsnbvisiteurs = array_fill(0, 12, 0);

   		$statsBatiment = array_fill(0, 12, 0);
		$statsCrea = array_fill(0, 12, 0);
		$statsConf = array_fill(0, 12, 0);
		$statsOpale = array_fill(0, 12, 0);
		$statsShowroom = array_fill(0, 12, 0);
		$statsLancementShowroom = array_fill(0, 12, 0);
		$statsHalle = array_fill(0, 12, 0);
		$statsRest = array_fill(0, 12, 0);


   		while($moisenarriere != 0){
			$dateStart = date('Y-m-d', strtotime($dateStart . ' +1 month'));
			$dateEnd = date('Y-m-d', strtotime($dateEnd . ' +1 month'));
			// une fois les dates de l'intervalle updateted on selectionne les visites
   			$visitesEtudiees = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->where('event.startDate BETWEEN ?1 and ?2')->setParameter(1, $dateStart)->setParameter(2, $dateEnd)->orderBy('event.startDate', 'ASC')->getQuery()->getResult();
   			foreach ($visitesEtudiees  as $visite){
   				// on peut mtn prendre les stats qu'on veut:
   				$statsnbvisites[12-$moisenarriere]+=1;
   				$statsnbvisiteurs[12-$moisenarriere]+=$visite->getNombreParticipants();
   				foreach($visite->getParcours() as $parcour){
   					foreach($parcour->getElementsVisites() as $element){
	   					if($element->getTitre() == 'Extérieurs des bâtiments' ){
	   						$statsBatiment[12-$moisenarriere]+=1;
	   					}else if($element->getTitre() == 'Espace de créativité' ){
	   						$statsCrea[12-$moisenarriere] +=1;
	   					}else if($element->getTitre() == 'Centre de Conférence' ){
	   						$statsConf[12-$moisenarriere] +=1;
	   					}else if($element->getTitre() == 'Rez-de-chaussée Opale' ){
	   						$statsOpale[12-$moisenarriere] +=1;
	   					}else if($element->getTitre() == 'Showroom' ){
	   						$statsShowroom[12-$moisenarriere] +=1;
	   					}else if($element->getTitre() == 'Lancement Showroom' ){
	   						$statsLancementShowroom[12-$moisenarriere] +=1;
	   					}else if($element->getTitre() == 'Restauration' ){
	   						$statsRest[12-$moisenarriere] +=1;
   					}
   				}
   				}
   			
   			}

   			$moisenarriere -= 1;
   		}


		$stats = $this->statsSommeVisitesAction($visites);
		return $this->render('OFCalendarBundle:Admin:stats.html.twig',array('stats' => $stats,'visitesMonth'=> $statsVisitesMonth, 'visitesMonthLabels' => $statsVisitesMonthlabels, 'statsMoyenneMonth'=>$statsMoyenneMonth, 'statsnbvisites' => $statsnbvisites, 'statsnbvisiteurs' => $statsnbvisiteurs, 'statsBatiment' => $statsBatiment, 'statsCrea'=>$statsCrea, 'statsConf' => $statsConf, 'statsOpale'=>$statsOpale, 'statsShowroom'=>$statsShowroom,'statsLancementShowroom'=>$statsLancementShowroom, 'statsRest'=>$statsRest, 'tableauMois'=>$tableauMois));

	}

	public function statsSommeVisitesAction($visites){

		//dans result autant de tableau que de questions, et dans chaque sous tableaux, autant de tableau que de réponses.
		$result = array(array(0,0,0,0,0,0,0,0), array(0,0,0,0,0,0,0, 0), array(0,0,0,0,0,0,0, 0), array(0,0,0,0,0,0,0, 0), array(0,0,0,0,0,0,0, 0), array(0,0,0,0,0,0,0, 0), array(0,0,0,0,0,0,0, 0), array(0,0,0,0,0,0,0, 0));
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
		if($this->getUser()==null){
			throw new NotFoundHttpException("Vous n'êtes pas connecté !");
		}
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

	public function mailAutoAction(Request $req){
			$dateStart = date('Y-m-d', strtotime('-0 days'));
			$dateEnd = date('Y-m-d', strtotime(' +1 days'));
			// une fois les dates de l'intervalle updateted on selectionne les visites
   			$visitesDemain = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->where('event.startDate BETWEEN ?1 and ?2')->setParameter(1, $dateStart)->setParameter(2, $dateEnd)->orderBy('event.startDate', 'ASC')->getQuery()->getResult();

   			foreach ($visitesDemain as $event){
   				$destinataires = Array();

   				foreach ($event->getApplications() as $userEvent){
   					array_push($destinataires, $userEvent->getUser()->getEmail());
   					
   				}
   				array_push($destinataires, 'overflozz@gmail.com');
   				$message = \Swift_Message::newInstance()
		        ->setSubject('Visite '.$event->getId().' : Rappel  de visite' )
		        ->setFrom('visitesaclayjcs@gmail.com')
		        ->setTo($destinataires)
		        ->setBody(
		            $this->renderView('OFCalendarBundle:Visite:Emails/mailRappelVeille.html.twig',
		                array('event' => $event)
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

   			// pour ilana 72heures avant :
   			$dateStart = date('Y-m-d', strtotime('+2 days'));
			$dateEnd = date('Y-m-d', strtotime(' +3 days'));
			// une fois les dates de l'intervalle updateted on selectionne les visites
   			$visitesDemain = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->where('event.startDate BETWEEN ?1 and ?2')->setParameter(1, $dateStart)->setParameter(2, $dateEnd)->orderBy('event.startDate', 'ASC')->getQuery()->getResult();

   			foreach ($visitesDemain as $event){
   				$destinataires = Array();

   				array_push($destinataires, 'ilana.dahan148@gmail.com');
   				array_push($destinataires, 'overflozz@gmail.com');
   				
   				$message = \Swift_Message::newInstance()
		        ->setSubject('Visite '.$event->getId().' : Rappel  de visite dans 72h.' )
		        ->setFrom('visitesaclayjcs@gmail.com')
		        ->setTo($destinataires)
		        ->setBody(
		            $this->renderView('OFCalendarBundle:Visite:Emails/mailRappel72h.html.twig',
		                array('event' => $event)
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



   			// pour ilana lorsqu'elle n'a pas rempli ses elements de visites
   			$dateStart = date('Y-m-d', strtotime('+20 days'));
			$dateEnd = date('Y-m-d', strtotime(' +21 days'));
   			$visitesNoParcours= $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Event')->createQueryBuilder('event')->where('event.startDate BETWEEN ?1 and ?2')->setParameter(1, $dateStart)->setParameter(2, $dateEnd)->orderBy('event.startDate', 'ASC')->getQuery()->getResult();

   			foreach ($visitesNoParcours as $event){
				
   				if($event->getParcours()->count()== 0){
   					$message = \Swift_Message::newInstance()
			        ->setSubject('Visite '.$event->getId().' : Parcours de visite non rempli' )
			        ->setFrom('visitesaclayjcs@gmail.com')
			        ->setTo(array('ilana.dahan148@gmail.com', 'overflozz@gmail.com'))
			        ->setBody(
			            $this->renderView('OFCalendarBundle:Visite:Emails/mailRappelParcours.html.twig',
			                array('event' => $event)
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

   			}
   			return $this->redirectToRoute('of_calendar_homepage');		    
	}
}
