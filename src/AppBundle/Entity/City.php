<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="cities")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class City {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $hr;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $en;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $de;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $it;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $es;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $fr;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cs;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $hu;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ru;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $da;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sv;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sk;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $no;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $fi;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ca;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sr;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mk;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $bs;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tr;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ja;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $zh;

    /**
     * @ORM\ManyToOne(targetEntity="Subregion", inversedBy="cities")
     */
    private $subregion;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="cities")
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity="Accommodation", mappedBy="city")
     */
    private $accommodations;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="city")
     */
    private $banners;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\AdditionalOffer\AdditionalOffer", mappedBy="city")
     */
    private $additionalOffers;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;
    /**
     * @Assert\File(maxSize="2M",
     * mimeTypes={"image/jpeg", "image/gif", "image/png"},
     * maxSizeMessage = "The maxmimum allowed file size is 2MB.",
     * mimeTypesMessage = "Only JPEG, PNG and GIF is allowed.")
     */
    protected $file;


    /**
     * Constructor
     */
    public function __construct() {
        $this->accommodations = new \Doctrine\Common\Collections\ArrayCollection();
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
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/locations/cities';
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        $file_name = $this->getEn() . '.' . $this->file->guessExtension();
        $file_name = str_replace(" ", "", $file_name);
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
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
     * Add accommodations
     * @param \AppBundle\Entity\Accommodation $accommodations
     * @return City
     */
    public function addAccommodation(\AppBundle\Entity\Accommodation $accommodations) {
        $this->accommodations[] = $accommodations;

        return $this;
    }

    /**
     * Remove accommodations
     * @param \AppBundle\Entity\Accommodation $accommodations
     */
    public function removeAccommodation(\AppBundle\Entity\Accommodation $accommodations) {
        $this->accommodations->removeElement($accommodations);
    }

    /**
     * Get accommodations
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccommodations() {
        return $this->accommodations;
    }

    /**
     * Set subregion
     * @param \AppBundle\Entity\Subregion $subregion
     * @return City
     */
    public function setSubregion(\AppBundle\Entity\Subregion $subregion = null) {
        $this->subregion = $subregion;

        return $this;
    }

    /**
     * Get subregion
     * @return \AppBundle\Entity\Subregion
     */
    public function getSubregion() {
        return $this->subregion;
    }

    /**
     * Set hr
     * @param string $hr
     * @return City
     */
    public function setHr($hr) {
        $this->hr = $hr;

        return $this;
    }

    /**
     * Get hr
     * @return string
     */
    public function getHr() {
        return $this->hr;
    }

    /**
     * Set en
     * @param string $en
     * @return City
     */
    public function setEn($en) {
        $this->en = $en;

        return $this;
    }

    /**
     * Get en
     * @return string
     */
    public function getEn() {
        return $this->en;
    }

    /**
     * Set de
     * @param string $de
     * @return City
     */
    public function setDe($de) {
        $this->de = $de;

        return $this;
    }

    /**
     * Get de
     * @return string
     */
    public function getDe() {
        return $this->de;
    }

    /**
     * Set it
     * @param string $it
     * @return City
     */
    public function setIt($it) {
        $this->it = $it;

        return $this;
    }

    /**
     * Get it
     * @return string
     */
    public function getIt() {
        return $this->it;
    }

    /**
     * Set es
     * @param string $es
     * @return City
     */
    public function setEs($es) {
        $this->es = $es;

        return $this;
    }

    /**
     * Get es
     * @return string
     */
    public function getEs() {
        return $this->es;
    }

    /**
     * Set fr
     * @param string $fr
     * @return City
     */
    public function setFr($fr) {
        $this->fr = $fr;

        return $this;
    }

    /**
     * Get fr
     * @return string
     */
    public function getFr() {
        return $this->fr;
    }

    /**
     * Set cs
     * @param string $cs
     * @return City
     */
    public function setCs($cs) {
        $this->cs = $cs;

        return $this;
    }

    /**
     * Get cs
     * @return string
     */
    public function getCs() {
        return $this->cs;
    }

    /**
     * Set sl
     * @param string $sl
     * @return City
     */
    public function setSl($sl) {
        $this->sl = $sl;

        return $this;
    }

    /**
     * Get sl
     * @return string
     */
    public function getSl() {
        return $this->sl;
    }

    /**
     * Set pl
     * @param string $pl
     * @return City
     */
    public function setPl($pl) {
        $this->pl = $pl;

        return $this;
    }

    /**
     * Get pl
     * @return string
     */
    public function getPl() {
        return $this->pl;
    }

    /**
     * Set hu
     * @param string $hu
     * @return City
     */
    public function setHu($hu) {
        $this->hu = $hu;

        return $this;
    }

    /**
     * Get hu
     * @return string
     */
    public function getHu() {
        return $this->hu;
    }

    /**
     * Set ru
     * @param string $ru
     * @return City
     */
    public function setRu($ru) {
        $this->ru = $ru;

        return $this;
    }

    /**
     * Get ru
     * @return string
     */
    public function getRu() {
        return $this->ru;
    }

    /**
     * Set pt
     * @param string $pt
     * @return City
     */
    public function setPt($pt) {
        $this->pt = $pt;

        return $this;
    }

    /**
     * Get pt
     * @return string
     */
    public function getPt() {
        return $this->pt;
    }

    /**
     * Set nl
     * @param string $nl
     * @return City
     */
    public function setNl($nl) {
        $this->nl = $nl;

        return $this;
    }

    /**
     * Get nl
     * @return string
     */
    public function getNl() {
        return $this->nl;
    }

    /**
     * Set da
     * @param string $da
     * @return City
     */
    public function setDa($da) {
        $this->da = $da;

        return $this;
    }

    /**
     * Get da
     * @return string
     */
    public function getDa() {
        return $this->da;
    }

    /**
     * Set sv
     * @param string $sv
     * @return City
     */
    public function setSv($sv) {
        $this->sv = $sv;

        return $this;
    }

    /**
     * Get sv
     * @return string
     */
    public function getSv() {
        return $this->sv;
    }

    /**
     * Set sk
     * @param string $sk
     * @return City
     */
    public function setSk($sk) {
        $this->sk = $sk;

        return $this;
    }

    /**
     * Get sk
     * @return string
     */
    public function getSk() {
        return $this->sk;
    }

    /**
     * Set no
     * @param string $no
     * @return City
     */
    public function setNo($no) {
        $this->no = $no;

        return $this;
    }

    /**
     * Get no
     * @return string
     */
    public function getNo() {
        return $this->no;
    }

    /**
     * Set fi
     * @param string $fi
     * @return City
     */
    public function setFi($fi) {
        $this->fi = $fi;

        return $this;
    }

    /**
     * Get fi
     * @return string
     */
    public function getFi() {
        return $this->fi;
    }

    /**
     * Set ca
     * @param string $ca
     * @return City
     */
    public function setCa($ca) {
        $this->ca = $ca;

        return $this;
    }

    /**
     * Get ca
     * @return string
     */
    public function getCa() {
        return $this->ca;
    }

    /**
     * Set sr
     * @param string $sr
     * @return City
     */
    public function setSr($sr) {
        $this->sr = $sr;

        return $this;
    }

    /**
     * Get sr
     * @return string
     */
    public function getSr() {
        return $this->sr;
    }

    /**
     * Set mk
     * @param string $mk
     * @return City
     */
    public function setMk($mk) {
        $this->mk = $mk;

        return $this;
    }

    /**
     * Get mk
     * @return string
     */
    public function getMk() {
        return $this->mk;
    }

    /**
     * Set bs
     * @param string $bs
     * @return City
     */
    public function setBs($bs) {
        $this->bs = $bs;

        return $this;
    }

    /**
     * Get bs
     * @return string
     */
    public function getBs() {
        return $this->bs;
    }

    /**
     * Set tr
     * @param string $tr
     * @return City
     */
    public function setTr($tr) {
        $this->tr = $tr;

        return $this;
    }

    /**
     * Get tr
     * @return string
     */
    public function getTr() {
        return $this->tr;
    }

    /**
     * Set ja
     * @param string $ja
     * @return City
     */
    public function setJa($ja) {
        $this->ja = $ja;

        return $this;
    }

    /**
     * Get ja
     * @return string
     */
    public function getJa() {
        return $this->ja;
    }

    /**
     * Set zh
     * @param string $zh
     * @return City
     */
    public function setZh($zh) {
        $this->zh = $zh;

        return $this;
    }

    /**
     * Get zh
     * @return string
     */
    public function getZh() {
        return $this->zh;
    }

    /**
     * Add banners
     * @param \AppBundle\Entity\Banner $banners
     * @return City
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
     * Add additionalOffers
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffers
     * @return City
     */
    public function addAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffers) {
        $this->additionalOffers[] = $additionalOffers;

        return $this;
    }

    /**
     * Remove additionalOffers
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffers
     */
    public function removeAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffers) {
        $this->additionalOffers->removeElement($additionalOffers);
    }

    /**
     * Get additionalOffers
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdditionalOffers() {
        return $this->additionalOffers;
    }

    /**
     * Set photo
     * @param string $photo
     * @return City
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
     * Set region
     * @param \AppBundle\Entity\Region $region
     * @return City
     */
    public function setRegion(\AppBundle\Entity\Region $region = null) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     * @return \AppBundle\Entity\Region
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set banners
     *
     * @param \AppBundle\Entity\Banner $banners
     * @return City
     */
    public function setBanners(\AppBundle\Entity\Banner $banners = null)
    {
        $this->banners = $banners;

        return $this;
    }
}
