<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

namespace App\Tests\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Infrastructure\Collection\AbstractStronglyTypedArrayCollection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AbstractStronglyTypedArrayCollectionTest extends TestCase
{

    public function testConstructCanAddObjectsOfWantedType(): void
    {
        $elements = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];

        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);

        self::assertCount(5, $subject);
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

    public function testConstructCannotAddNullValues(): void
    {
        $elements = [
            new \stdClass(),
            null,
            null,
        ];

        $this->expectException(InvalidArgumentException::class);
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);
    }

    public function testAddCanAddObjectsOfWantedType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $subject->add(new \stdClass());
        self::assertCount(1, $subject);
    }

    public function testAddCannotAddObjectsOfScalarType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->add(42);
    }

    public function testAddCannotAddNull(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->add(null);
    }

    public function testSetCanAddObjectsOfWantedType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $subject->set('test', new \stdClass());
        self::assertCount(1, $subject);
    }

    public function testSetCannotAddObjectsOfScalarType(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->set('test', 42);
    }

    public function testSetCannotAddNull(): void
    {
        $subject = new SimpleStronglyTypedArrayCollectionTestAsset();

        $this->expectException(InvalidArgumentException::class);
        $subject->set('test', null);
    }

    public function testMapAlwaysReturnsArrayCollection(): void
    {
        $elements = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];

        $subject = new SimpleStronglyTypedArrayCollectionTestAsset($elements);

        $result = $subject->map(static fn(\stdClass $e) => $e);

        self::assertNotInstanceOf(AbstractStronglyTypedArrayCollection::class, $result);
        self::assertNotInstanceOf(SimpleStronglyTypedArrayCollectionTestAsset::class, $result);
    }
}
