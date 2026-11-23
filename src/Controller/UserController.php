<?php

namespace App\Controller;

use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\UserService;

final class UserController extends AbstractController
{
	public function __construct(private UserService $userService)
	{
	}
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

	#[Route('/users', name: 'app_users')]
	public function showAllUser(): Response
	{
		return $this->render('user/show_all_user.html.twig', [
			'users' => $this->userService->getUsers(),
		]);
	}

	#[Route('/user/{id}', name: 'app_user_show', requirements:['id' => '\d+'])]
	public function getUserById(int $id): Response
	{
		$user = $this->userService->getUserById($id);

		if ($user === null){
			throw $this->createNotFoundException('Utilisateur introuvable');
		}
		return $this->render('user/show_user_id.html.twig', [
			'user' => $user,
		]);
	}

	#[IsGranted('ROLE_ADMIN', message : 'Vous \etes pas autorisé')]
	#[Route('/user/{id}/edit', name:'app_user_edit', requirements:['id' => '\d+'])]
	public function editUser(int $id, Request $request): Response
	{
		$user = $this->userService->getUserById($id);
		if ($user === null){
			throw $this->createNotFoundException('Utilisateur introuvable');
		}

		$form = $this->createForm(ProfileType::class, $user);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$this->userService->saveProfil($user);

			$this->addFlash('success', 'Modification de ' . $user->getFirstname() . " " . $user->getLastname() . " enregistrée");

			return $this->redirectToRoute('app_users');
		}

		return $this->render('user/edit.html.twig', [
			'form' => $form,
			'user' => $user,
		]);
	}
}
