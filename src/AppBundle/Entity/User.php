<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Blog", mappedBy="user")
     */
    protected $blogs;

    /**
     * @return mixed
     */
    public function getBlogs()
    {
        return $this->blogs;
    }

    /**
     * @param mixed $blogs
     */
    public function setBlogs($blogs)
    {
        $this->blogs = $blogs;
    }

    public function __construct()
    {
        parent::__construct();
        $this->blogs = new ArrayCollection();
    }
}