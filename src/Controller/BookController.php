<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BookService;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

final class BookController extends AbstractController
{
	public function __construct(private BookService $bookService) {}

	// All book
	#[Route('/books', name: 'app_book_all')]
	public function showAllBook(): Response
	{
		return $this->render('book/index.html.twig', [
			'books' => $this->bookService->getBooks(),
		]);
	}

	// Book by ID
	#[Route('/livres/detail/{id}', name: 'app_book_detail', requirements: ['id' => '\d+'])]
	public function detail(int $id): Response
	{
		$book = $this->bookService->getBookById($id);
		if ($book === null) {
			throw $this->createNotFoundException('Livre introuvable');
		}

		return $this->render('book/detail.html.twig', ['book' => $book]);
	}

	// Book by title
	#[Route('/books/search', name: 'app_book_search')]
	public function searchBook(#[MapQueryParameter] string $title= ''): Response 
	{
		return $this->render('book/index.html.twig', [
			'books' => $this->bookService->getBookByTitle($title)
		]);
	}
}
