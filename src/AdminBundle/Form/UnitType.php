<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UnitType extends AbstractType {

	public function __construct() {

	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name')
			->add('categorize', 'choice', array(
				'choices'     => array(
					1 => '1 star',
					2 => '2 stars',
					3 => '3 stars',
					4 => '4 stars',
					5 => '5 stars',
				),
				'placeholder' => 'not_categorized',
			))
			->add('surface')
			->add('capacity')
			->add('minimumStay')
			->add('position', 'choice', array(
				'choices'     => array(
					'basement',
					'ground_floor',
					'1_floor',
					'2_floor',
					'3_floor',
					'4_floor',
					'5_floor',
					'above_5_floor'
				),
				'placeholder' => 'choose_option',
			))
			->add('unitCategory', 'entity', array(
				'class'     =>  'AppBundle:UnitCategory',
				'property'  => 'name',
			))
			->add('accommodation', 'entity', array(
				'class'         => 'AppBundle:Accommodation',
				'property'      => 'name',
				'placeholder'   => 'choose_option',
			))
			->add('basicPrice')
			->add('views', 'entity', array(
				'class'     =>  'AppBundle:UnitView',
				'property'  => 'name',
				'expanded'  => true,
				'multiple'  => true
			))
			->add('bedroom', new \AppBundle\Form\BedroomType())
			->add('bathroom', new \AppBundle\Form\BathroomType())
			->add('livingroom', new \AppBundle\Form\LivingroomType())
			->add('kitchen', new \AppBundle\Form\KitchenType())
			->add('details', new \AppBundle\Form\UnitDetailType())
		;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'unit';
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Unit',
			'cascade_validation' => true,
		));
	}

}