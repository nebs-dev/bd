<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
	private $role;
	private $user;
	private $locale;

	public function __construct($role, $user = null, $locale) {
		$this->role = $role;
		$this->user = $user;
		$this->locale = $locale;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('username')
			->add('email')
			->add('name')
			->add('surename')
			->add('title', 'choice', array(
				'choices'     => array(
					'mr'   => 'Mr',
					'mrs'  => 'Mrs',
					'ms'   => 'Ms',
					'miss' => 'Miss',
				),
				'placeholder' => 'choose_option',
			))
			->add('country', 'entity', array(
				'class'       => 'AppBundle:Country',
				'property'    => $this->locale,
				'placeholder' => 'choose_option',
			))
			->add('city')
			->add('address')
			->add('phone')
			->add('skype')
			->add('google')
			->add('type', 'choice', array(
				'choices'     => array(
					'private',
					'company'
				),
				'placeholder' => 'choose_option',
			))
			->add('zip')
			->add('companyName')
			->add('companyVAT')
			->add('languages', 'entity', array(
				'class'    => 'AppBundle:Language',
				'property' => 'name',
				'multiple' => true,
				'expanded' => true
			));
		# If user is set, take his role as preferred choice and passeord is optional
		if( ! is_null($this->user)) {
			$roles    = $this->role->findAll();
			$userRole = $this->user->getRoles();
			$builder
				->add('password', 'repeated', array(
					'first_options'  => array('label' => 'password'),
					'second_options' => array('label' => 'password_repeat'),
					'data'           => '',
					'mapped'         => false // Disable validation for this field
				));
			# If no user(registration && user edit profile) no preferred choice && password is mandatory
//		} else {
//			$roles = $this->role->findAll();
//
//			$builder
//				->add('roles', 'entity', array(
//					'class'     =>  'UserBundle:Role',
//					'choices'   =>  $roles,
//					'property'  => 'name',
//					'required'  => true
//				))
//				->add('password', 'repeated', array(
//					'first_options'     => array('label' => 'password'),
//					'second_options'    => array('label' => 'password_repeat'),
////                'invalid_message'   => 'password_not_equal',
//					'data'              => '',
//				));
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'user';
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class'         => 'UserBundle\Entity\User',
			'cascade_validation' => true,
		));
	}
}