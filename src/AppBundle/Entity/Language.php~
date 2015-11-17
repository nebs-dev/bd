<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="languages")
 * @ORM\Entity()
 */
class Language {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=3)
     */
    private $code;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    /**
     * @ORM\ManyToMany(targetEntity="\UserBundle\Entity\User", mappedBy="languages")
     */
    private $users;
    /**
     * @ORM\ManyToMany(targetEntity="Banner", mappedBy="languages")
     */
    private $banners;
    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="language")
     */
    private $posts;

    /**
     * Constructor
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set code
     * @param string $code
     * @return Language
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set name
     * @param string $name
     * @return Language
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add users
     * @param \UserBundle\Entity\User $users
     * @return Language
     */
    public function addUser(\UserBundle\Entity\User $users) {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     * @param \UserBundle\Entity\User $users
     */
    public function removeUser(\UserBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Add banners
     * @param \AppBundle\Entity\Banner $banners
     * @return Language
     */
    public function addBanner(\AppBundle\Entity\Banner $banners) {
        $this->banners[] = $banners;

        return $this;
    }

    /**
     * Remove banners
     * @param \AppBundle\Entity\Banner $banners
     */
    public function removeBanner(\AppBundle\Entity\Banner $banners) {
        $this->banners->removeElement($banners);
    }

    /**
     * Get banners
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBanners() {
        return $this->banners;
    }

    /**
     * Add posts
     * @param \AppBundle\Entity\Post $posts
     * @return Language
     */
    public function addPost(\AppBundle\Entity\Post $posts) {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     * @param \AppBundle\Entity\Post $posts
     */
    public function removePost(\AppBundle\Entity\Post $posts) {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts() {
        return $this->posts;
    }
}
