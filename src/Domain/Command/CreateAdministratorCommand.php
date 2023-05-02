<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Command;

use Orchestra\Domain\Entity\User;
use Orchestra\Domain\Enumeration\Role;
use Orchestra\Domain\Repository\UserRepository;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand('app:create-administrator', 'Create an administrative user')]
final class CreateAdministratorCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');

        $usernameQuestion = new Question('What should the username for this user be? ');
        $usernameQuestion->setValidator(function (string $username) {
            $user = $this->userRepository->findOneBy([
                'username' => $username,
            ]);

            if ($user !== null) {
                throw new RuntimeException('This username is already taken');
            }

            return $username;
        });

        $username = $questionHelper->ask($input, $output, $usernameQuestion);

        $emailQuestion = new Question('What should the e-mail address for this user be? ');
        $emailQuestion->setValidator(function (string $email) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            if ($email === false) {
                throw new RuntimeException('Invalid e-mail address');
            }

            $user = $this->userRepository->findOneBy([
                'email' => $email,
            ]);

            if ($user !== null) {
                throw new RuntimeException('This e-mail address is already taken');
            }

            return $email;
        });

        $email = $questionHelper->ask($input, $output, $emailQuestion);

        $passwordQuestion = new Question('What should the password be? ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $plainTextPassword = $questionHelper->ask($input, $output, $passwordQuestion);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $plainTextPassword
            )
        );
        $user->addFormRole(Role::ROLE_ADMIN);

        $validationResult = $this->validator->validate($user);

        if ($validationResult->count() > 0) {
            $output->writeln('<error>The user did not pass validation.</error>');

            return self::FAILURE;
        }

        $this->userRepository->save($user, true);

        $output->writeln('<info>The user has been created.</info>');

        return self::SUCCESS;
    }
}
