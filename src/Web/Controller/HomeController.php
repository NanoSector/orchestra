<?php

declare(strict_types = 1);

namespace Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'web_home_index')]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}