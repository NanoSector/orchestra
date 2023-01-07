<?php

declare(strict_types=1);

namespace Web\Controller;

use Domain\Entity\User;
use Domain\Repository\GroupRepository;
use Domain\Repository\UserRepository;
use Infrastructure\Breadcrumbs\Breadcrumb;
use Infrastructure\Controller\AppContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Web\Form\UserForm;
use Web\Helper\Flash;

#[AppContext('user_management')]
#[Breadcrumb('Users', 'web_user_index')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private GroupRepository $groupRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, GroupRepository $groupRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/users', name: 'web_user_index', methods: ["GET"])]
    public function index(Request $request): Response
    {
        return $this->render('users/index.html.twig', [
            'groups' => $this->groupRepository->findAll(),
            'users' => $this->userRepository->findAll(),
        ]);
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

            $this->addFlash('success', 'The user has been updated.');

            return $this->redirectToRoute('web_user_update', ['id' => $user->getId()]);
        }

        return $this->render('users/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'web_user_delete', methods: ["POST"])]
    public function delete(User $user): Response
    {
        $this->userRepository->remove($user, true);

        return $this->redirectToRoute('web_user_index');
    }
}