<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccommodationType extends AbstractType {

    private $create;
    private $locale;

    public function __construct($locale, $create = false) {
        $this->locale = $locale;
        $this->create = $create;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
//            ->add('advertisingPackage', 'entity', array(
//                'class' => 'AppBundle:AdvertisingPackage',
//                'property' => 'name',
//                //'placeholder'   => 'choose_option',
//                'expanded' => true,
//            ))
            ->add('name')
            ->add('wifi', 'text', array(
                'empty_data'  => null,
                'required' => false
            ))
            ->add('accommodationCategory', 'entity', array(
                'class' => 'AppBundle:AccommodationCategory',
                'property' => 'name',
                'required' => true,
                'placeholder' => 'choose_option',
            ))
//            ->add('city', 'entity', array(
//                'class' => 'AppBundle:City',
//                'property' => $this->locale,
//                'required' => true,
//                'placeholder' => 'choose_option',
//            ))
            ->add('address')
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
            ->add('x')
            ->add('y')
            ->add('checkIn')
            ->add('checkOut')
            ->add('web', 'url')
            ->add('email')
            ->add('phone')
            ->add('distance', new \AppBundle\Form\DistanceType())
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
            ->add('description', new \AppBundle\Form\AccommodationDescriptionType());

        if($this->create) {
            $builder
                ->add('fees', new \AppBundle\Form\AccommodationFeeType());
//                ->add('advertisingPackage', new \AppBundle\Form\AdvertisingPackageType());
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
            'csrf_protection' => false,
        ));
    }
}