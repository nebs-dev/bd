<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class SearchType extends AbstractType {

	private $accommodationCategories;

	public function __construct($accommodationCategories) {
		$this->accommodationCategories = $accommodationCategories;
	}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('location', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
	        ->add('accommodationCategory', 'entity', array(
		        'class'     =>  'AppBundle:AccommodationCategory',
		        'choices'   =>  $this->accommodationCategories,
		        'property'  => 'name',
                'placeholder'  => 'any'
	        ))
            ->add('fromDate')
            ->add('toDate')
	        ->add('priceMin')
	        ->add('priceMax')
//	        ->add('checkIn')
//	        ->add('checkOut')
            ->add('categorization', 'choice', array(
                'choices'     => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'placeholder'  => 'any',
                'multiple' => true,
                'expanded' => true
            ))
	        ->add('capacity', 'choice', array(
                'choices'     => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10
                ),
                'placeholder'  => 'any'
            ))
	        ->add('bedroomNumber', 'choice', array(
		        'choices'     => array(
			        '1' => 1,
			        '2' => 2,
			        '3' => 3,
			        '4' => 4,
			        '5' => 5,
		        ),
		        'placeholder'  => 'any'
	        ))
	        ->add('bathroomNumber', 'choice', array(
		        'choices'     => array(
			        '1' => 1,
			        '2' => 2,
			        '3' => 3,
			        '4' => 4,
			        '5' => 5,
		        ),
		        'placeholder'  => 'any'
	        ))
            ->add('airCondition', 'checkbox')
            ->add('animal_welcome', 'checkbox')
            ->add('parking', 'checkbox')
            ->add('internet', 'checkbox')
            ->add('satTv', 'checkbox')
            ->add('heating', 'checkbox')
            ->add('washingMachine', 'checkbox')
            ->add('dishwasher', 'checkbox')
            ->add('grill', 'checkbox')
            ->add('pool', 'checkbox')
            ->add('restaurant', 'checkbox')
//            ->add('center', 'integer')
//            ->add('beach', 'integer')
//            ->add('sea', 'integer')
//            ->add('restaurantDistance', 'integer')
//            ->add('airport', 'integer')
//            ->add('shoppingMall', 'integer')
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'search';
    }

}