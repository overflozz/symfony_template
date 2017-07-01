<?php

namespace OF\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Controller\SecurityController as SecurityController;

use OF\UserBundle\Entity\User;

/**
 * Description of UserController
 *
 */
class UserController extends SecurityController {
	// Permet d'utiliser l'include du form login.content.html.twig sur toutes les pages
    public function LoginBisAction()
    {
    	//CF \vendor\friendsofsymfony\user-bundle\Controller\SecurityController pour comprendre le crsf et les différents arguments ici.
        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Security:login_content.html.twig', array(
            'last_username' => null,
            'error'         => null,
            'csrf_token'    => $csrfToken
        ));
    }
        public function LoginMenuAction()
    {
        //CF \vendor\friendsofsymfony\user-bundle\Controller\SecurityController pour comprendre le crsf et les différents arguments ici.
        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Security:login_menu.html.twig', array(
            'last_username' => null,
            'error'         => null,
            'csrf_token'    => $csrfToken
        ));
    }


}