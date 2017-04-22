<?php

namespace OF\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OFUserBundle:Default:index.html.twig');
    }
}
