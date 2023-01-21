<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Web\Controller;

use Domain\Entity\Group;
use Domain\Repository\GroupRepository;
use Infrastructure\Controller\AppContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Web\Breadcrumb\Breadcrumb;
use Web\Breadcrumb\BreadcrumbBuilder;
use Web\Form\GroupForm;
use Web\Helper\BreadcrumbHelper;
use Web\Helper\Flash;

#[AppContext('user_management')]
#[Breadcrumb('Users & Groups', 'web_user_index')]
class GroupController extends AbstractController
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly BreadcrumbBuilder $breadcrumbBuilder
    ) {
    }

    #[Route('/groups/create', name: 'web_group_create', methods: ["GET", "POST"])]
    #[Breadcrumb('Create group')]
    public function create(Request $request): Response
    {
        $group = new Group();

        $form = $this->createForm(GroupForm::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepository->save($group, true);

            $this->addFlash(Flash::OK, 'The group has been created.');

            return $this->redirectToRoute('web_group_update', ['id' => $group->getId()]);
        }

        return $this->render('groups/create.html.twig', [
            'user' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/groups/{id}/delete', name: 'web_group_delete', methods: ["POST"])]
    public function delete(Group $group): Response
    {
        $this->groupRepository->remove($group, true);

        $this->addFlash('success', 'The group has been deleted.');

        return $this->redirectToRoute('web_user_index');
    }

    #[Route('/groups/{id}', name: 'web_group_update', methods: ["GET", "POST"])]
    public function update(Group $group, Request $request): Response
    {
        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->text(sprintf('Group %s', $group->getName())),
            'current'     => $this->breadcrumbBuilder->text('Update group', true),
        ]);

        $form = $this->createForm(GroupForm::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepository->save($group, true);

            $this->addFlash('success', 'The group has been updated.');

            return $this->redirectToRoute('web_group_update', ['id' => $group->getId()]);
        }

        return $this->render('groups/update.html.twig', [
            'group' => $group,
            'form'  => $form,
        ]);
    }
}
