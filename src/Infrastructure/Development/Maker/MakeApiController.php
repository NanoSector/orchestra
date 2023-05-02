<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Infrastructure\Development\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Maker\MakeController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Small wrapper around the make:controller command to facilitate making API controllers.
 */
final class MakeApiController extends AbstractMaker
{
    private string $projectDirectory;

    private MakeController $makeController;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] string $projectDirectory,
        #[Autowire(service: 'maker.maker.make_controller')] MakeController $makeController
    ) {
        $this->projectDirectory = $projectDirectory;
        $this->makeController = $makeController;
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new API controller class';
    }

    public static function getCommandName(): string
    {
        return 'make:controller:api';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument(
                'controller-class',
                InputArgument::OPTIONAL,
                sprintf(
                    'Choose a name for your controller class (e.g. <fg=yellow>%sController</>)',
                    Str::asClassName(Str::getRandomTerm())
                )
            )
            ->addOption(
                'no-template',
                null,
                InputOption::VALUE_NONE,
                'Use this option to disable template generation (will always be overwritten with true for API controllers!)'
            )
            ->setHelp(
                file_get_contents(
                    $this->projectDirectory . '/vendor/symfony/maker-bundle/src/Resources/help/MakeController.txt'
                )
            );
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $this->makeController->configureDependencies($dependencies);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $input->setArgument('controller-class', '\\Api\\Controller\\' . $input->getArgument('controller-class'));
        $input->setOption('no-template', true);

        $this->makeController->generate($input, $io, $generator);
    }
}
