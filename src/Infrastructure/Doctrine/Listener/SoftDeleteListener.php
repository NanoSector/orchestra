<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Infrastructure\Doctrine\Exception\EntityHardDeletedException;
use Infrastructure\Doctrine\Traits\SoftDeleteEntityTrait;

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