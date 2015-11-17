<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;
    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="posts")
     */
    private $language;
    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="children")
     **/
    private $parent;
    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="parent")
     **/
    private $children;
    /**
     * @ORM\OneToMany(targetEntity="PostComment", mappedBy="post", cascade={"all"})
     **/
    private $comments;
    /**
     * @Assert\File(maxSize="1M",
     * mimeTypes={"image/jpeg", "image/gif", "image/png"},
     * maxSizeMessage = "The maxmimum allowed file size is 1MB.",
     * mimeTypesMessage = "Only JPEG, PNG and GIF is allowed.")
     */
    protected $file;
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
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        $file = $this->getAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    ##############
    ### UPLOAD ###
    ##############
    /**
     * Sets file.
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    /**
     * Get file.
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function getAbsolutePath() {
        return null === $this->photo
            ? null
            : $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPath() {
        return null === $this->photo
            ? null
            : $this->getUploadDir() . '/' . $this->photo;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        # Create accommodation's directory if not exists
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/posts';
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        $file_name = $this->getId() . '.' . $this->file->guessExtension();
        $file_name = str_replace(" ", "", $file_name);
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
//            $this->getFile()->getClientOriginalName()
            $file_name
        );
        // set the path property to the filename where you've saved the file
        $this->photo = $file_name;
        // clean up the file property as you won't need it anymore
        $this->file = null;
    }


    ###############
    ### /UPLOAD ###
    ###############


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     * @param string $title
     * @return Post
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set photo
     * @param string $photo
     * @return Post
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Set language
     * @param \AppBundle\Entity\Language $language
     * @return Post
     */
    public function setLanguage(\AppBundle\Entity\Language $language = null) {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     * @return \AppBundle\Entity\Language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set parent
     * @param \AppBundle\Entity\Post $parent
     * @return Post
     */
    public function setParent(\AppBundle\Entity\Post $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     * @return \AppBundle\Entity\Post
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add children
     * @param \AppBundle\Entity\Post $children
     * @return Post
     */
    public function addChild(\AppBundle\Entity\Post $children) {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     * @param \AppBundle\Entity\Post $children
     */
    public function removeChild(\AppBundle\Entity\Post $children) {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Set text
     * @param string $text
     * @return Post
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
     * Add comments
     * @param \AppBundle\Entity\PostComment $comments
     * @return Post
     */
    public function addComment(\AppBundle\Entity\PostComment $comments) {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     * @param \AppBundle\Entity\PostComment $comments
     */
    public function removeComment(\AppBundle\Entity\PostComment $comments) {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this->comments;
    }
}
