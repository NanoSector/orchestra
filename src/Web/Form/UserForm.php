<?php

declare(strict_types=1);

namespace Web\Form;

use Domain\Entity\User;
use Domain\Enumeration\Role;
use Symfony\Component\Form\AbstractType;
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
            ->add('email', TextType::class)
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
                'choice_label' => function (?Role $role) {
                    return match ($role) {
                        Role::ROLE_USER => 'Regular user',
                        Role::ROLE_ADMIN => 'Administrator',
                    };
                },
                'preferred_choices' => [
                    Role::ROLE_USER
                ]
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