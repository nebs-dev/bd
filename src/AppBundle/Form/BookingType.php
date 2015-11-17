<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class BookingType extends AbstractType {

    private $accommodation;
    private $hidden;
    private $units;

    public function __construct($accommodation = false, $hidden = false, $units = false) {
        $this->accommodation = $accommodation;
        $this->units = $units;
        $this->hidden = $hidden;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        # Checkout
        if($this->hidden) {

            $builder
                ->add('unit', 'hidden')
                ->add('checkIn', 'hidden')
                ->add('checkOut', 'hidden')
                ->add('price', 'hidden');

        # Single
        } else {
            $units = ($this->units == false) ? $this->accommodation->getUnits() : $this->units;

            $builder
                ->add('unit', 'entity', array(
                    'class' => 'AppBundle:Unit',
                    'choices' => $units,
                    'property' => 'name',
                    'placeholder' => 'choose_option',
                    'constraints' => array(
                        new NotBlank()
                    )
                ))
                ->add('checkIn')
                ->add('checkOut')
                ->add('price');
        }

    }

    /**
     * @return string
     */
    public function getName() {
        return 'booking';
    }

}