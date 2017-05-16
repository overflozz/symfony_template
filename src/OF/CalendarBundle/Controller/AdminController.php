<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OF\CalendarBundle\Form\EventType;
use OF\UserBundle\Form\Type\ModifAdminType;
use OF\CalendarBundle\Entity\Event;
class AdminController extends Controller
{
    public function homeAdminAction(Request $request)
    {	
        return $this->render('OFCalendarBundle:Admin:home.html.twig');
    }
    public function usersAdminAction(Request $request)
    {	

    	$listeUsers = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->createQueryBuilder('alias')->getQuery()->getResult();

        return $this->render('OFCalendarBundle:Admin:users.html.twig', array('listeUsers' => $listeUsers));
    }
       public function supprUserAdminAction($id, Request $request)
    {	
 		$em = $this->getDoctrine()->getManager();
    	$user = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->findOneBy(array('id' => $id));
        if($user != null){
            $em->remove($user);
            $em->flush();
        }
    	$listeUsers = $this->getDoctrine()->getManager()->getRepository('OFUserBundle:User')->createQueryBuilder('alias')->getQuery()->getResult();
        return $this->render('OFCalendarBundle:Admin:users.html.twig', array('listeUsers' => $listeUsers));
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
        }




        return $this->render('OFCalendarBundle:Admin:modifUser.html.twig', array('form' => $form->createView(), 'user' => $user));
    } 



    
}
