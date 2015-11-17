<?php

namespace AppBundle\Form\AdditionalOffer;

//use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class AdditionalOfferType extends AbstractType {

    private $locale;
    private $hosts;

    public function __construct($locale, $hosts = array()) {
        $this->locale = $locale;
        $this->hosts = $hosts;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('validUntil', 'date', array(
                'widget' => 'single_text'
            ))
            ->add('status')
//            ->add('country', 'entity', array(
//                'class' => 'AppBundle:Country',
//                'property' => $this->locale,
//                'placeholder' => 'choose_option'
//            ))
//            ->add('region', 'entity', array(
//                'class' => 'AppBundle:Region',
//                'property' => $this->locale,
//                'placeholder' => 'choose_option',
//            ))
//            ->add('subregion', 'entity', array(
//                'class' => 'AppBundle:Subregion',
//                'property' => $this->locale,
//                'placeholder' => 'choose_option',
//            ))
//            ->add('city', 'entity', array(
//                'class' => 'AppBundle:City',
//                'property' => $this->locale,
//                'placeholder' => 'choose_option',
//            ))

//            ->add('country', 'text')
//            ->add('region', 'text')
//            ->add('subregion', 'text')
//            ->add('city', 'text')
            ->add('category', 'entity', array(
                'class' => 'AppBundle:AdditionalOffer\Category',
                'property' => 'name',
                'placeholder' => 'choose_option',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('host', 'entity', array(
                'class' => 'UserBundle:User',
                'choices' => $this->hosts,
                'property' => 'username',
                'placeholder' => 'choose_host',
            ))
            ->add('gallery', 'collection', array(
                'type'      => new GalleryType(),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add('details', new \AppBundle\Form\AdditionalOffer\DetailType())
            ->add('descriptions', new \AppBundle\Form\AdditionalOffer\DescriptionType())
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'additional_offer';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdditionalOffer\AdditionalOffer',
            'cascade_validation' => true,
        ));
    }


}