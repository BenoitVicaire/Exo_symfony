<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
	private const BOOKS = [
			['id' => 1, 'title' => 'Dune', 'author' => 'Frank Herbert'],
			['id' => 2, 'title' => '1984', 'author' => 'George Orwell'],
			['id' => 3, 'title' => 'Fondation', 'author' => 'Isaac Asimov'],
		];

    #[Route('/livres', name: 'app_book')]
    public function index(Request $request): Response
    {
		$book_id = $request->query->get("book_id");	
		if( $book_id !== null){
			if (!preg_match('/^\d+$/', $book_id)){
				return $this->render ('book/index.html.twig', [
					'error' => 'Id invalide',
					'books '=>self::BOOKS]);
			}
			return $this->redirectToRoute('app_book_detail', ['id' => $book_id ]);
		}
        return $this->render('book/index.html.twig', [
            'books' => self::BOOKS,
        ]);
    }

	#[Route('/livres/detail/{id}', name: 'app_book_detail', requirements:['id' => '\d+'])]
	public function detail(int $id):Response
	{
		foreach (self::BOOKS as $book){
			if ($book['id'] === $id){
				return $this->render('book/detail.html.twig', ['book' => $book]);
			}
		}
		throw $this->createNotFoundException('Libre introuvable');
	}
}
