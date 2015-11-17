<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class CityType extends AbstractType {

    private $subregions;
    private $locale;

    public function __construct($subregions, $locale) {
        $this->subregions = $subregions;
        $this->locale = $locale;
    }

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
            ->add('region', 'entity', array(
                'class'             => 'AppBundle:Region',
                'property'          => $this->locale,
                'required'          => true,
                'placeholder'       => 'choose_option'
            ))
            ->add('subregion', 'entity', array(
                'class'             => 'AppBundle:Subregion',
                'property'          => $this->locale,
                'required'          => false,
                'placeholder'       => 'choose_option'
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'city';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\City',
            'cascade_validation' => true
        ));
    }

}