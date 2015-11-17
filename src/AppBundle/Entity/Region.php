<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="regions")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\RegionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Region {

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
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="regions")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="Subregion", mappedBy="region", cascade={"all"})
     */
    private $subregions;

    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="region", cascade={"all"})
     */
    private $cities;
    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="region")
     */
    private $banners;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\AdditionalOffer\AdditionalOffer", mappedBy="region")
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
        $this->subregions = new ArrayCollection();
        $this->cities = new ArrayCollection();
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
        return 'upload/locations/regions';
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
     * Set country
     * @param \AppBundle\Entity\Country $country
     * @return Region
     */
    public function setCountry(\AppBundle\Entity\Country $country = null) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     * @return \AppBundle\Entity\Country
     */
    public function getCountry() {
        return $this->country;
    }


    /**
     * Add subregions
     * @param \AppBundle\Entity\Subregion $subregions
     * @return Region
     */
    public function addSubregion(\AppBundle\Entity\Subregion $subregions) {
        $this->subregions[] = $subregions;

        return $this;
    }

    /**
     * Remove subregions
     * @param \AppBundle\Entity\Subregion $subregions
     */
    public function removeSubregion(\AppBundle\Entity\Subregion $subregions) {
        $this->subregions->removeElement($subregions);
    }

    /**
     * Get subregions
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubregions() {
        return $this->subregions;
    }

    /**
     * Set hr
     * @param string $hr
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * @return Region
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
     * Add cities
     * @param \AppBundle\Entity\City $cities
     * @return Region
     */
    public function addCity(\AppBundle\Entity\City $cities) {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove cities
     * @param \AppBundle\Entity\City $cities
     */
    public function removeCity(\AppBundle\Entity\City $cities) {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities() {
        return $this->cities;
    }

    /**
     * Set banners
     *
     * @param \AppBundle\Entity\Banner $banners
     * @return Region
     */
    public function setBanners(\AppBundle\Entity\Banner $banners = null)
    {
        $this->banners = $banners;

        return $this;
    }
}
