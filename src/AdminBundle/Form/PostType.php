<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType {

    private $parents;

    public function __construct($parents) {
        $this->parents = $parents;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title')
            ->add('text', 'textarea')
            ->add('parent', 'entity', array(
                'class' => 'AppBundle:Post',
                'choices' => $this->parents,
                'property' => 'title',
                'placeholder' => 'choose_option'
            ))
            ->add('file', 'file')
            ->add('language', 'entity', array(
                'class' => 'AppBundle:Language',
                'property' => 'name'
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'adminbundle_post';
    }
}
