<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Repository;

use Orchestra\Domain\Entity\User;

interface UserRepositoryInterface
{
    /**
     * Deletes the entity from the repository, if it exists.
     */
    public function delete(User $entity): void;

    /**
     * Finds a single user by their e-mail address.
     * Returns null when the user is not found.
     */
    public function findOneByEmail(string $email): ?User;

    /**
     * Finds a single user by their username.
     * Returns null when the user is not found.
     */
    public function findOneByUsername(string $username): ?User;

    /**
     * Saves the entity to the repository, either creating or updating it as necessary.
     */
    public function save(User $entity): void;
}
