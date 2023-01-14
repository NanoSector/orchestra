<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Web\Form;

use Domain\Endpoint\EndpointDriver;
use Domain\Entity\Application;
use Domain\Entity\Endpoint;
use phpDocumentor\Reflection\Type;
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
                'class' => Application::class,
                'choice_label' => static fn(Application $u) => $u->getName(),
                'disabled' => true,
            ])
            ->add('url', UrlType::class, [
                'label' => 'URL to fetch'
            ])
            ->add('driver', EnumType::class, [
                'class' => EndpointDriver::class,
                'choice_label' => static fn(EndpointDriver $d) => $d->getFriendlyName(),
            ])
            ->add('driverOptions', TextareaType::class, [
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Endpoint::class,
        ]);
    }
}
