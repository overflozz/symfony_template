<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use OF\CalendarBundle\Entity\Document;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OF\UserBundle\Entity\User;
class DocumentController extends Controller
{
    public function viewAction(Request $request)
    {	
    	// on récupère les documents en triant selon la catégorie.
		 $listeDocuments = $this->getDoctrine()->getManager()->getRepository('OFCalendarBundle:Document')->createQueryBuilder('alias')->orderBy('alias.category', 'ASC')->getQuery()->getResult();

        return $this->render('OFCalendarBundle:Document:show.html.twig', array('listeDocuments' => $listeDocuments));

	}

}
