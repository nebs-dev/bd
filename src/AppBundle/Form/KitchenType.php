<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class KitchenType extends AbstractType {

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('type', 'choice', array(
				'choices'     => array(
					'independent',
					'livingroom',
					'bedroom',
				),
				'expanded'  => true
			))
			->add('dishes')
			->add('cutlery')
			->add('fridge')
			->add('rings')
			->add('electricKettle')
			->add('coffeeMachine')
			->add('diningroom')
			->add('microwave')
			->add('toaster')
			->add('childrenChair')
			->add('tableChairs')
			->add('oven')
			->add('dishwasher')
		;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'kitchen';
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\UnitKitchen',
		));
	}

}