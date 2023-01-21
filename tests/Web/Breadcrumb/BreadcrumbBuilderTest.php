<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

namespace Web\Tests\Breadcrumb;

use Domain\Entity\Application;
use Domain\Entity\Endpoint;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Web\Breadcrumb\BreadcrumbBuilder;
use Web\Exception\BreadcrumbBuilderException;

class BreadcrumbBuilderTest extends KernelTestCase
{

    public function testApplication(): void
    {
        // Arrange
        $application = new Application();
        $application->setName('Test name');
        $application->setId(4);

        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $breadcrumbItem = $builder->application($application);
        $activeBreadcrumbItem = $builder->application($application, true);

        // Assert
        self::assertEquals('Test name', $breadcrumbItem->name);
        self::assertEquals('/applications/4', $breadcrumbItem->url);
        self::assertFalse($breadcrumbItem->active);

        self::assertEquals('Test name', $activeBreadcrumbItem->name);
        self::assertEquals('/applications/4', $activeBreadcrumbItem->url);
        self::assertTrue($activeBreadcrumbItem->active);
    }

    public function testApplicationWithoutIdShouldThrowException(): void
    {
        // Arrange
        $application = new Application();
        $application->setName('Test name');
        $application->setId(null); // invalid use case

        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $this->expectException(BreadcrumbBuilderException::class);
        $breadcrumbItem = $builder->application($application);
    }

    public function testEndpoint(): void
    {
        // Arrange
        $application = new Application();
        $application->setName('Test name');
        $application->setId(4);

        $endpoint = new Endpoint();
        $endpoint->setName('Test endpoint');
        $endpoint->setId(3);
        $endpoint->setApplication($application);

        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $breadcrumbItem = $builder->endpoint($endpoint);
        $activeBreadcrumbItem = $builder->endpoint($endpoint, true);

        // Assert
        self::assertEquals('Endpoint Test endpoint', $breadcrumbItem->name);
        self::assertEquals('/applications/4/endpoints/3', $breadcrumbItem->url);
        self::assertFalse($breadcrumbItem->active);

        self::assertEquals('Endpoint Test endpoint', $activeBreadcrumbItem->name);
        self::assertEquals('/applications/4/endpoints/3', $activeBreadcrumbItem->url);
        self::assertTrue($activeBreadcrumbItem->active);
    }

    public function testEndpointWithoutApplicationAssociationShouldThrowException(): void
    {
        // Arrange

        $endpoint = new Endpoint();
        $endpoint->setName('Test endpoint');
        $endpoint->setId(3);
        // No application set - invalid use case

        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $this->expectException(BreadcrumbBuilderException::class);
        $breadcrumbItem = $builder->endpoint($endpoint);
    }

    public function testEndpointWithoutApplicationIdShouldThrowException(): void
    {
        // Arrange
        $application = new Application();
        $application->setName('Test name');
        $application->setId(null); // invalid use case

        $endpoint = new Endpoint();
        $endpoint->setName('Test endpoint');
        $endpoint->setId(3);
        $endpoint->setApplication($application);

        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $this->expectException(BreadcrumbBuilderException::class);
        $breadcrumbItem = $builder->endpoint($endpoint);
    }

    public function testEndpointWithoutEndpointIdShouldThrowException(): void
    {
        // Arrange
        $application = new Application();
        $application->setName('Test name');
        $application->setId(4);

        $endpoint = new Endpoint();
        $endpoint->setName('Test endpoint');
        $endpoint->setId(null); // invalid use case
        $endpoint->setApplication($application);

        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $this->expectException(BreadcrumbBuilderException::class);
        $breadcrumbItem = $builder->endpoint($endpoint);
    }

    public function testText(): void
    {
        // Arrange
        $kernel = self::bootKernel();
        $router = $kernel->getContainer()->get('router');
        $builder = new BreadcrumbBuilder($router);

        // Act
        $breadcrumbItem = $builder->text('Test string');
        $activeBreadcrumbItem = $builder->text('Test string', true);

        // Assert
        self::assertEquals('Test string', $breadcrumbItem->name);
        self::assertEquals(null, $breadcrumbItem->url);
        self::assertFalse($breadcrumbItem->active);

        self::assertEquals('Test string', $activeBreadcrumbItem->name);
        self::assertEquals(null, $activeBreadcrumbItem->url);
        self::assertTrue($activeBreadcrumbItem->active);
    }
}
