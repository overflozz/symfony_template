<?php

namespace OF\CalendarBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Parcours
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\ParcoursRepository")
 */
class Parcours
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
  /**

   * @ORM\OneToMany(targetEntity="OF\CalendarBundle\Entity\ElementVisite", mappedBy="parcours", cascade={"remove", "persist"})

   */

  protected $elementsVisites; // Notez le « s », une annonce est liée à plusieurs candidatures
    /**

   * @ORM\ManyToOne(targetEntity="OF\CalendarBundle\Entity\Event", inversedBy="parcours")

   * @ORM\JoinColumn(nullable=false)

   */

  private $visite;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementsVisites = array();
    }

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
     * Add elementsVisite
     *
     * @param \OF\CalendarBundle\Entity\ElementVisite $elementsVisite
     *
     * @return Parcours
     */
    public function addElementsVisite(\OF\CalendarBundle\Entity\ElementVisite $elementsVisite)
    {
        $this->elementsVisites[] = $elementsVisite;

        return $this;
    }

    /**
     * Remove elementsVisite
     *
     * @param \OF\CalendarBundle\Entity\ElementVisite $elementsVisite
     */
    public function removeElementsVisite(\OF\CalendarBundle\Entity\ElementVisite $elementsVisite)
    {
        $this->elementsVisites->removeElement($elementsVisite);
    }

    /**
     * Get elementsVisites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementsVisites()
    {
        return $this->elementsVisites;
    }

    /**
     * Set visite
     *
     * @param \OF\CalendarBundle\Entity\Event $visite
     *
     * @return Parcours
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
