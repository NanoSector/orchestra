<?php

declare(strict_types=1);

namespace Web\Controller;

use Domain\Entity\Group;
use Domain\Repository\GroupRepository;
use Infrastructure\Breadcrumbs\Breadcrumb;
use Infrastructure\Controller\AppContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Web\Form\GroupForm;
use Web\Helper\Flash;

#[AppContext('user_management')]
#[Breadcrumb('Users', 'web_user_index')]
#[Breadcrumb('Groups', 'web_group_index')]
class GroupController extends AbstractController
{
    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    #[Route('/groups', name: 'web_group_index')]
    public function index(): Response
    {
        return $this->render('groups/index.html.twig', [
            'groups' => $this->groupRepository->findAll()
        ]);
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

    #[Route('/groups/{id}', name: 'web_group_update', methods: ["GET", "POST"])]
    #[Breadcrumb('Update group')]
    public function update(Group $group, Request $request): Response
    {
        $form = $this->createForm(GroupForm::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupRepository->save($group, true);

            $this->addFlash('success', 'The group has been updated.');

            return $this->redirectToRoute('web_group_update', ['id' => $group->getId()]);
        }

        return $this->render('groups/update.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/groups/{id}/delete', name: 'web_group_delete', methods: ["POST"])]
    public function delete(Group $group): Response
    {
        $this->groupRepository->remove($group, true);

        $this->addFlash('success', 'The group has been deleted.');

        return $this->redirectToRoute('web_group_index');
    }
}
