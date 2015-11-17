<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PeriodType extends AbstractType {

	private $unit;
    private $units;

	public function __construct($unit = null, $units = null) {
		$this->unit  = $unit;
		$this->units = $units;
	}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('fromDate', 'date', array(
                'widget' => 'single_text',
            ))
            ->add('toDate', 'date', array(
                'widget' => 'single_text',
            ))
            ->add('amount', 'number', array());

        if(is_null($this->unit) && is_null($this->units)) {
            $builder->add('unit', 'entity', array(
                'class'     =>  'AppBundle:Unit',
                'property'  => 'name',
            ));
        } elseif(!is_null($this->unit)) {
            $builder->add('unit', 'entity', array(
                'class'             =>  'AppBundle:Unit',
                'property'          => 'name',
                'disabled'          => true,
                'preferred_choices' => array($this->unit)
            ));
        } elseif(!is_null($this->units)) {
            $builder->add('unit', 'entity', array(
                'class'             =>  'AppBundle:Unit',
                'choices'           => $this->units,
                'property'          => 'name',
            ));
        }

    }

    /**
     * @return string
     */
    public function getName() {
        return 'period';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UnitPeriods',
            'cascade_validation' => true,
        ));
    }

}