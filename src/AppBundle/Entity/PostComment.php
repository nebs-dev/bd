<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="post_comments")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PostCommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PostComment {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     **/
    private $post;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="comments")
     **/
    private $user;
    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Set createdAt
     * @ORM\PrePersist
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt() {
        return $this->createdAt;
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
     * @return PostComment
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
     * Set post
     * @param \AppBundle\Entity\Post $post
     * @return PostComment
     */
    public function setPost(\AppBundle\Entity\Post $post = null) {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     * @return \AppBundle\Entity\Post
     */
    public function getPost() {
        return $this->post;
    }

    /**
     * Set user
     * @param \UserBundle\Entity\User $user
     * @return PostComment
     */
    public function setUser(\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     * @return \UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }
}
