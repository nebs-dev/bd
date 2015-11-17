<?php

namespace AppBundle\Form\AdditionalOffer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class GalleryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('file', 'file', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'additionalOfferGallery';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdditionalOffer\Gallery',
        ));
    }

}