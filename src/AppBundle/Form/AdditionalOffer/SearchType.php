<?php

namespace AppBundle\Form\AdditionalOffer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class SearchType extends AbstractType {


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('location', 'text')
            ->add('category', 'entity', array(
                'class'     =>  'AppBundle:AdditionalOffer\Category',
                'property'  => 'name',
                'placeholder' => 'any'
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'additional_offer_search';
    }

}