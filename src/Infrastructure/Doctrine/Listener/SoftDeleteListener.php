<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Infrastructure\Doctrine\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Orchestra\Infrastructure\Doctrine\Exception\EntityHardDeletedException;
use Orchestra\Infrastructure\Doctrine\Traits\SoftDeleteEntityTrait;

class SoftDeleteListener
{
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $classMetadata = $em->getClassMetadata(get_class($entity));

            $traits = $classMetadata->reflClass->getTraitNames();
            if (in_array(SoftDeleteEntityTrait::class, $traits)) {
                throw new EntityHardDeletedException($entity);
            }
        }
    }
}
