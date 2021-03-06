<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('type', 'entity', array(
                'class' => 'AppBundle:AccommodationFeeType',
                'property' => 'name',
                'expanded' => true
            ))
            ->add('status', 'checkbox')
            ->add('validUntil', 'date', array(
                'widget' => 'single_text'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AccommodationFee'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'adminbundle_fee';
    }
}
