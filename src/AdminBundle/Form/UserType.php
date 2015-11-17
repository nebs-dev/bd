<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType {

    private $role;
    private $user;

    public function __construct($role, $user = null) {
        $this->role = $role;
        $this->user = $user;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username')
            ->add('email')
            ->add('name')
            ->add('surename')
            ->add('address')
            ->add('phone')
            ->add('isActive')
            ->add('note')
//            ->add('file', 'file', array(
//                'data_class' => null
//            ))
        ;

        # If user is set, take his role as preferred choice and passeord is optional
        if(!is_null($this->user)) {
            $roles = $this->role->findAll();
            $userRole = $this->user->getRoles();

            $builder
                ->add('roles', 'entity', array(
                    'class'             =>  'UserBundle:Role',
                    'choices'           =>  $roles,
                    'property'          => 'name',
                    'required'          => true,
//                    'expanded'          => true,
//                    'multiple'          => true,
                    'preferred_choices' => array($userRole[0])
                ))
                ->add('password', 'repeated', array(
                    'first_options'     => array('label' => 'password'),
                    'second_options'    => array('label' => 'password_repeat'),
                    'data'              => '',
                    'mapped'            => false // Disable validation for this field
                ));

        # If no user(registration && user edit profile) no preferred choice && password is mandatory
        } else {
            $roles = $this->role->findAll();

            $builder
                ->add('roles', 'entity', array(
                    'class'     =>  'UserBundle:Role',
                    'choices'   =>  $roles,
                    'property'  => 'name',
                    'required'  => true
                ))
                ->add('password', 'repeated', array(
                    'first_options'     => array('label' => 'password'),
                    'second_options'    => array('label' => 'password_repeat'),
//                'invalid_message'   => 'password_not_equal',
                    'data'              => '',
                ));
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
            'data_class' => 'UserBundle\Entity\User',
            'cascade_validation' => true,
        ));
    }

}