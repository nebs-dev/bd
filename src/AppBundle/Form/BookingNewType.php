<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class BookingNewType extends AbstractType {

    private $units;

    public function __construct($units) {
        $this->units = $units;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('unit', 'entity', array(
                'class' => 'AppBundle:Unit',
                'choices' => $this->units,
                'property' => 'name',
                'placeholder' => 'choose_option',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('fromDate', 'date')
            ->add('toDate', 'date')
            ->add('price')
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'booking_new';
    }

}