<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="gallery_units")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UnitGalleryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UnitGallery {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="gallery")
     */
    private $unit;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $photo;
    /**
     * @ORM\Column(type="boolean")
     */
    private $featuredImage;
    /**
     * @Assert\File(maxSize="3M",
     * mimeTypes={"image/jpeg", "image/gif", "image/png"},
     * maxSizeMessage = "The maxmimum allowed file size is 3MB.",
     * mimeTypesMessage = "Only JPEG, PNG and GIF is allowed.")
     */
    protected $file;


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
//        return __DIR__.'/../../../../web/'.$this->getUploadDir();
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/galleryUnits/' . $this->unit->getId() . '/';
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
//        $file_name = $this->id . '.' . $this->file->guessExtension();
        $file_name = $file_name = sha1($this->unit->getId() . microtime()) .'.'. $this->file->guessExtension();
        $file_name = str_replace(" ", "", $file_name);
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
//            $this->getFile()->getClientOriginalName()
            $file_name
        );
        // set the path property to the filename where you've saved the file
//        $this->path = $this->getFile()->getClientOriginalName();
        $this->photo = $file_name;
//        $this->setUrl('test');
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
     * Set photo
     * @param string $photo
     * @return Gallery
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
     * Set unit
     * @param \AppBundle\Entity\Unit $unit
     * @return UnitGallery
     */
    public function setUnit(\AppBundle\Entity\Unit $unit = null) {
        $this->unit = $unit;
        return $this;
    }

    /**
     * Get unit
     * @return \AppBundle\Entity\Unit
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * Set featuredImage
     * @param boolean $featuredImage
     * @return UnitGallery
     */
    public function setFeaturedImage($featuredImage) {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    /**
     * Get featuredImage
     * @return boolean
     */
    public function getFeaturedImage() {
        return $this->featuredImage;
    }
}
