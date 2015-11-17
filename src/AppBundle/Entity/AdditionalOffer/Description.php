<?php

namespace AppBundle\Entity\AdditionalOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="additional_offer_descriptions")
 * @ORM\Entity()
 */
class Description {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="AdditionalOffer", mappedBy="descriptions")
     */
    private $additionalOffer;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $hr;
    /**
     * @ORM\Column(type="text")
     */
    private $en;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $de;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $it;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $es;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $fr;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cs;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sl;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pl;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $hu;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ru;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pt;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $nl;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $da;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sv;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sk;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $no;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $fi;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ca;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sr;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mk;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bs;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tr;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ja;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $zh;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set hr
     * @param string $hr
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * @return Description
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
     * Set additionalOffer
     * @param \AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer
     * @return Description
     */
    public function setAdditionalOffer(\AppBundle\Entity\AdditionalOffer\AdditionalOffer $additionalOffer = null) {
        $this->additionalOffer = $additionalOffer;

        return $this;
    }

    /**
     * Get additionalOffer
     * @return \AppBundle\Entity\AdditionalOffer\AdditionalOffer
     */
    public function getAdditionalOffer() {
        return $this->additionalOffer;
    }
}
