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
     * @ORM\Column(type="integer")
     */
    protected $step;
    
    /**
     * 
     
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $url;
    
    /**
     * @ORM\Column(type="string",length=512, nullable=true)
     * @var string HTML color code for the bg color of the event label.
     */
    protected $bgColor;
    
    /**
     * @ORM\Column(type="string",length=512, nullable=true)
     * @var string HTML color code for the foregorund color of the event label.
     */
    protected $fgColor;
    
    /**
     * @ORM\Column(type="string",length=512, nullable=true)
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
     */

    protected $endDatetime;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="date")
     */
    protected $startDate;


    /**
     * @ORM\Column(name="allday", type="boolean")
     */

    protected $allDay = false;

    /**
     * @ORM\Column(name="heureDebut", type="time")
     */

    protected $heureDebut;

    /**
     * @ORM\Column(name="nbUserMax", type="integer")
     */

    protected $nbUserMax;
    
    /**
     * @ORM\Column(name="heurefin", type="time")
     */

    protected $heureFin;
  /**

   * @ORM\OneToMany(targetEntity="OF\CalendarBundle\Entity\EventUser", mappedBy="event")

   */

  private $applications; // Notez le « s », une annonce est liée à plusieurs candidatures



    
    public function __construct()
    {

        $this->startDate = new \DateTime();
        $this->startDatetime = new \DateTime();
        $this->endDatetime = new \DateTime();
        $this->step = 0;

    }

    public function updateValues(){
        $this->startDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d')." ".$this->heureDebut->format('H:i:s'));
        $this->endDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d')." ".$this->heureFin->format('H:i:s'));
        $this->bgColor = '#FFFFFF';
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

    /**
     * Set heureDebut
     *
     * @param \DateTime $heureDebut
     *
     * @return Event
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return \DateTime
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set duration
     *
     * @param \Time $duration
     *
     * @return Event
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \Time
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set heureFin
     *
     * @param \DateTime $heureFin
     *
     * @return Event
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return \DateTime
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Add application
     *
     * @param \OF\CalendarBundle\Entity\EventUser $application
     *
     * @return Event
     */
    public function addApplication(\OF\CalendarBundle\Entity\EventUser $application)
    {
        $this->applications[] = $application;
        $application->setEvent($this); //On lie lutilisater à l'event

        return $this;
    }

    /**
     * Remove application
     *
     * @param \OF\CalendarBundle\Entity\EventUser $application
     */
    public function removeApplication(\OF\CalendarBundle\Entity\EventUser $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }
    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */    
    public function getUsers(){
        return $this->getApplications()->map(
            function($element){
                return $element->getUser();
            });
    }

    public function getApplication(\OF\UserBundle\Entity\User $user){
            $listeEvents = $this->getUsers(); /// on fait la liste des evnts ordonnés
            $index = $listeEvents->indexOf($user); // on regarde l'id correspondant à notre event
            return $this->getApplications()->get($index) ; // on renvoie l'application associé à cette idée.

    }

    /**
     * Set nbUserMax
     *
     * @param integer $nbUserMax
     *
     * @return Event
     */
    public function setNbUserMax($nbUserMax)
    {
        $this->nbUserMax = $nbUserMax;

        return $this;
    }
   

    /**
     * Get nbUserMax
     *
     * @return integer
     */
    public function getNbUserMax()
    {
        return $this->nbUserMax;
    }

    /**
     * Set step
     *
     * @param integer $step
     *
     * @return Event
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return integer
     */
    public function getStep()
    {
        return $this->step;
    }
}
