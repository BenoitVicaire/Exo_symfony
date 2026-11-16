<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(Request $request): Response
    {
		$id = $request->query->get('id');
		if( $id !== null){
			if (!preg_match('/^\d+$/', $id)){
				return $this->render ('product/index.html.twig', ['error' => 'Id invalide']);
			}
			return $this->redirectToRoute('app_product_show', ['id' => $id]);
		}

        return $this->render('product/index.html.twig', [
            
        ]);
    }

	#[Route('/product/{id}', 
		name: 'app_product_show', 
		requirements: ['id' => '\d+']
	)]
	public function show(int $id): Response
	{
		return $this->render('product/show.html.twig', [
			'id' => $id
		]);
	}
}
