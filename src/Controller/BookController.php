<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BookService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapDateTime;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

	// Book List au format BookListDto
	#[Route('/books/list', name:'app_book_list')]
	public function listBook():Response
	{
		return $this->render('book/list_dto.html.twig', [
			'books' => $this->bookService->getBookListDtos()
		]);
	}

	// Book Detail au format BookDetailDto
	#[Route('/books/{id}', name:'app_book_detailID', requirements: ['id' => '\d+'])]
	public function detailBook(int $id):Response
	{
		$book = $this->bookService->getBookDetailDto($id);
		if ($book === null){
			throw $this->createNotFoundException('Livre introuvable');
		}

		return $this->render('book/detail_dto.html.twig', [
			'book' => $book,
		]);
	}

	#[IsGranted('ROLE_ADMIN', message:'Vous n\'etes pas autorisé')]
	#[Route('/books/new', name:'app_book_new')]
	public function newBook(Request $request, EntityManagerInterface $em):Response
	{
		$book = new Book();
		$form = $this->createForm(BookType::class, $book);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->bookService->saveBook($book);

			return $this->redirectToRoute('app_book_all');
		}
		return $this->render('book/book_form.html.twig', [
			'form' => $form
		]);
	}

	#[IsGranted('ROLE_ADMIN', message:"Nous n'etes pas autorisé")]
	#[Route('/books/{id}/edit', name:'app_book_edit', requirements: ['id' => '\d+'])]
	public function editBook(Request $request, int $id):Response
	{
		$book = $this->bookService->getBookById($id);
		if ($book === null){
			throw $this->createNotFoundException('Livre introuvable');
		}
		if (!$this->isGranted('ROLE_ADMIN')){
			throw $this->createAccessDeniedException("Nous n'êtes pas autorisé à modifier ce livre");
		}

		$form = $this->createForm(BookType::class, $book);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->bookService->saveBook($book);

			$this->addFlash('success', 'Modification de ' . $book->getTitle() . ' enregistrée');

			return $this->redirectToRoute('app_book_detailID', ['id' => $book->getId()]);
		}

		return $this->render('book/book_form.html.twig', [
			'form' => $form,
			'book' => $book
		]);
	}

}
