<?php

namespace AppBundle\Entity\AdditionalOffer\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AdditionalOfferRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdditionalOfferRepository extends EntityRepository {

    /**
     * Get all Additional offers
     * @return array
     */
    public function getAll() {
        $qb = $this->createQueryBuilder('a')
            ->select('a, details, category, gallery, descriptions')
            ->leftJoin('a.descriptions', 'descriptions')
            ->leftJoin('a.details', 'details')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.gallery', 'gallery')
            ->leftJoin('a.country', 'country')
            ->leftJoin('a.region', 'region')
            ->leftJoin('a.subregion', 'subregion')
            ->leftJoin('a.city', 'city');

        return $qb->getQuery()->getResult();
    }


    /**
     * Get Additional offers by location/locationType
     * @param $location
     * @param $locationType
     * @return array
     */
    public function getByLocation($location, $locationType) {
        $qb = $this->createQueryBuilder('a')
            ->select('a, details, category, gallery, descriptions')
            ->leftJoin('a.descriptions', 'descriptions')
            ->leftJoin('a.details', 'details')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.gallery', 'gallery')
            ->leftJoin('a.country', 'country')
            ->leftJoin('a.region', 'region')
            ->leftJoin('a.subregion', 'subregion')
            ->leftJoin('a.city', 'city');

        switch($locationType) {
            case 'region':
                $qb->where('region.en = :location')
                    ->orWhere('EXISTS(
                        SELECT 1 FROM AppBundle:Region r
                        INNER JOIN r.country c
                        WHERE r.en = :location AND c.id = country.id
                    )');
                break;
            case 'subregion':
                $qb->where('subregion.en = :location')
                    ->orWhere('EXISTS(
                        SELECT 1 FROM AppBundle:Subregion s
                        INNER JOIN s.region r
                        INNER JOIN r.country c
                        WHERE s.en = :location AND (r.id = region.id OR c.id = country.id)
                    )');
                break;
            case 'city':
                $qb->where('city.en = :location')
                    ->orWhere('EXISTS(
                        SELECT 1 FROM AppBundle:City ci
                        INNER JOIN ci.subregion s
                        INNER JOIN s.region r
                        INNER JOIN r.country c
                        WHERE ci.en = :location AND (s.id = subregion.id OR r.id = region.id OR c.id = country.id)
                    )');
                break;
            default:
                $qb->where('country.en = :location');
                break;
        }

        $qb->andWhere('a.status = 1')
            ->setParameter('location', $location);

        return $qb->getQuery()->getResult();
    }


    /**
     * Search by location && category
     * @param $location
     * @param $locationType
     * @param $category
     * @return array
     */
    public function searchByLocationCategory($location, $locationType, $category) {
        $qb = $this->createQueryBuilder('a')
            ->select('a, details, category, gallery, descriptions')
            ->leftJoin('a.descriptions', 'descriptions')
            ->leftJoin('a.details', 'details')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.gallery', 'gallery')
            ->leftJoin('a.country', 'country')
            ->leftJoin('a.region', 'region')
            ->leftJoin('a.subregion', 'subregion')
            ->leftJoin('a.city', 'city');

        if(!is_null($locationType) && !is_null($location)) {
            switch($locationType) {
                case 'country':
                    $qb->where('country.en = :location')
                        ->orWhere('EXISTS(
                            SELECT 1 FROM AppBundle:Region r
                            INNER JOIN r.country c
                            WHERE c.en = :location AND r.id = region.id
                        )')
                        ->orWhere('EXISTS(
                            SELECT 1 FROM AppBundle:Subregion s
                            INNER JOIN s.region reg
                            INNER JOIN reg.country co
                            WHERE co.en = :location AND s.id = subregion.id
                        )')
                        ->orWhere('EXISTS(
                            SELECT 1 FROM AppBundle:City ci
                            INNER JOIN ci.subregion sub
                            INNER JOIN sub.region regio
                            INNER JOIN regio.country countr
                            WHERE countr.en = :location AND ci.id = city.id
                        )');
                    break;
                case 'region':
                    $qb->where('region.en = :location')
                        ->orWhere('EXISTS(
                            SELECT 1 FROM AppBundle:Subregion s
                            INNER JOIN s.region r
                            WHERE r.en = :location AND s.id = subregion.id
                        )')
                        ->orWhere('EXISTS(
                            SELECT 1 FROM AppBundle:City ci
                            INNER JOIN ci.subregion sub
                            INNER JOIN sub.region regio
                            WHERE regio.en = :location AND ci.id = city.id
                        )');
                    break;
                case 'subregion':
                    $qb->where('subregion.en = :location')
                        ->orWhere('EXISTS(
                            SELECT 1 FROM AppBundle:City ci
                            INNER JOIN ci.subregion s
                            WHERE s.en = :location AND ci.id = city.id
                        )');
                    break;
                default:
                    $qb->where('city.en = :location');
                    break;
            }

            $qb->setParameter('location', $location);
        }

        if(!empty($category)) {
            $qb
                ->andWhere('category.name = :category')
                ->setParameter('category', $category->getName());
        }

        $qb->andWhere('a.status = 1');

        return $qb->getQuery()->getResult();
    }

}
