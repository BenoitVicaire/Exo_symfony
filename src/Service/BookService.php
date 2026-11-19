<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Entity\Book;

class BookService
{
	public function __construct(private BookRepository $book_repository)
	{
	}

	/**
	 * @return Book[]
	 */
	public function getBooks():array
	{
		return $this->book_repository->findAll();
	}

	public function getBookById(int $id): ?Book
	{
		return $this->book_repository->find($id);
	}

	/**
	 * @return Book[]
	 */
	public function getBookByTitle(string $title): array
	{
		return $this->book_repository->findByTitle($title);
	}

}