<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Infrastructure\Doctrine\Traits\SoftDeleteEntityTrait;

class SoftDeleteFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $traits = $targetEntity->reflClass->getTraitNames();
        if (!in_array(SoftDeleteEntityTrait::class, $traits)) {
            return '';
        }

        // TODO Support beyond PostgreSQL
        return $targetTableAlias . '.deleted = false';
    }
}