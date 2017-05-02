<?php

namespace OF\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalendarController extends Controller
{
    public function showAction()
    {
        return $this->render('OFCalendarBundle:Calendar:show.html.twig');
    }
}
