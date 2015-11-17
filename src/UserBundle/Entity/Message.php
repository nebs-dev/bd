<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messageFrom")
     */
    private $userFrom;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messageTo")
     */
    private $userTo;
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     * @ORM\Column(type="boolean")
     */
    private $new = 1;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;


    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->created = new \DateTime();

        return $this;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set text
     * @param string $text
     * @return Message
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set userFrom
     * @param \UserBundle\Entity\User $userFrom
     * @return Message
     */
    public function setUserFrom(\UserBundle\Entity\User $userFrom = null) {
        $this->userFrom = $userFrom;

        return $this;
    }

    /**
     * Get userFrom
     * @return \UserBundle\Entity\User
     */
    public function getUserFrom() {
        return $this->userFrom;
    }

    /**
     * Set userTo
     * @param \UserBundle\Entity\User $userTo
     * @return Message
     */
    public function setUserTo(\UserBundle\Entity\User $userTo = null) {
        $this->userTo = $userTo;

        return $this;
    }

    /**
     * Get userTo
     * @return \UserBundle\Entity\User
     */
    public function getUserTo() {
        return $this->userTo;
    }

    /**
     * Set created
     * @param \DateTime $created
     * @return Message
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set new
     * @param boolean $new
     * @return Message
     */
    public function setNew($new) {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     * @return boolean
     */
    public function getNew() {
        return $this->new;
    }
}
