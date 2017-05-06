<?php 
// src/OF/CalendarBundle/EventListener/CalendarEventListener.php  
	
namespace OF\CalendarBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use \Symfony\Bundle\FrameworkBundle\Routing\Router;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
	private $entityManager;
	private $router;
	protected $token;

	public function __construct(EntityManager $entityManager, Router $router, TokenStorage $token)
	{
		$this->entityManager = $entityManager;
		$this->token = $token;
		$this->router = $router;
	}
	
	public function loadEvents(CalendarEvent $calendarEvent)
	{

		$startDateA = $calendarEvent->getStartDatetime()->format('Y-m-d H:i:s');
		$endDateA = $calendarEvent->getEndDatetime()->format('Y-m-d H:i:s');

		// The original request so you can get filters from the calendar
        // Use the filter in your query for example

     	$request = $calendarEvent->getRequest();
        $filter = $request->get('filter');


		// load events using your custom logic here,
		// for instance, retrieving events from a repository

		$companyEvents = $this->entityManager->getRepository('OFCalendarBundle:Event')
			              ->createQueryBuilder('event')
			              ->where('event.startDatetime BETWEEN ?1 and ?2')
			              ->setParameter(1, $startDateA)
			              ->setParameter(2, $endDateA)
			              ->getQuery()->getResult();

	    // $companyEvents and $companyEvent in this example
	    // represent entities from your database, NOT instances of EventEntity
	    // within this bundle.
	    //
	    // Create EventEntity instances and populate it's properties with data
	    // from your own entities/database values.
	    
		foreach($companyEvents as $companyEvent) {

		    // create an event with a start/end time, or an all day event
		    if ($companyEvent->getAllDay() === false) {
		    	$eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), $companyEvent->getEndDatetime());
		    } else {
		    	$eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), null, true);
		    }

		    //optional calendar event settings
		    $eventEntity->setAllDay($companyEvent->getAllDay()); // default is false, set to true if this is an all day event
		    $eventEntity->setUrl($this->router->generate('of_calendar_view_visite', array('id'=>$companyEvent->getId()))); // url to send user to when event label is clicked
		    $eventEntity->setCssClass($companyEvent->getCssClass()); // a custom class you may want to apply to event labels
		    if ($companyEvent->getUsers()->contains($this->token->getToken()->getUser())){
		    	$eventEntity->setCssClass('applying');
		    }
		    else if (count($companyEvent->getApplications()) >= $companyEvent->getNbUserMax()){
		    	$eventEntity->setCssClass('locked');
		    }else{
		    	$eventEntity->setCssClass('open');
		    }
		    //finally, add the event to the CalendarEvent for displaying on the calendar
		    $calendarEvent->addEvent($eventEntity);
		}
	}
}