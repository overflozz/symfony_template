<?php

namespace OF\CalendarBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * EventUser
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\EventUserRepository")
 */
class EventUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="roleUser", type="string", length=255)
     */
    protected $roleUser;
  /**

   * @ORM\ManyToOne(targetEntity="OF\CalendarBundle\Entity\Event", inversedBy="applications")

   * @ORM\JoinColumn(nullable=false)

   */

  private $event;
    /**

   * @ORM\ManyToOne(targetEntity="OF\UserBundle\Entity\User", inversedBy="visites")

   * @ORM\JoinColumn(nullable=false)

   */

  private $user;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set roleUser
     *
     * @param string $roleUser
     *
     * @return EventUser
     */
    public function setRoleUser($roleUser)
    {
        $this->roleUser = $roleUser;

        return $this;
    }

    /**
     * Get roleUser
     *
     * @return string
     */
    public function getRoleUser()
    {
        return $this->roleUser;
    }

    /**
     * Set event
     *
     * @param \OF\CalendarBundle\Entity\Event $event
     *
     * @return EventUser
     */
    public function setEvent(\OF\CalendarBundle\Entity\Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \OF\CalendarBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set user
     *
     * @param \OF\UserBundle\Entity\User $user
     *
     * @return EventUser
     */
    public function setUser(\OF\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \OF\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
