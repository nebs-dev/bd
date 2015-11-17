<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BannerType extends AbstractType {

    private $locale;

    public function __construct($locale) {
        $this->locale = $locale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title')
//            ->add('countries', 'entity', array(
//                'class'     => 'AppBundle:Country',
//                'property'  => $this->locale,
//                'multiple'  => true
//            ))
//            ->add('regions', 'entity', array(
//                'class'     => 'AppBundle:Region',
//                'property'  => $this->locale,
//                'multiple'  => true
//            ))
//            ->add('subregions', 'entity', array(
//                'class'     => 'AppBundle:Subregion',
//                'property'  => $this->locale,
//                'multiple'  => true
//            ))
//            ->add('cities', 'entity', array(
//                'class'     => 'AppBundle:City',
//                'property'  => $this->locale,
//                'multiple'  => true
//            ))
            ->add('languages', 'entity', array(
                'class'     => 'AppBundle:Language',
                'property'  => 'name',
                'multiple'  => true
            ))
            ->add('file', 'file')
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'banner';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Banner',
            'cascade_validation' => true,
        ));
    }

}