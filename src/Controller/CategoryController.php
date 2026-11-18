<?php

namespace App\Controller;

use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    #[Route('/categories', name: 'app_categories')]
    public function showAllCategory(): Response
    {
        return $this->render('category/show_all_category.html.twig', [
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show', requirements: ['id' => '\d+'])]
    public function getCategoryById(int $id): Response
    {
        $category = $this->categoryService->getCategoryById($id);

        if ($category === null) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }

        return $this->render('category/show_category_id.html.twig', [
            'category' => $category,
        ]);
    }
}
