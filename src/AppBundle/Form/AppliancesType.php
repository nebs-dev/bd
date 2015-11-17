<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AppliancesType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('airCondition')
            ->add('fan')
            ->add('iron')
            ->add('tv')
            ->add('flatscreenTv')
            ->add('satTv')
            ->add('dvd')
            ->add('phone')
            ->add('radio')
            ->add('pc')
            ->add('gameConsole')
            ->add('heating')
            ->add('safe')
            ->add('miniBar')
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appliances';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Appbundle\Entity\UnitAppliances',
        ));
    }

}