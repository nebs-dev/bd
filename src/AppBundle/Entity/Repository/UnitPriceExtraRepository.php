<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UnitPriceExtraRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UnitPriceExtraRepository extends EntityRepository {

    /**
     * Get UnitPriceExtra by priceExtra name && unit
     * @param $name
     * @param $unit
     * @return bool|mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUnitPriceExtraByName($name, $unit) {
        $qb = $this->createQueryBuilder('upe')
            ->select('upe')
            ->innerJoin('upe.priceExtra', 'pe')
            ->where('pe.name = :name')
            ->andWhere('upe.unit = :unit')
            ->setParameter('name', $name)
            ->setParameter('unit', $unit);

        return (count($qb->getQuery()->getResult())) ? $qb->getQuery()->getSingleResult() : false;
    }


    /**
     * Delete all from unit_price_extra table for Unit
     * @param $unit
     * @return bool
     */
    public function destroyAllUnit($unit) {
        try {
            $qb = $this->createQueryBuilder('upe')
                ->delete('AppBundle:UnitPriceExtra', 'upe')
                ->where('upe.unit = :unit')
                ->setParameter('unit', $unit);

            $qb->getQuery()->execute();
            return true;

        } catch(\Exception $e) {
            return false;
        }
    }

}