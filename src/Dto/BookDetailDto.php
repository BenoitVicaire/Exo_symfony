<?php

namespace App\Dto;
use App\Entity\Book;

final readonly class BookDetailDto
{
	public function __construct(
		public int $id,
		public string $title,
		public string $resume,
		public string $author,
		public \DateTimeInterface $publishAt,
		/** @var CategoryDto[] */
		public array $categories,
		public ?string $userEmail,
	)
	{
	}

	public static function fromEntity(Book $book): self{
		return new self(
			$book->getId(),
			$book->getTitle(),
			$book->getResume(),
			$book->getAuthor(),
			$book->getPublishAt(),
			$book->getCategories()->map(CategoryDto::fromEntity(...))->toArray(),
			$book->getUser()?->getEmail(),
		);

		
	}
}