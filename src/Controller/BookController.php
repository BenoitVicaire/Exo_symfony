<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BookService;
use DateTimeImmutable;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapDateTime;

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

	// Book publishAt after set Date
	#[Route('/books/search_published_after', name: 'app_book_search_published_after')]
	public function searchBookPublishedAfterDate(#[MapQueryParameter] string $date): Response
	{
		$parsed = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
		if ($parsed === false){
			throw $this->createNotFoundException('Date invalide');
		}

		return $this->render('book/index.html.twig', [
			'books' => $this->bookService->getBookPublishedAfterDate($parsed)
		]);
	}

	// Book by categories

	#[Route('/books/search_by_categories', name:'app_book_search_by_categories')]
	public function searchBookByCategories(#[MapQueryParameter] string $categoryName): Response
	{
		return $this->render('book/index.html.twig', [
			'books' => $this->bookService->getBookByCategories($categoryName)
		]);
	}
}
