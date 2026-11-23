<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
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

	#[IsGranted('ROLE_USER' , message : 'Vous\etes pas autorisé')]
    #[Route('/category/new', name: 'app_category_new')]
    public function newCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->saveCategory($category);

			$this->addFlash('success', 'Catégorie ' . $category->getName() . ' enregistrée');

            return $this->redirectToRoute('app_categories');
        }

        return $this->render('category/category_form.html.twig', [
            'form' => $form,
        ]);
    }

	#[IsGranted('ROLE_USER', message : 'Vous \etes pas autorisé')]
	#[Route('/category/{id}/edit', name :'app_category_edit', requirements: ['id' => '\d+']) ]
	public function editCategory(Request $request, int $id):Response
	{
		$category = $this->categoryService->getCategoryById($id);
		if ($category === null) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }
		

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$this->categoryService->saveCategory($category);

			$this->addFlash('success', 'Modification de ' . $category->getName() . ' enregistrée');

            return $this->redirectToRoute('app_categories');
        }

		return $this->render('category/category_form.html.twig', [
			'form' => $form,
			'category' => $category
		]);
	}
}
