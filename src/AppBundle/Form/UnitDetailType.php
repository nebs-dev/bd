<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UnitDetailType extends AbstractType {

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('doubleBed', 'integer', array(
                'label' => 'doubleBed'
            ))
			->add('singleBed', 'integer', array(
                'label' => 'singleBed'
            ))
			->add('sofaBed', 'integer', array(
                'label' => 'sofaBed'
            ))
			->add('extraBed', 'integer', array(
                'label' => 'extraBed'
            ))
			->add('cot', 'integer', array(
                'label' => 'cot'
            ))
			->add('privateEntrance', 'checkbox', array(
                'label' => 'privateEntrance'
            ))
			->add('accessDisabled', 'checkbox', array(
                'label' => 'accessDisabled'
            ))
			->add('smokingAllowed', 'checkbox', array(
                'label' => 'smokingAllowed'
            ))
			->add('corridor', 'checkbox', array(
                'label' => 'corridor'
            ))
		;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'unit_detail';
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\UnitDetail',
		));
	}

}