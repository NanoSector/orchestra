<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Web\Form;

use Domain\Entity\Group;
use Domain\Entity\User;
use Domain\Enumeration\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false, // Prevent the password from ever entering the user object
                'required' => $options['require_password'],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm password'],
            ])
            ->add('formRoles', EnumType::class, [
                'class' => Role::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => fn(Role $role) => match ($role) {
                    Role::ROLE_USER => 'Regular user',
                    Role::ROLE_ADMIN => 'Administrator',
                    default => $role->name,
                },
                'preferred_choices' => [
                    Role::ROLE_USER
                ]
            ])
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'multiple' => true,
                'expanded' => true,
                'disabled' => true, // TODO: persisting does not work?
                'choice_label' => static fn (Group $g) => $g->getName(),
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true,
        ]);

        $resolver->setAllowedTypes('require_password', 'bool');
    }
}