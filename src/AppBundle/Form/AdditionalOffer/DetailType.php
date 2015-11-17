<?php

namespace AppBundle\Form\AdditionalOffer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class DetailType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('address', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('x', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('y', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('email', 'text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Email()
                )
            ))
            ->add('phone')
            ->add('web')
            ->add('facebook')
            ->add('google')
            ->add('twitter')
            ->add('video')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdditionalOffer\Detail'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appbundle_additionaloffer_detail';
    }
}
