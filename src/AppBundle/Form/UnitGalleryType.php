<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitGalleryType extends AbstractType {

	private $units;

	public function __construct($units) {
		$this->units = $units;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('unit', 'entity', array(
				'class'     =>  'AppBundle:Unit',
				'choices'   =>  $this->units,
				'property'  => 'name',
			))
			->add('file')
		;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'unitGallery';
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\UnitGallery',
			'cascade_validation' => true,
		));
	}

}