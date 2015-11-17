<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BannerRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BannerRepository extends EntityRepository {

    /**
     * Get all Banners
     * @return array
     */
    public function getAll() {
        $qb = $this->createQueryBuilder('b')
            ->select('b, country, region, subregion, city, language')
            ->leftJoin('b.languages', 'language')
            ->leftJoin('b.country', 'country')
            ->leftJoin('b.region', 'region')
            ->leftJoin('b.subregion', 'subregion')
            ->leftJoin('b.city', 'city');

        return $qb->getQuery()->getResult();
    }

    /**
     * Get banners by type && language code && location if is not NULL
     * @param $type
     * @param $langCode
     * @param null $locationType
     * @param null $location
     * @return array
     */
    public function getBanners($langCode, $locationType = null, $location = null) {
        $qb = $this->createQueryBuilder('b')
            ->select('b, country, region, subregion, city, language')
            ->leftJoin('b.languages', 'language')
            ->leftJoin('b.country', 'country')
            ->leftJoin('b.region', 'region')
            ->leftJoin('b.subregion', 'subregion')
            ->leftJoin('b.city', 'city')
            ->leftJoin('city.region', 'cityRegion')
            ->leftJoin('cityRegion.country', 'cityCountry')
            ->andWhere('language.code = :langCode')
            ->setParameter('langCode', $langCode);

        if(!is_null($locationType) && !is_null($location)) {
            switch($locationType) {
                case 'country':
                    $qb->andWhere('country.en = :location');
                    break;
                case 'region':
                    $qb
                        ->andWhere('region.en = :location OR country.en = :country')
                        ->setParameter('country', $location->getCountry()->getEn());
                    break;
                case 'subregion':
                    $qb
                        ->andWhere('subregion.en = :location OR region.en = :region OR country.en = :country')
                        ->setParameter('country', $location->getRegion()->getCountry()->getEn())
                        ->setParameter('region', $location->getRegion()->getEn());
                    break;
                case 'city':
                    $qb
                        ->andWhere('city.en = :location OR country.en = :country OR region.en = :region OR subregion.en = :subregion')
                        ->setParameter('country', $location->getRegion()->getCountry()->getEn())
                        ->setParameter('region', $location->getRegion()->getEn())
                        ->setParameter('subregion', $location->getSubregion()->getEn());

                    break;
                default:
                    $qb->andWhere('country.en = :location');
                    break;
            }

            $qb->setParameter('location', $location->getEn());
        }

        $result = $qb->getQuery()->getResult();
        $numResults = count($result);

        if($numResults >= 3)
            return $result;

        $keys = array();
        foreach($result as $res) {
            array_push($keys, $res->getId());
        }

        $limit = 3 - $numResults;

        # Search for more accommodations
        $qb2 = $this->createQueryBuilder('b')
            ->select('b')
            ->groupBy('b.id')
            ->where("b.id NOT IN (:keys)")
            ->setParameter('keys', $keys)
            ->setMaxResults($limit);

        foreach($qb2->getQuery()->getResult() as $acc) {
            array_push($result, $acc);
        }

        return $result;
    }

}
