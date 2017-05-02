<?php

namespace OF\CalendarBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\EventRepository")
 */
class Event
{

    /**
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * 
     * @ORM\Column(type="string",length=512)
     */
    protected $title;
    
    /**
     * 
     
     * @ORM\Column(type="string",length=512)
     */
    protected $url;
    
    /**
     * @ORM\Column(type="string",length=512)
     * @var string HTML color code for the bg color of the event label.
     */
    protected $bgColor;
    
    /**
     * @ORM\Column(type="string",length=512)
     * @var string HTML color code for the foregorund color of the event label.
     */
    protected $fgColor;
    
    /**
     * @ORM\Column(type="string",length=512)
     * @var string css class for the event label
     */
    protected $cssClass;
    
    /**
     * @var \DateTime DateTime object of the event start date/time.
     * @ORM\Column(name="startdatetime", type="datetime")
     */
    protected $startDatetime;
    
    /**
     * @ORM\Column(name="enddatetime", type="datetime")
     * @var \DateTime DateTime object of the event start date/time. Résoud je sais pas quel bug
     */

    protected $endDatetime;

    /**
     * @ORM\Column(name="allday", type="boolean")
     */

    protected $allDay = false;

    /**
     * @ORM\Column(name="otherfields", type="string")
     * 
     */
    protected $otherFields = array();
    
    public function __construct($title, \DateTime $startDatetime = null, \DateTime $endDatetime = null, $allDay = false)
    {
        $this->title = $title;
        $this->startDatetime = $startDatetime;
        $this->startDate = $startDatetime;
        $this->setAllDay($allDay);
   
    }

    /**
     * Convert calendar event details to an array
     * 
     * @return array $event 
     */
    
    public function toArray()
    {
        $event = array();
        
        if ($this->id !== null) {
            $event['id'] = $this->id;
        }
        
        $event['title'] = $this->title;
        
        if ($this->startDatetime !== null){
            $event['start'] = $this->startDatetime->format("Y-m-d\TH:i:sP");
        }
        
        if ($this->url !== null) {
            $event['url'] = $this->url;
        }
        
        if ($this->bgColor !== null) {
            $event['backgroundColor'] = $this->bgColor;
            $event['borderColor'] = $this->bgColor;
        }
        
        if ($this->fgColor !== null) {
            $event['textColor'] = $this->fgColor;
        }
        
        if ($this->cssClass !== null) {
            $event['className'] = $this->cssClass;
        }

        if ($this->endDatetime !== null) {
            $event['end'] = $this->endDatetime->format("Y-m-d\TH:i:sP");
        }
        
        $event['allDay'] = $this->allDay;

        foreach ($this->otherFields as $field => $value) {
            $event[$field] = $value;
        }
        
        return $event;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setTitle($title) 
    {
        $this->title = $title;
    }
    
    public function getTitle() 
    {
        return $this->title;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setBgColor($color)
    {
        $this->bgColor = $color;
    }
    
    public function getBgColor()
    {
        return $this->bgColor;
    }
    
    public function setFgColor($color)
    {
        $this->fgColor = $color;
    }
    
    public function getFgColor()
    {
        return $this->fgColor;
    }
    
    public function setCssClass($class)
    {
        $this->cssClass = $class;
    }
    
    public function getCssClass()
    {
        return $this->cssClass;
    }
    
    public function setStartDatetime(\DateTime $start)
    {
        $this->startDatetime = $start;
    }
    
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }
    
    public function setEndDatetime(\DateTime $end)
    {
        $this->endDatetime = $end;
    }
    
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }
    
    public function setAllDay($allDay = false)
    {
        $this->allDay = (boolean) $allDay;
    }
    
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addField($name, $value)
    {
        $this->otherFields[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeField($name)
    {
        if (!array_key_exists($name, $this->otherFields)) {
            return;
        }

        unset($this->otherFields[$name]);
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set otherFields
     *
     * @param array $otherFields
     *
     * @return Event
     */
    public function setOtherFields($otherFields)
    {
        $this->otherFields = $otherFields;

        return $this;
    }

    /**
     * Get otherFields
     *
     * @return array
     */
    public function getOtherFields()
    {
        return $this->otherFields;
    }
}
