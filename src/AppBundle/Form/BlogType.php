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
                'entry_type' => TagType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
<<<<<<< HEAD
<<<<<<< HEAD
||||||| merged common ancestors
<<<<<<<<< Temporary merge branch 1
            ))
            ->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'))
            ->add('photo', FileType::class, array('label' => 'Photo (png, jpeg)'));
||||||||| merged common ancestors
            ))
            ->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'));
=========
=======

            ))
            ->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'))
            ->add('photo', FileType::class, array('label' => 'Photo '));

>>>>>>> origin/master
            ));

<<<<<<< HEAD
//        if ($options['data']->getId() == null) {
            $builder->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'));
//        }
||||||| merged common ancestors
            ))
            ->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'));
=======
            ))
            ->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'))
            ->add('photo', FileType::class, array('label' => 'Photo (png, jpeg)'));
>>>>>>> n2
||||||| merged common ancestors
//        if ($options['data']->getId() == null) {
            $builder->add('brochure', FileType::class, array('label' => 'Brochure (PDF file)'));
//        }
>>>>>>>>> Temporary merge branch 2
=======

>>>>>>> origin/master
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
