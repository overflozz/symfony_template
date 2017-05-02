<?php

namespace OF\Careersml\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OF\Careersml\Bundle\Entity\Candidat;
use OF\Careersml\Bundle\Form\CandidatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
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
			

			$request->getSession()->getFlashBag()->add('notice', 'Candidature en cours de traitement.');
			

            $process = new Process('python script.py '.$candidat->getNom().' '.$candidat->getPrenom());
            while ($process->isRunning()) {
                // waiting for process to finish
            }



            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $output = $process->getOutput();

            $nouvelleview = $this->renderView('OFCareersmlBundle:Candidature:view.html.twig', array( 'candidat' => $candidat, 'responseScript' => $output ));
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
