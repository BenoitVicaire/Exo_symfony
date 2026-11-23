<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BookService;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Security\Voter\BookVoter;

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

	
	#[Route('/books/new', name:'app_book_new')]
	public function newBook(Request $request):Response
	{
		$book = new Book();

		$this->denyAccessUnlessGranted(BookVoter::CREATE, $book, "Vous n'êtes pas autorisé à créer un livre");

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

	#[Route('/books/{id}/edit', name:'app_book_edit', requirements: ['id' => '\d+'])]
	public function editBook(Request $request, int $id):Response
	{
		$book = $this->bookService->getBookById($id);
		if ($book === null){
			throw $this->createNotFoundException('Livre introuvable');
		}
		$this->denyAccessUnlessGranted(BookVoter::EDIT, $book, "Vous n'êtes pas autorisé à modifier ce livre");

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

	#[Route('/books/{id}/delete', name:'app_book_delete',methods:['POST'], requirements: ['id' => '\d+'] )]
	public function deleteBook(int $id, Request $request):Response
	{
		$book = $this->bookService->getBookById($id);
		if ($book === null) {
			throw $this->createNotFoundException('Livre non trouvé');
		}
		$this->denyAccessUnlessGranted(BookVoter::DELETE, $book, "Vous n'êtes pas autorisé à supprimer ce livre");

		if(!$this->isCsrfTokenValid('delete-book-' . $book->getId(), $request->request->get('_token'))){
			throw $this->createAccessDeniedException('Token invalid');
		}

		$this->bookService->deleteBook($book);

		$this->addFlash('success', 'Livre ' . $book->getTitle() . ' supprimé');

		return $this->redirectToRoute('app_book_all');

	}

}
