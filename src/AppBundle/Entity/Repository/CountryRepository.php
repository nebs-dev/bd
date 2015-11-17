<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CountryRepository extends EntityRepository {

    public function getByHr($hr) {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.hr = :hr')
            ->setParameter('hr', $hr);
        return $qb->getQuery()->getSingleResult();
    }

    public function getByEn($en) {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.en = :en')
            ->setParameter('en', $en);

        return (count($qb->getQuery()->getResult()) == 0) ? false : $qb->getQuery()->getSingleResult();
    }

    /**
     * Search autocomplete by string
     * @param $string
     * @return array
     */
    public function searchCountryByString($string) {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.en like :string')
            ->orWhere('c.hr like :string')
            ->orWhere('c.de like :string')
            ->orWhere('c.it like :string')
            ->orWhere('c.es like :string')
            ->orWhere('c.fr like :string')
            ->orWhere('c.cs like :string')
            ->orWhere('c.sl like :string')
            ->orWhere('c.pl like :string')
            ->orWhere('c.hu like :string')
            ->orWhere('c.ru like :string')
            ->orWhere('c.pt like :string')
            ->orWhere('c.nl like :string')
            ->orWhere('c.da like :string')
            ->orWhere('c.sv like :string')
            ->orWhere('c.sk like :string')
            ->orWhere('c.no like :string')
            ->orWhere('c.fi like :string')
            ->orWhere('c.ca like :string')
            ->orWhere('c.sr like :string')
            ->orWhere('c.mk like :string')
            ->orWhere('c.bs like :string')
            ->orWhere('c.tr like :string')
            ->orWhere('c.ja like :string')
            ->orWhere('c.zh like :string')
            ->setParameter('string', '%' . $string . '%')
            ->setMaxResults(20);

        return $qb->getQuery()->getResult();
    }
}
