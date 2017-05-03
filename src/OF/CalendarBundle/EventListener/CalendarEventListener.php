<?php 
// src/OF/CalendarBundle/EventListener/CalendarEventListener.php  
	
namespace OF\CalendarBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
	private $entityManager;
	
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
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
		    $eventEntity->setBgColor('#FF0000'); //set the background color of the event's label
		    $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
		    $eventEntity->setUrl($companyEvent->getUrl()); // url to send user to when event label is clicked
		    $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

		    //finally, add the event to the CalendarEvent for displaying on the calendar
		    $calendarEvent->addEvent($eventEntity);
		}
	}
}