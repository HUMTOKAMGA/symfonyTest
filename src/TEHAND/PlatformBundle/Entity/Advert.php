<?php

namespace TEHAND\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TEHAND\PlatformBundle\Entity\Image;
use TEHAND\PlatformBundle\Entity\Category;
use TEHAND\PlatformBundle\Entity\Application;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="TEHAND\PlatformBundle\Repository\AdvertRepository")
 */
class Advert
{
    
    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist"})
     */
    private $image;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"})
     * @ORM\JoinTable(name="advert_category")
     */
    private $categories;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Application", mappedBy="advert")
     */
    private $applications; //Ici pour signifier qu'une annonce peux faire appelle à +sieurs candidatures
 
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;
    
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
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
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \TEHAND\PlatformBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(\TEHAND\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \TEHAND\PlatformBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \TEHAND\PlatformBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(\TEHAND\PlatformBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \TEHAND\PlatformBundle\Entity\Category $category
     */
    public function removeCategory(\TEHAND\PlatformBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add application
     *
     * @param \TEHAND\PlatformBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(\TEHAND\PlatformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;
        
        $application->setAdvert($this);

        return $this;
    }

    /**
     * Remove application
     *
     * @param \TEHAND\PlatformBundle\Entity\Application $application
     */
    public function removeApplication(\TEHAND\PlatformBundle\Entity\Application $application)
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
}
