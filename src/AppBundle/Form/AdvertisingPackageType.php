<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertisingPackageType extends AbstractType {

    private $types;

    public function __construct($types = null) {
        $this->types = $types;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        if(is_null($this->types)) {
            $builder
                ->add('type', 'entity', array(
                    'class' => 'AppBundle:AdvertisingPackageType',
                    'property' => 'name',
                    'expanded' => true
                ));
        } else {
            $builder
                ->add('type', 'entity', array(
                    'class' => 'AppBundle:AdvertisingPackageType',
                    'choices' => $this->types,
                    'property' => 'name',
                    'expanded' => true
                ));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdvertisingPackage'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appbundle_advertisingpackage';
    }
}
