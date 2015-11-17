<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SubregionType extends AbstractType {

	private $regions;
	private $locale;

	public function __construct($regions, $locale) {
		$this->regions = $regions;
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
				'choices'           => $this->regions,
				'property'          => $this->locale,
				'required'          => true,
                'placeholder'       => 'choose_option'
			))
		;

	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'subregion';
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Subregion',
			'cascade_validation' => true,
		));
	}

}