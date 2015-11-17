<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class BathroomType extends AbstractType {

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('number', 'integer', array(
                'label' => 'number'
            ))
			->add('bathtub', 'checkbox', array(
                'label' => 'bathtub'
            ))
			->add('bidet', 'checkbox', array(
                'label' => 'bidet'
            ))
			->add('shower', 'checkbox', array(
                'label' => 'shower'
            ))
			->add('jacuzzi', 'checkbox', array(
                'label' => 'jacuzzi'
            ))
			->add('sauna', 'checkbox', array(
                'label' => 'sauna'
            ))
			->add('bathrobe', 'checkbox', array(
                'label' => 'bathrobe'
            ))
			->add('slippers', 'checkbox', array(
                'label' => 'slippers'
            ))
			->add('toiletries', 'checkbox', array(
                'label' => 'toiletries'
            ))
			->add('hairDryer', 'checkbox', array(
                'label' => 'hairDryer'
            ))
			->add('sharedToilet', 'checkbox', array(
                'label' => 'sharedToilet'
            ))
			->add('separateToilet', 'checkbox', array(
                'label' => 'separateToilet'
            ))
			->add('washingMachine', 'checkbox', array(
                'label' => 'washingMachine'
            ))
			->add('clothesDryer', 'checkbox', array(
                'label' => 'clothesDryer'
            ))
			->add('towels', 'checkbox', array(
                'label' => 'towels'
            ))
		;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'bathroom';
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\UnitBathroom',
		));
	}

}