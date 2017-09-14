<?php

namespace OF\CalendarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $title;
 


    /**

    * @ORM\ManyToOne(targetEntity="OF\UserBundle\Entity\User", cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)

    */

    private $respoQuali;

    /* Client */

    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */

    protected $entrepriseClient;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $typeClient;
     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $nomClient;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $prenomClient;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $civiliteClient;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $serviceClient;
     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $villeClient;
     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $paysClient;

     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $adresseMailClient;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $telephoneClient;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $commentaireClient;





    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $contexte;
    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $lieurdv;
    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $lieudepart;
    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $nombredebadges;

    /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $nombreParticipants;
    /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $halleessais; // vaut 1 si la halle d'essai est dans le processus de visite.
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $langue;
    /**
     * 
     * @ORM\Column(type="string",nullable=true)
     */
    protected $descriptionVisiteurs;
        /**
     * 
     * @ORM\Column(type="string",nullable=true)
     */
    protected $infoComplementaire;
            /**
     * 
     * @ORM\Column(type="string",nullable=true)
     */
    protected $priorite;
    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $objectif;
    /**
     * @ORM\Column(name="otherinfos", type="array")
     */
    protected $otherInfos;


    /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $step;
    
    /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $verrou; // permet de savoir si on verouille ou non les etapes de procédure

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
     * @ORM\Column(name="refusEDF", type="integer")
     */

    protected $refusEDF;
        /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $commentaireEDF;


    /**
     * @ORM\Column(name="nbUserMax", type="integer")
     */

    protected $nbUserMax;
    
    /**
     * @ORM\Column(name="heurefin", type="time")
     */

    protected $heureFin;

  /**

   * @ORM\OneToMany(targetEntity="OF\CalendarBundle\Entity\Satisfaction", mappedBy="visite", cascade={"remove", "persist"})

   */

  protected $enquetes; // Notez le « s », une annonce est liée à plusieurs candidatures

  /**

   * @ORM\OneToMany(targetEntity="OF\CalendarBundle\Entity\EventUser", mappedBy="event" , cascade={"remove"})

   */

  protected $applications; // Notez le « s », une annonce est liée à plusieurs candidatures

  /**

   * @ORM\OneToMany(targetEntity="OF\CalendarBundle\Entity\Parcours", mappedBy="visite", cascade={"remove", "persist"})

   */

  protected $parcours; // Notez le « s », une annonce est liée à plusieurs candidatures

    /**
     * @ORM\Column(name="satisfactiongenere", type="boolean")
     */

    protected $satisfactiongenere;

    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $premierContact;

    
    public function __construct()
    {

        $this->startDate = new \DateTime();
        $this->startDatetime = new \DateTime();
        $this->endDatetime = new \DateTime();
        $this->step = 1;
        $this->enquetes = array();
        $this->parcours = array();
        $this->nbUserMax = 2;
        $this->satisfactiongenere = false;
        $this->verrou = 0;
        $this->refusEDF = 0;

    }

    public function updateValues(){
        
        $this->startDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d')." ".$this->heureDebut->format('H:i:s'));
        $this->endDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d')." ".$this->heureFin->format('H:i:s'));
        $this->bgColor = '#FFFFFF';
    }
    public function getNotes(){
        // Le résultat est un tableau de tableau contenant à chaque case les nombre de réponses données pour chaque question.
        $result = array();

        $somme = 0;
        $compteur = 0;
        $resultat1 = array(0,0,0,0, 0, 0,0, 0);
        $resultat2 = array(0,0,0,0, 0, 0,0, 0);
        $resultat3 = array(0,0,0,0, 0, 0,0, 0);
        $resultat4 = array(0,0,0,0, 0, 0,0, 0);
        $resultat5 = array(0,0,0,0, 0, 0,0, 0);
        $resultat6 = array(0,0,0,0, 0, 0,0, 0);
        $resultat7 = array(0,0,0,0, 0, 0,0,0);
        $resultat8 = array(0,0,0,0, 0, 0,0,0);

        foreach ($this->enquetes as $enquete) {
            $resultenquete = 1;
            $resultat1[''.$enquete->getResultat1()] = $resultat1[$enquete->getResultat1()] + 1; 
            $resultat2[''.$enquete->getResultat2()] = $resultat2[$enquete->getResultat2()] + 1; 
            $resultat3[''.$enquete->getResultat3()] = $resultat3[$enquete->getResultat3()] + 1; 
            $resultat4[''.$enquete->getResultat4()] = $resultat4[$enquete->getResultat4()] + 1; 
            $resultat5[''.$enquete->getResultat5()] = $resultat5[$enquete->getResultat5()] + 1; 
            $resultat6[''.$enquete->getResultat6()] = $resultat6[$enquete->getResultat6()] + 1; 
            $resultat7[''.$enquete->getResultat7()] = $resultat7[$enquete->getResultat7()] + 1;
            $resultat8[''.$enquete->getResultat8()] = $resultat8[$enquete->getResultat8()] + 1;
        

        }
        array_push($result, $resultat1);
        array_push($result, $resultat2);
        array_push($result, $resultat3);
        array_push($result, $resultat4);
        array_push($result, $resultat5);
        array_push($result, $resultat6);
        array_push($result, $resultat7);
        array_push($result, $resultat8);
        return $result;
    }
    function getNotesMoyenne(){
        
        $somme = 0;
        $nombreTot = 0;
        $notes = $this->getNotes();
        foreach ($notes as $result){
            $note = 0; // 0 pour insuffisant et 3 pour tb.
            foreach ($result as $nombre){
                $somme += $nombre * $note;
                $nombreTot += $nombre;
                $note +=1;
            }

        }
        if( $nombreTot != 0 ){
            return $somme / $nombreTot;
        }else{
            return 0;
        }
    
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

    /**
     * Set otherInfos
     *
     * @param array $otherInfos
     *
     * @return Event
     */
    public function setOtherInfos($otherInfos)
    {
        $this->otherInfos = $otherInfos;

        return $this;
    }

    /**
     * Get otherInfos
     *
     * @return array
     */
    public function getOtherInfos()
    {
        return $this->otherInfos;
    }

    /**
     * Set contexte
     *
     * @param string $contexte
     *
     * @return Event
     */
    public function setcontexte($contexte)
    {
        $this->contexte = $contexte;

        return $this;
    }

    /**
     * Get contexte
     *
     * @return string
     */
    public function getcontexte()
    {
        return $this->contexte;
    }

    /**
     * Set nombreParticipants
     *
     * @param integer $nombreParticipants
     *
     * @return Event
     */
    public function setNombreParticipants($nombreParticipants)
    {
        $this->nombreParticipants = $nombreParticipants;

        return $this;
    }

    /**
     * Get nombreParticipants
     *
     * @return integer
     */
    public function getNombreParticipants()
    {
        return $this->nombreParticipants;
    }

    /**
     * Set langue
     *
     * @param string $langue
     *
     * @return Event
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return string
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set objectif
     *
     * @param string $objectif
     *
     * @return Event
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectifè
     *
     * @return string
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set typeClient
     *
     * @param string $typeClient
     *
     * @return Event
     */
    public function setTypeclient($typeClient)
    {
        $this->typeClient = $typeClient;

        return $this;
    }

    /**
     * Get typeClient
     *
     * @return string
     */
    public function getTypeclient()
    {
        return $this->typeClient;
    }

    


    /**
     * Set respoQuali
     *
     * @param \OF\UserBundle\Entity\User $respoQuali
     *
     * @return Event
     */
    public function setRespoQuali(\OF\UserBundle\Entity\User $respoQuali)
    {
        $this->respoQuali = $respoQuali;

        return $this;
    }

    /**
     * Get respoQuali
     *
     * @return \OF\UserBundle\Entity\User
     */
    public function getRespoQuali()
    {
        return $this->respoQuali;
    }

    /**
     * Add enquete
     *
     * @param \OF\CalendarBundle\Entity\Satisfaction $enquete
     *
     * @return Event
     */
    public function addEnquete(\OF\CalendarBundle\Entity\Satisfaction $enquete)
    {
        $this->enquetes[] = $enquete;

        return $this;
    }

    /**
     * Remove enquete
     *
     * @param \OF\CalendarBundle\Entity\Satisfaction $enquete
     */
    public function removeEnquete(\OF\CalendarBundle\Entity\Satisfaction $enquete)
    {
        $this->enquetes->removeElement($enquete);
    }

    /**
     * Get enquetes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnquetes()
    {
        return $this->enquetes;
    }

    /**
     * Set satisfactiongenere
     *
     * @param boolean $satisfactiongenere
     *
     * @return Event
     */
    public function setSatisfactiongenere($satisfactiongenere)
    {
        $this->satisfactiongenere = $satisfactiongenere;

        return $this;
    }

    /**
     * Get satisfactiongenere
     *
     * @return boolean
     */
    public function getSatisfactiongenere()
    {
        return $this->satisfactiongenere;
    }


    /**
     * Set verrou
     *
     * @param integer $verrou
     *
     * @return Event
     */
    public function setVerrou($verrou)
    {
        $this->verrou = $verrou;

        return $this;
    }

    /**
     * Get verrou
     *
     * @return integer
     */
    public function getVerrou()
    {
        return $this->verrou;
    }

    /**
     * Set refusEDF
     *
     * @param integer $refusEDF
     *
     * @return Event
     */
    public function setRefusEDF($refusEDF)
    {
        $this->refusEDF = $refusEDF;

        return $this;
    }

    /**
     * Get refusEDF
     *
     * @return integer
     */
    public function getRefusEDF()
    {
        return $this->refusEDF;
    }

    /**
     * Set commentaireEDF
     *
     * @param string $commentaireEDF
     *
     * @return Event
     */
    public function setCommentaireEDF($commentaireEDF)
    {
        $this->commentaireEDF = $commentaireEDF;

        return $this;
    }

    /**
     * Get commentaireEDF
     *
     * @return string
     */
    public function getCommentaireEDF()
    {
        return $this->commentaireEDF;
    }

    /**
     * Set descriptionVisiteurs
     *
     * @param string $descriptionVisiteurs
     *
     * @return Event
     */
    public function setDescriptionVisiteurs($descriptionVisiteurs)
    {
        $this->descriptionVisiteurs = $descriptionVisiteurs;

        return $this;
    }

    /**
     * Get descriptionVisiteurs
     *
     * @return string
     */
    public function getDescriptionVisiteurs()
    {
        return $this->descriptionVisiteurs;
    }

    /**
     * Set infoComplémentaire
     *
     * @param string $infoComplémentaire
     *
     * @return Event
     */
    public function setInfoComplémentaire($infoComplémentaire)
    {
        $this->infoComplémentaire = $infoComplémentaire;

        return $this;
    }

    /**
     * Get infoComplémentaire
     *
     * @return string
     */
    public function getInfoComplémentaire()
    {
        return $this->infoComplémentaire;
    }

    /**
     * Set infoComplementaire
     *
     * @param string $infoComplementaire
     *
     * @return Event
     */
    public function setInfoComplementaire($infoComplementaire)
    {
        $this->infoComplementaire = $infoComplementaire;

        return $this;
    }

    /**
     * Get infoComplementaire
     *
     * @return string
     */
    public function getInfoComplementaire()
    {
        return $this->infoComplementaire;
    }

    /**
     * Set priorite
     *
     * @param string $priorite
     *
     * @return Event
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }

    /**
     * Get priorite
     *
     * @return string
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * Set halleessais
     *
     * @param integer $halleessais
     *
     * @return Event
     */
    public function setHalleessais($halleessais)
    {
        $this->halleessais = $halleessais;

        return $this;
    }

    /**
     * Get halleessais
     *
     * @return integer
     */
    public function getHalleessais()
    {
        return $this->halleessais;
    }

    /**
     * Set lieurdv
     *
     * @param string $lieurdv
     *
     * @return Event
     */
    public function setLieurdv($lieurdv)
    {
        $this->lieurdv = $lieurdv;

        return $this;
    }

    /**
     * Get lieurdv
     *
     * @return string
     */
    public function getLieurdv()
    {
        return $this->lieurdv;
    }

    /**
     * Set nombredebadges
     *
     * @param string $nombredebadges
     *
     * @return Event
     */
    public function setNombredebadges($nombredebadges)
    {
        $this->nombredebadges = $nombredebadges;

        return $this;
    }

    /**
     * Get nombredebadges
     *
     * @return string
     */
    public function getNombredebadges()
    {
        return $this->nombredebadges;
    }

    /**
     * Set entrepriseClient
     *
     * @param string $entrepriseClient
     *
     * @return Event
     */
    public function setEntrepriseClient($entrepriseClient)
    {
        $this->entrepriseClient = $entrepriseClient;

        return $this;
    }

    /**
     * Get entrepriseClient
     *
     * @return string
     */
    public function getEntrepriseClient()
    {
        return $this->entrepriseClient;
    }

    /**
     * Set nomClient
     *
     * @param string $nomClient
     *
     * @return Event
     */
    public function setNomClient($nomClient)
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    /**
     * Get nomClient
     *
     * @return string
     */
    public function getNomClient()
    {
        return $this->nomClient;
    }

    /**
     * Set prenomClient
     *
     * @param string $prenomClient
     *
     * @return Event
     */
    public function setPrenomClient($prenomClient)
    {
        $this->prenomClient = $prenomClient;

        return $this;
    }

    /**
     * Get prenomClient
     *
     * @return string
     */
    public function getPrenomClient()
    {
        return $this->prenomClient;
    }

    /**
     * Set civiliteClient
     *
     * @param string $civiliteClient
     *
     * @return Event
     */
    public function setCiviliteClient($civiliteClient)
    {
        $this->civiliteClient = $civiliteClient;

        return $this;
    }

    /**
     * Get civiliteClient
     *
     * @return string
     */
    public function getCiviliteClient()
    {
        return $this->civiliteClient;
    }

    /**
     * Set serviceClient
     *
     * @param string $serviceClient
     *
     * @return Event
     */
    public function setServiceClient($serviceClient)
    {
        $this->serviceClient = $serviceClient;

        return $this;
    }

    /**
     * Get serviceClient
     *
     * @return string
     */
    public function getServiceClient()
    {
        return $this->serviceClient;
    }

    /**
     * Set villeClient
     *
     * @param string $villeClient
     *
     * @return Event
     */
    public function setVilleClient($villeClient)
    {
        $this->villeClient = $villeClient;

        return $this;
    }

    /**
     * Get villeClient
     *
     * @return string
     */
    public function getVilleClient()
    {
        return $this->villeClient;
    }

    /**
     * Set paysClient
     *
     * @param string $paysClient
     *
     * @return Event
     */
    public function setPaysClient($paysClient)
    {
        $this->paysClient = $paysClient;

        return $this;
    }

    /**
     * Get paysClient
     *
     * @return string
     */
    public function getPaysClient()
    {
        return $this->paysClient;
    }

    /**
     * Set adresseMailClient
     *
     * @param string $adresseMailClient
     *
     * @return Event
     */
    public function setAdresseMailClient($adresseMailClient)
    {
        $this->adresseMailClient = $adresseMailClient;

        return $this;
    }

    /**
     * Get adresseMailClient
     *
     * @return string
     */
    public function getAdresseMailClient()
    {
        return $this->adresseMailClient;
    }

    /**
     * Set telephoneClient
     *
     * @param string $telephoneClient
     *
     * @return Event
     */
    public function setTelephoneClient($telephoneClient)
    {
        $this->telephoneClient = $telephoneClient;

        return $this;
    }

    /**
     * Get telephoneClient
     *
     * @return string
     */
    public function getTelephoneClient()
    {
        return $this->telephoneClient;
    }

    /**
     * Set commentaireClient
     *
     * @param string $commentaireClient
     *
     * @return Event
     */
    public function setCommentaireClient($commentaireClient)
    {
        $this->commentaireClient = $commentaireClient;

        return $this;
    }

    /**
     * Get commentaireClient
     *
     * @return string
     */
    public function getCommentaireClient()
    {
        return $this->commentaireClient;
    }

    /**
     * Set lieudepart
     *
     * @param string $lieudepart
     *
     * @return Event
     */
    public function setLieudepart($lieudepart)
    {
        $this->lieudepart = $lieudepart;

        return $this;
    }

    /**
     * Get lieudepart
     *
     * @return string
     */
    public function getLieudepart()
    {
        return $this->lieudepart;
    }

    /**
     * Set premierContact
     *
     * @param string $premierContact
     *
     * @return Event
     */
    public function setPremierContact($premierContact)
    {
        $this->premierContact = $premierContact;

        return $this;
    }

    /**
     * Get premierContact
     *
     * @return string
     */
    public function getPremierContact()
    {
        return $this->premierContact;
    }

    /**
     * Add parcour
     *
     * @param \OF\CalendarBundle\Entity\Parcours $parcour
     *
     * @return Event
     */
    public function addParcour(\OF\CalendarBundle\Entity\Parcours $parcour)
    {
        $this->parcours[] = $parcour;

        return $this;
    }

    /**
     * Remove parcour
     *
     * @param \OF\CalendarBundle\Entity\Parcours $parcour
     */
    public function removeParcour(\OF\CalendarBundle\Entity\Parcours $parcour)
    {
        $this->parcours->removeElement($parcour);
    }

    /**
     * Get parcours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParcours()
    {
        return $this->parcours;
    }
}
