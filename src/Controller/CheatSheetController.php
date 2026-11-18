<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheatSheetController extends AbstractController
{
    #[Route('/cheatsheet', name: 'app_cheatsheet')]
    public function index(): Response
    {
        return $this->render('cheatsheet/index.html.twig');
    }

    #[Route('/cheatsheet/mise-en-place', name: 'app_cheatsheet_setup')]
    public function setup(): Response
    {
        return $this->render('cheatsheet/setup.html.twig');
    }

    #[Route('/cheatsheet/controller', name: 'app_cheatsheet_controller')]
    public function controller(): Response
    {
        return $this->render('cheatsheet/controller.html.twig');
    }

    #[Route('/cheatsheet/entity', name: 'app_cheatsheet_entity')]
    public function entity(): Response
    {
        return $this->render('cheatsheet/entity.html.twig');
    }

    #[Route('/cheatsheet/tailwind', name: 'app_cheatsheet_tailwind')]
    public function tailwind(): Response
    {
        return $this->render('cheatsheet/tailwind.html.twig');
    }
}
