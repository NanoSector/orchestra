<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class            => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class             => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class                    => ['dev' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                      => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class        => ['dev' => true, 'test' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class              => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class                    => ['dev' => true],
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class           => ['all' => true],
    Orchestra\Bundle\Endpoint\OrchestraEndpointBundle::class         => ['all' => true],
    Symfony\UX\StimulusBundle\StimulusBundle::class => ['all' => true],
];
