<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Controller;

use Orchestra\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'web_login_index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('web_home_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username'   => $lastUsername,
            'error'           => $error,
        ]);
    }

    #[Route('/logout', name: 'web_login_logout', methods: ['GET'])]
    public function logout(): never
    {
        // As per https://symfony.com/doc/current/security.html#logging-out this should never be called;
        // pretend it does not exist.
        throw $this->createNotFoundException();
    }
}
