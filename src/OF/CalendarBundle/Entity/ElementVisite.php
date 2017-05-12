<?php

namespace OF\CalendarBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * ElementVisite
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\ElementVisiteRepository")
 */
class ElementVisite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="titre", type="string", length=255)
     */
    protected $titre;
  /**

    * @ORM\Column(name="duree", type="time")

   */

  private $duree;
    /**

   * @ORM\ManyToOne(targetEntity="OF\CalendarBundle\Entity\Event", inversedBy="elementsVisites")

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
     * Set titre
     *
     * @param string $titre
     *
     * @return ElementVisite
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return ElementVisite
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set visite
     *
     * @param \OF\CalendarBundle\Entity\Event $visite
     *
     * @return ElementVisite
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
}
