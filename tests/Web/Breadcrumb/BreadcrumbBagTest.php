<?php
/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Tests\Breadcrumb;

use Orchestra\Web\Breadcrumb\BreadcrumbBag;
use Orchestra\Web\Breadcrumb\BreadcrumbItem;
use Orchestra\Web\Exception\BreadcrumbException;
use PHPUnit\Framework\TestCase;
use stdClass;

class BreadcrumbBagTest extends TestCase
{

    public function testAdd(): void
    {
        // Arrange
        $bag = new BreadcrumbBag();

        $item1 = new BreadcrumbItem('Test', null);
        $item2 = new BreadcrumbItem('Test2', null);
        $item3 = new BreadcrumbItem('Test3', null);

        // Act
        $bag->add([
            'first'  => $item1,
            'second' => $item2,
            'third'  => $item3
        ]);

        // Assert
        self::assertCount(3, $bag);
        self::assertContains($item1, $bag);
        self::assertTrue($bag->has('first'));
        self::assertContains($item2, $bag);
        self::assertTrue($bag->has('second'));
        self::assertContains($item3, $bag);
        self::assertTrue($bag->has('third'));
    }

    public function testAddWithNonStringKeys(): void
    {
        // Arrange
        $bag = new BreadcrumbBag();

        $item1 = new BreadcrumbItem('Test', null);
        $item2 = new BreadcrumbItem('Test2', null);
        $item3 = new BreadcrumbItem('Test3', null);

        // Act
        $this->expectException(BreadcrumbException::class);
        $bag->add([
            0 => $item1,
            1 => $item2,
            2 => $item3
        ]);
    }

    public function testReplace(): void
    {
        // Arrange
        $bag = new BreadcrumbBag();

        $item1 = new BreadcrumbItem('Test', null);
        $item2 = new BreadcrumbItem('Test2', null);
        $item3 = new BreadcrumbItem('Test3', null);

        $bag->add([
            'first' => $item1,
        ]);

        // Act
        $bag->replace([
            'second' => $item2,
            'third'  => $item3
        ]);

        // Assert
        self::assertCount(2, $bag);
        self::assertNotContains($item1, $bag);
        self::assertFalse($bag->has('first'));
        self::assertContains($item2, $bag);
        self::assertTrue($bag->has('second'));
        self::assertContains($item3, $bag);
        self::assertTrue($bag->has('third'));
    }

    public function testReplaceWithNonStringKeys(): void
    {
        // Arrange
        $bag = new BreadcrumbBag();

        $item1 = new BreadcrumbItem('Test', null);
        $item2 = new BreadcrumbItem('Test2', null);
        $item3 = new BreadcrumbItem('Test3', null);

        $bag->add([
            'first' => $item1,
        ]);

        // Act
        $this->expectException(BreadcrumbException::class);
        $bag->replace([
            1 => $item2,
            2 => $item3
        ]);
    }

    public function testSet(): void
    {
        // Arrange
        $bag = new BreadcrumbBag();

        $item1 = new BreadcrumbItem('Test', null);
        $item2 = new BreadcrumbItem('Test2', null);
        $item3 = new BreadcrumbItem('Test3', null);

        // Act
        $bag->set('first', $item1);
        $bag->set('second', $item2);
        $bag->set('third', $item3);

        // Assert
        self::assertCount(3, $bag);
        self::assertContains($item1, $bag);
        self::assertTrue($bag->has('first'));
        self::assertContains($item2, $bag);
        self::assertTrue($bag->has('second'));
        self::assertContains($item3, $bag);
        self::assertTrue($bag->has('third'));
    }

    public function testSetWithInvalidType(): void
    {
        // Arrange
        $bag = new BreadcrumbBag();

        // Act
        $this->expectException(BreadcrumbException::class);
        $bag->set('first', new stdClass());
    }
}
