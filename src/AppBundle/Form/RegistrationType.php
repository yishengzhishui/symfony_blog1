<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
            ]);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray) {
                    return implode(', ', $rolesAsArray);
                },
                function ($rolesAsString) {
                    // transform the string back to an array
                    // 将字符串转回数组
                    return explode(', ', $rolesAsString);
                }
            ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}