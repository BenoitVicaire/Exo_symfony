<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Entity\Book;
use Doctrine\DBAL\Types\DateImmutableType;

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
	/**
	 * @return Book[]
	 */
	public function getBookPublishedAfterDate(\DateTimeInterface $date): array
	{
		return $this->book_repository->findPublishedAfter($date);
	}

	/**
	 * @return Book[]
	 */
	public function getBookByCategories(string $categoryName): array
	{
		return $this->book_repository->findByCategory($categoryName);
	}
}