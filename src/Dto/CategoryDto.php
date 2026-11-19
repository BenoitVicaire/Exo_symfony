<?php

namespace App\Dto;
use App\Entity\Category;

final readonly class CategoryDto{
	public function __construct(
		public int $id,
		public string $name,
	)
	{
	}

	public static function fromEntity(Category $category): self
	{
		return new self($category->getId(), $category->getName());
	}
}