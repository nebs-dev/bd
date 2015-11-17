<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccommodationType extends AbstractType {

    private $hosts;
    private $locale;
    private $create;
//    private $cities;

    public function __construct($hosts, $locale, $create = false) {
        $this->hosts = $hosts;
        $this->locale = $locale;
        $this->create = $create;
//        $this->cities = $cities;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('user', 'entity', array(
                'class' => 'UserBundle:User',
                'choices' => $this->hosts,
                'property' => 'username',
                'required' => true,
                'placeholder' => 'choose_host',
            ))
            ->add('name')
            ->add('wifi', 'number', array(
                'empty_data'  => null,
                'required' => false
            ))
            ->add('accommodationCategory', 'entity', array(
                'class' => 'AppBundle:AccommodationCategory',
                'property' => 'name',
                'required' => true,
                'placeholder' => 'choose_option'
            ))
            ->add('address')
            ->add('x')
            ->add('y')
            ->add('checkIn')
            ->add('checkOut')
            ->add('web', 'url')
//            ->add('city', 'entity', array(
//                'class' => 'AppBundle:City',
//                'choices' => array(),
//                'property' => $this->locale,
//                'required' => true,
//                'placeholder' => 'choose_option',
//            ))


//            ->add("city", "text", array(
//                "mapped" => false
//            ))

            ->add('distance', new \AppBundle\Form\DistanceType())
//            ->add('advertisingPackage', 'entity', array(
//                'class' => 'AppBundle:AdvertisingPackage',
//
//                'property' => 'label',
//                //'placeholder'   => 'choose_option',
//                'expanded' => true,
////                'multiple' => true
//            ))
            ->add('activities', 'entity', array(
                'class' => 'AppBundle:AccommodationActivity',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true
            ))
            ->add('facilities', 'entity', array(
                'class' => 'AppBundle:AccommodationFacility',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true
            ))
            ->add('beaches', 'entity', array(
                'class' => 'AppBundle:AccommodationBeach',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true
            ))
            ->add('additionals', 'entity', array(
                'class' => 'AppBundle:AccommodationAdditional',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true
            ))
            ->add('payments', 'entity', array(
                'class' => 'AppBundle:AccommodationPayment',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true
            ))
            ->add('prepay', 'choice', array(
                'choices' => array(
                    5 => '5%',
                    10 => '10%',
                    15 => '15%',
                    20 => '20%',
                    30 => '30%',
                    40 => '40%',
                    50 => '50%',
                    60 => '60%',
                    70 => '70%',
                    80 => '80%',
                    90 => '90%',
                    100 => '100%',
                ),
                'placeholder' => 'none',
            ))
            ->add('description', new \AppBundle\Form\AccommodationDescriptionType());

        if($this->create) {
            $builder
                ->add('fees', new \AppBundle\Form\AccommodationFeeType())
                ->add('advertisingPackage', new \AppBundle\Form\AdvertisingPackageType());
        }
    }

    /**
     * @return string
     */
    public function getName() {
        return 'accommodation';
    }

    ///**
    // * @param OptionsResolver $resolver
    // */
    //public function configureOptions(OptionsResolverInterface $resolver) {
    //    $resolver->setDefaults(array(
    //        'data_class' => 'AppBundle\Entity\Accommodation',
    //        'cascade_validation' => true,
    //    ));
    //}
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Accommodation',
            'cascade_validation' => true,
        ));
    }
}