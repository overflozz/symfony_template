<?php

namespace OF\Careersml\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OF\Careersml\Bundle\Entity\Candidat;
use OF\Careersml\Bundle\Form\CandidatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CandidatureController extends Controller
{
    public function indexAction()
    {
        return $this->render('OFCareersmlBundle::layout.html.twig');
    }



    public function createAction(Request $request)
    {
    	// Le formulaire menant à ceci est en AJAX

    	$candidat = new Candidat();
    	$form = $this -> get('form.factory')->create(CandidatType::class, $candidat);


    	//Si le formulaire a été rempli : 
    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($candidat);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Candidature en cours de traitement.');
			$nouvelleview = $this->renderView('OFCareersmlBundle:Candidature:view.html.twig', array( 'candidat' => $candidat ));
			$response = new Response(json_encode(array('content' => $nouvelleview)));	
			return $response;

   		}



    return $this->render('OFCareersmlBundle:Candidature:add.html.twig', array(
      'form' => $form->createView()
    ));

    }
	
	public function viewAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository("OFCareersmlBundle:Candidat");
		$candidat = $repository -> find($id);
		
		return $this->render('OFCareersmlBundle:Candidature:view.html.twig', array( 'candidat' => $candidat ));
	}

}
