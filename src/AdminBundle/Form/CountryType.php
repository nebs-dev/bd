<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CountryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('file', 'file')
            ->add('hr')
            ->add('en')
            ->add('de')
            ->add('it')
            ->add('es')
            ->add('fr')
            ->add('cs')
            ->add('sl')
            ->add('pl')
            ->add('hu')
            ->add('ru')
            ->add('pt')
            ->add('nl')
            ->add('da')
            ->add('sv')
            ->add('sk')
            ->add('no')
            ->add('fi')
            ->add('ca')
            ->add('sr')
            ->add('mk')
            ->add('bs')
            ->add('tr')
            ->add('ja')
            ->add('zh')
        ;

    }

    /**
     * @return string
     */
    public function getName() {
        return 'country';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Country',
            'cascade_validation' => true,
        ));
    }

}