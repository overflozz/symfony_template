<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OF\CalendarBundle\Form\DocumentType;
use OF\CalendarBundle\Form\DocumentEditType;
use OF\CalendarBundle\Form\EventType;
use OF\UserBundle\Form\Type\ModifAdminType;
use OF\CalendarBundle\Entity\Event;
use OF\CalendarBundle\Entity\Client;
use OF\CalendarBundle\Entity\Document;
use OF\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class AdminController extends Controller
{
    public function homeAdminAction(Request $request)
    {	
        return $this->render('OFCalendarBundle:Admin:home.html.twig');
    }

    //ADMINISTRATION USER
    public function usersAdminAction(Request $request)
    {	
        $em = $this->getDoctrine()->getManager();
        $user = new User();

        $form   = $this->get('form.factory')->create(ModifAdminType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $user->setPassword($user->getNom().$user->getPrenom());
          $user->setUsername( strtolower(substr($user->getNom(),0, 10)).'_'.strtolower(substr($user->getPrenom(),0, 3)));
          $em->persist($user);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Le compte de '.$user->getNom().' '.$user->getPrenom().' a bien été ajouté !');
        }


    	$listeUsers = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->createQueryBuilder('alias')->getQuery()->getResult();

        return $this->render('OFCalendarBundle:Admin:users.html.twig', array('form'=> $form->createView(), 'listeUsers' => $listeUsers));
    }
       public function supprUserAdminAction($id, Request $request)
    {	
 		$em = $this->getDoctrine()->getManager();
    	$user = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->findOneBy(array('id' => $id));
        if($user != null){
            $em->remove($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le compte a été supprimé. ');
        }
    	$listeUsers = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->createQueryBuilder('alias')->getQuery()->getResult();
        return $this->usersAdminAction($request);
    } 
       public function modifUserAdminAction($id, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->findOneBy(array('id' => $id));

        $form   = $this->get('form.factory')->create(ModifAdminType::class, $user);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em = $this->getDoctrine()->getManager();
          
          $em->persist($user);
          $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le compte a bien été modifié. ');
        }

        return $this->render('OFCalendarBundle:Admin:modifUser.html.twig', array('form' => $form->createView(), 'user' => $user));
    } 


    //ADMINISTRATION CLIENT
        public function clientsAdminAction(Request $request)
    {   

        $listeClients = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Client')->createQueryBuilder('alias')->getQuery()->getResult();
        $client = new Client();
        // on génère le form pour le placer dans le add button.


        $form   = $this->createFormBuilder($client)
        ->add('entreprise', TextType::class)
        ->add('type', ChoiceType::class, array(
        'choices'  => array(
            'Entités et Directions du groupe EDF et filiales' => "Entités et Directions du groupe EDF et filiales",
            'Clients et partenaires industriels et académiques' => "Clients et partenaires industriels et académiques",
            'Etudiants de grandes écoles ou d’universités' => 'Etudiants de grandes écoles ou d’universités',
            'Actionnaires, investisseurs' => 'Actionnaires et investisseurs',
            'PME et ETI' => 'PME et ETI',
            'Institutionnels et élus ' => 'Institutionnels et élus ',
            'Grand public' => 'Grand public',
            'Public scolaire ' =>'Public scolaire'

        ))) 
         ->add('civilite', ChoiceType::class, array(
        'choices'  => array(
            'Mr.' => "Mr.",
            'Mme.' => "Mme.",
        )))         
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class)
        ->add('service', TextType::class)
        ->add('ville', TextType::class)
        ->add('pays', TextType::class)
        ->add('adresseMail', TextType::class)
        ->add('telephone', TextType::class)
        ->add('commentaire', TextType::class)
        ->add('save', SubmitType::class)
        ->getForm();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($client);

          $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Le client a été ajouté.');

          return $this->redirectToRoute('of_calendar_clientsadmin');
        }
        return $this->render('OFCalendarBundle:Admin:clients.html.twig', array('form' => $form->createView(),'listeClients' => $listeClients));
    }


       public function supprClientAdminAction($id, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $client = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Client')->findOneBy(array('id' => $id));
        if($client != null){
            $em->remove($client);
            $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Le client a été supprimé.');
        }
        $listeClients = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Client')->createQueryBuilder('alias')->getQuery()->getResult();
        return $this->clientsAdminAction($request);
    } 
       public function modifClientAdminAction($id, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $client = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Client')->findOneBy(array('id' => $id));

         $form   = $this->createFormBuilder($client)        
        ->add('entreprise', TextType::class)
        ->add('type', TextType::class)
        ->add('civilite', ChoiceType::class, array(
        'choices'  => array(
            'Mr.' => "Mr.",
            'Mme.' => "Mme.",
        )))
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class)
        ->add('service', TextType::class)
        ->add('ville', TextType::class)
        ->add('pays', TextType::class)
        ->add('adresseMail', TextType::class)
        ->add('telephone', TextType::class)
        ->add('commentaire', TextType::class)
        ->add('save', SubmitType::class)->getForm();


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em = $this->getDoctrine()->getManager();
          
          $em->persist($client);
          $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Le client a été modifié.');
        }

        return $this->render('OFCalendarBundle:Admin:modifClient.html.twig', array('form' => $form->createView(), 'client' => $client));
    } 

    // ADMINISTRATION DOCUMENTS

        public function documentsAdminAction(Request $request)
    {   

        $listeDocuments = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Document')->createQueryBuilder('alias')->getQuery()->getResult();
        $document = new Document();
        // on génère le form pour le placer dans le add button.


        $form   = $this->get('form.factory')->create(DocumentType::class, $document);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $document->upload();
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);

        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Document ajouté.');

          return $this->redirectToRoute('of_calendar_documentsadmin');
        }
        return $this->render('OFCalendarBundle:Admin:documents.html.twig', array('form' => $form->createView(),'listeDocuments' => $listeDocuments));
    }


       public function supprDocumentAdminAction($id, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $document = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Document')->findOneBy(array('id' => $id));
        if($document != null){
            $em->remove($document);
            $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Le document a été supprimé.');
        }
        $listeDocuments = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Document')->createQueryBuilder('alias')->getQuery()->getResult();
        return $this->documentsAdminAction($request);
    } 


       public function modifDocumentAdminAction($id, Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $document = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Document')->findOneBy(array('id' => $id));

        $form   = $this->get('form.factory')->create(DocumentEditType::class, $document);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $document->upload();
          $em->persist($document);
          $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Le document a été modifié.');
        }

        return $this->render('OFCalendarBundle:Admin:modifDocument.html.twig', array('form' => $form->createView(), 'document' => $document));
    } 


    
}
