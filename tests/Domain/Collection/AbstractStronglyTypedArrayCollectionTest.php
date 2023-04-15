<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Tests\Collection;

use InvalidArgumentException;
use Orchestra\Infrastructure\Collection\AbstractStronglyTypedArrayCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

class AbstractStronglyTypedArrayCollectionTest extends TestCase
{

    public function testAddCanAddObjectsOfWantedType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $subject->add(new stdClass());
        self::assertCount(1, $subject);
    }

    public function testAddCannotAddNull(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->add(null);
    }

    public function testAddCannotAddObjectsOfScalarType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->add(42);
    }

    public function testConstructCanAddObjectsOfWantedType(): void
    {
        $elements = [
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ];

        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);

        self::assertCount(5, $subject);
    }

    public function testConstructCannotAddNullValues(): void
    {
        $elements = [
            new stdClass(),
            null,
            null,
        ];

        $this->expectException(InvalidArgumentException::class);
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);
    }

    public function testConstructCannotAddObjectsOfScalarType(): void
    {
        $elements = [
            1,
            'test',
            null,
        ];

        $this->expectException(InvalidArgumentException::class);
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);
    }

    public function testMapAlwaysReturnsArrayCollection(): void
    {
        $elements = [
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ];

        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);

        $result = $subject->map(static fn(stdClass $e) => $e);

        self::assertNotInstanceOf(AbstractStronglyTypedArrayCollection::class, $result);
        self::assertNotInstanceOf(SimpleStronglyTypedArrayCollectionTestAsset::class, $result);
    }

    public function testSetCanAddObjectsOfWantedType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $subject->set('test', new stdClass());
        self::assertCount(1, $subject);
    }

    public function testSetCannotAddNull(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->set('test', null);
    }

    public function testSetCannotAddObjectsOfScalarType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->set('test', 42);
    }
}
