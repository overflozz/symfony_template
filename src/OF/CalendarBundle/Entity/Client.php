<?php

namespace OF\CalendarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Event
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\EventRepository")
 */
class Client
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

    protected $entreprise;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $type;
     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $nom;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $prenom;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $civilite;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $service;
     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $ville;
     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $pays;

     /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $adresseMail;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $telephone;
    /**
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $commentaire;




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
     * Set entreprise
     *
     * @param string $entreprise
     *
     * @return Client
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return string
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }



    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->nom.' '.$this->prenom;
    }

    /**
     * Set adresseMail
     *
     * @param string $adresseMail
     *
     * @return Client
     */
    public function setAdresseMail($adresseMail)
    {
        $this->adresseMail = $adresseMail;

        return $this;
    }

    /**
     * Get adresseMail
     *
     * @return string
     */
    public function getAdresseMail()
    {
        return $this->adresseMail;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Client
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Client
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set civilite
     *
     * @param string $civilite
     *
     * @return Client
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set service
     *
     * @param string $service
     *
     * @return Client
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Client
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Client
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}
