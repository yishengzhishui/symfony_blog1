<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('description')
            ->add('tags', CollectionType::class, array(
                'data_class' => null,
                'entry_type' => TagType::class, array('data_class' => null),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('brochure', FileType::class, array('data_class' => null,'label' => 'Brochure (PDF file)'))
            ->add('photo', FileType::class, array('data_class' => null,'label' => 'Photo (png, jpeg)'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Blog'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_blog';
    }


}
