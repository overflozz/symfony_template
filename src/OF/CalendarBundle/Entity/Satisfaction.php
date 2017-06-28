<?php

namespace OF\CalendarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Event
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="satisfaction")
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\SatisfactionRepository")
 */
class Satisfaction
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

    protected $type;

    /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat1;
        /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat2;
   
       /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat3;
   
       /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat4;
   
       /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat5;

       /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat6;

       /**
     * 
     * @ORM\Column(type="integer", nullable=true)
     */

    protected $resultat7;
   
   
    /**

   * @ORM\ManyToOne(targetEntity="OF\CalendarBundle\Entity\Event", inversedBy="enquetes")

   * @ORM\JoinColumn(nullable=false)

   */

  private $visite;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Satisfaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set resultats
     *
     * @param array $resultats
     *
     * @return Satisfaction
     */
    public function setResultats($resultats)
    {
        $this->resultats = $resultats;

        return $this;
    }

    /**
     * Get resultats
     *
     * @return array
     */
    public function getResultats()
    {
        return $this->resultats;
    }

    /**
     * Set visite
     *
     * @param \OF\CalendarBundle\Entity\Event $visite
     *
     * @return Satisfaction
     */
    public function setVisite(\OF\CalendarBundle\Entity\Event $visite)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite
     *
     * @return \OF\CalendarBundle\Entity\Event
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set resultat1
     *
     * @param integer $resultat1
     *
     * @return Satisfaction
     */
    public function setResultat1($resultat1)
    {
        $this->resultat1 = $resultat1;

        return $this;
    }

    /**
     * Get resultat1
     *
     * @return integer
     */
    public function getResultat1()
    {
        return $this->resultat1;
    }

    /**
     * Set resultat2
     *
     * @param integer $resultat2
     *
     * @return Satisfaction
     */
    public function setResultat2($resultat2)
    {
        $this->resultat2 = $resultat2;

        return $this;
    }

    /**
     * Get resultat2
     *
     * @return integer
     */
    public function getResultat2()
    {
        return $this->resultat2;
    }

    /**
     * Set resultat3
     *
     * @param integer $resultat3
     *
     * @return Satisfaction
     */
    public function setResultat3($resultat3)
    {
        $this->resultat3 = $resultat3;

        return $this;
    }

    /**
     * Get resultat3
     *
     * @return integer
     */
    public function getResultat3()
    {
        return $this->resultat3;
    }

    /**
     * Set resultat4
     *
     * @param integer $resultat4
     *
     * @return Satisfaction
     */
    public function setResultat4($resultat4)
    {
        $this->resultat4 = $resultat4;

        return $this;
    }

    /**
     * Get resultat4
     *
     * @return integer
     */
    public function getResultat4()
    {
        return $this->resultat4;
    }

    /**
     * Set resultat5
     *
     * @param integer $resultat5
     *
     * @return Satisfaction
     */
    public function setResultat5($resultat5)
    {
        $this->resultat5 = $resultat5;

        return $this;
    }

    /**
     * Get resultat5
     *
     * @return integer
     */
    public function getResultat5()
    {
        return $this->resultat5;
    }

    /**
     * Set resultat6
     *
     * @param integer $resultat6
     *
     * @return Satisfaction
     */
    public function setResultat6($resultat6)
    {
        $this->resultat6 = $resultat6;

        return $this;
    }

    /**
     * Get resultat6
     *
     * @return integer
     */
    public function getResultat6()
    {
        return $this->resultat6;
    }

    /**
     * Set resultat7
     *
     * @param integer $resultat7
     *
     * @return Satisfaction
     */
    public function setResultat7($resultat7)
    {
        $this->resultat7 = $resultat7;

        return $this;
    }

    /**
     * Get resultat7
     *
     * @return integer
     */
    public function getResultat7()
    {
        return $this->resultat7;
    }
}
