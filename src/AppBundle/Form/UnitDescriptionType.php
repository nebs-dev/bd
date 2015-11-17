<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class UnitDescriptionType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('hr')
            ->add('en', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
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
        return 'unit_description';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Appbundle\Entity\UnitDescription',
        ));
    }

}