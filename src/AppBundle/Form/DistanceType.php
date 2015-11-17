<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DistanceType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('restaurant', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('farmacy', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('nightClub', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('shoppingMall', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('postOffice', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('bus', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('train', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('wellness', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('airport', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('museum', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('sportsCenter', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('casino', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('store', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('golf', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('doctor', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('cinema', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('bank', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('atm', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('gasStation', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('park', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('library', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('fitness', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('nationalPark', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('amusementPark', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('center', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('beach', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ))
            ->add('sea', 'choice', array(
                'choices' => array(
                    50 => '50m',
                    100 => '100m',
                    200 => '200m',
                    300 => '300m',
                    500 => '500m',
                    800 => '800m',
                    1000 => '1km',
                    2000 => '2km',
                    3000 => '3km',
                    4000 => '4km',
                    5000 => '5km',
                    8000 => '8km',
                    10000 => '10km',
                    15000 => '15km',
                    20000 => '20km',
                    30000 => '30km',
                ),
                'placeholder' => '-'
            ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'distance';
    }

    ///**
    // * @param OptionsResolver $resolver
    // */
    //public function configureOptions(OptionsResolver $resolver) {
    //	$resolver->setDefaults(array(
    //		'data_class' => 'AppBundle\Entity\AccommodationDistance'
    //	));
    //}

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Appbundle\Entity\AccommodationDistance',
        ));
    }

}