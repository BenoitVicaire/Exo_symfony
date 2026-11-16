<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {
		$name = $request->query->get('name');
		if ($name !== null){
			if (!preg_match('/^[a-zA-Z]+$/', $name)) {
            	return $this->render('user/index.html.twig', ['error' => 'Nom invalide']);
        	}

			return $this->redirectToRoute('profil', ['name' => $name ]);
		}

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
	#[Route('/profil/{name}', name: 'profil')]
	public function profile(string $name): Response
	{
		$age = 32;
		$city = "Toulouse";

		return $this->render('user/profile.html.twig', [
			'name' => $name,
			'age' => $age,
			'city' => $city,
		]);
	}
}
