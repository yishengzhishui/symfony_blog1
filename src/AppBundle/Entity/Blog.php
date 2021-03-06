<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Blog
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogRepository")
 */
class Blog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=90)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blogs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="blogs", cascade={"persist"})
     * @ORM\JoinTable(name="blogs_tags")
     */
    private $tags;

    /**
     *
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $brochure;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Please, upload ")
     *
     */
    private $brochureName;

    /**
     *
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg","image/jpg"})
     */
    private $photo;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload ")
     *
     */
    private $photoName;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

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
     * Set title
     *
     * @param string $title
     *
     * @return Blog
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
     * Set description
     *
     * @param string $description
     *
     * @return Blog
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

    public function __construct() {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag)
    {
        $tag->addBlog($this);

        $this->tags->add($tag);
    }

    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return mixed
     */
    public function getBrochure()
    {
        return $this->brochure;
    }

    /**
     * @param mixed $brochure
     * @return Blog
     */
    public function setBrochure($brochure)
    {
        $this->brochure = $brochure;
        $this->setBrochureName($brochure->getClientOriginalName());
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     * @return Blog
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        $this->setPhotoName($photo->getClientOriginalName());
        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * @param mixed $photoName
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;
    }

    /**
     * @return mixed
     */
    public function getBrochureName()
    {
        return $this->brochureName;
    }

    /**
     * @param mixed $brochureName
     */
    public function setBrochureName($brochureName)
    {
        $this->brochureName = $brochureName;
    }
}

