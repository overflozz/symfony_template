<?php

namespace OF\CalendarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
/**
 * Document
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="OF\CalendarBundle\Repository\DocumentRepository")
 */
class Document{

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
     * 
     * @ORM\Column(type="string",length=512, nullable=true)
     */
    protected $category;

      /**

   * @ORM\Column(name="url", type="string", length=255)

   */

  private $url;


  /**

   * @ORM\Column(name="alt", type="string", length=255)

   */

  private $alt;
  
    protected $file;


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
     * Set title
     *
     * @param string $title
     *
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Document
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
      public function getFile()

  {

    return $this->file;

  }


  public function setFile(UploadedFile $file = null)

  {

    $this->file = $file;

  }

  public function upload()

  {

    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien

    if (null === $this->file) {

      return;

    }


    // On récupère le nom original du fichier de l'internaute

    $name = $this->file->getClientOriginalName();


    // On déplace le fichier envoyé dans le répertoire de notre choix

    $this->file->move($this->getUploadRootDir(), $name);


    // On sauvegarde le nom de fichier dans notre attribut $url

    $this->url = $name;


    // On crée également le futur attribut alt de notre balise <img>

    $this->alt = $name;

  }


  public function getUploadDir()

  {

    // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)

    return 'uploads/documents';

  }


  protected function getUploadRootDir()

  {

    // On retourne le chemin relatif vers l'image pour notre code PHP

    return __DIR__.'/../../../../web/'.$this->getUploadDir();

  }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Document
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Document
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Document
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
}
