<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Controller;

use Orchestra\Domain\Entity\User;
use Orchestra\Domain\Enumeration\Role;
use Orchestra\Domain\Repository\GroupRepositoryInterface;
use Orchestra\Domain\Repository\UserRepositoryInterface;
use Orchestra\Infrastructure\Controller\AppContext;
use Orchestra\Web\Breadcrumb\Breadcrumb;
use Orchestra\Web\Form\UserForm;
use Orchestra\Web\Helper\Flash;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AppContext('user_management')]
#[Breadcrumb('Users & Groups', 'web_user_index')]
#[IsGranted(Role::ROLE_ADMIN->value)]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/users/create', name: 'web_user_create', methods: ["GET", "POST"])]
    #[Breadcrumb('Create user')]
    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $form->get('password')->getData();
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                )
            );

            $this->userRepository->save($user, true);

            $this->addFlash(Flash::OK, 'The user has been created.');

            return $this->redirectToRoute('web_user_update', ['id' => $user->getId()]);
        }

        return $this->render('users/create.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'web_user_delete', methods: ["POST"])]
    public function delete(User $user): Response
    {
        $this->userRepository->delete($user);

        return $this->redirectToRoute('web_user_index');
    }

    #[Route('/users', name: 'web_user_index', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'groups' => $this->groupRepository->findAll(),
            'users'  => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}', name: 'web_user_update', methods: ["GET", "POST"])]
    #[Breadcrumb('Update user')]
    public function update(User $user, Request $request): Response
    {
        $form = $this->createForm(UserForm::class, $user, [
            'require_password' => false,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $form->get('password')->getData();

            if ($plaintextPassword !== null) {
                // Password update requested
                $user->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        $plaintextPassword
                    )
                );
            }

            $this->userRepository->save($user, true);

            $this->addFlash(Flash::OK, 'The user has been updated.');

            return $this->redirectToRoute('web_user_update', ['id' => $user->getId()]);
        }

        return $this->render('users/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
