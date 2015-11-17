<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ReviewRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReviewRepository extends EntityRepository {

    /**
     * get all Reviews
     * @return array
     */
    public function getAll($status = null) {
        $qb = $this->createQueryBuilder('r')
            ->select('r, u, a')
            ->innerJoin('r.accommodation', 'a')
            ->innerJoin('r.tourist', 'u');

        if(!is_null($status)) {
            $qb->where('r.status = :status')
                ->setParameter('status', $status);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * Get all reviews by accommodation && status
     * @param $accommodation
     * @param int $status
     * @return array
     */
    public function getByAccommodation($accommodation, $status = 1) {
        $qb = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.status = :status')
            ->andWhere('r.accommodation = :accommodation')
            ->setParameter('status', $status)
            ->setParameter('accommodation', $accommodation);

        return $qb->getQuery()->getResult();
    }


    public function getAverageRate($accommodation) {
        $qb = $this->createQueryBuilder('r')
            ->select('avg(r.total) as rate_avg, count(r) as rev_num')
            ->where('r.status = :status')
            ->andWhere('r.accommodation = :accommodation')
            ->setParameter('status', 1)
            ->setParameter('accommodation', $accommodation);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Check if user already reviewed this accommodation
     * @param $accommodation
     * @param $user
     * @return mixed
     */
    public function userReviewed($accommodation, $user) {
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.accommodation = :accommodation')
            ->andWhere('r.tourist = :user')
            ->setParameter('accommodation', $accommodation)
            ->setParameter('user', $user);

        return $qb->getQuery()->getSingleScalarResult();
    }

}