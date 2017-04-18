<?php

namespace OF\Careersml\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OF\Careersml\Bundle\Entity\Candidat;
use OF\Careersml\Bundle\Form\CandidatType;
use Symfony\Component\HttpFoundation\Request;



class CandidatureController extends Controller
{
    public function indexAction()
    {
        return $this->render('OFCareersmlBundle::layout.html.twig');
    }

    public function newAction(Request $request)
    {
    	$candidature = new Candidat();
    	$form = $this -> get('form.factory')->create(CandidatType::class, $candidature);


    	//Si le formulaire a été rempli : 
    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($candidature);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Candidature en cours de traitement.');

			return $this->redirectToRoute('of_careersml_view', array('id' => $candidature->getId()));
   		}



    return $this->render('OFCareersmlBundle:Candidature:add.html.twig', array(
      'form' => $form->createView(),
    ));

    }
	
	public function viewAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository("OFCareersmlBundle:Candidat");
		$candidat = $repository -> find($id);
		
		return $this->render('OFCareersmlBundle:Candidature:view.html.twig', array( 'candidat' => $candidat ));
	}

}
