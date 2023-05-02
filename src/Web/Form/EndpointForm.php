<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Form;

use Orchestra\Domain\Endpoint\Driver\DriverEnum;
use Orchestra\Domain\Entity\Application;
use Orchestra\Domain\Entity\Endpoint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EndpointForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('application', EntityType::class, [
                'class'        => Application::class,
                'choice_label' => static fn(Application $u) => $u->getName(),
                'disabled'     => true,
            ])
            ->add('url', UrlType::class, [
                'label' => 'URL to fetch',
            ])
            ->add('driver', EnumType::class, [
                'class'        => DriverEnum::class,
                'choice_label' => static fn(DriverEnum $d) => $d->getFriendlyName(),
            ])
            ->add('driverOptions', TextareaType::class, [
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'row_attr' => [
                    'class' => 'mb-0', // remove the mb-3 class
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Endpoint::class,
        ]);
    }
}
