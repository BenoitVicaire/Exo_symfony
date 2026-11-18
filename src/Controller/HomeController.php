<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FormationService;

class HomeController extends AbstractController 
{
	public function __construct(private FormationService $formation_service)
	{
	}
    public function index(): Response
    {
		$formations = $this->formation_service->getFormations();
        return $this->render('home/index.html.twig', [
			'formations' => $formations
		]);
    }
}
