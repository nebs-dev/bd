<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegistrationType extends AbstractType {

    private $roles;

    public function __construct($roles) {
        $this->roles = $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('username')
            ->add('email')
            ->add('name')
            ->add('surename')
            ->add('address')
            ->add('phone')
            ->add('password', 'repeated', array(
                'first_options'     => array('label' => 'password'),
                'second_options'    => array('label' => 'password_repeat'),
//                'invalid_message'   => 'password_not_equal',
                'data'              => '',
                'required'          => false
            ))
            ->add('isActive')
//            ->add('file', 'file', array(
//                'data_class' => null
//            ))
        ;

        # If user is set, take his role as preferred choice
//        if(!is_null($this->user)) {
//            $roles = $this->role->findAll();
////            $userRole = $this->user->getRoles();
//
//            $builder
//                ->add('roles', 'entity', array(
//                    'class'             =>  'UserBundle:Role',
//                    'choices'           =>  $roles,
//                    'property'          => 'name',
//                    'required'          => true,
//                    'expanded'          => true,
//                    'multiple'          => true
////                    'preferred_choices' => array($userRole[0])
//                ));

            # If no user(registration && user edit profile) no preferred choice
//        } else {
//            $roles = $this->role->getRolesRegistration();

            $builder
                ->add('roles', 'entity', array(
                    'class'     =>  'UserBundle:Role',
                    'choices'   => $this->roles,
                    'property'  => 'name',
                    'required'  => true
                ));
//        }
    }


    public function getName() {
        return 'user';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User',
            'cascade_validation' => true,
        ));
    }

}