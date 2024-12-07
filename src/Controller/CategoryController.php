<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/add', name: 'app_category')]
    public function addCategory(CategoryRepository $categoryRepository,
                                Request $request,EntityManagerInterface $entityManager): Response
    {

        $categoryAdd = new Category();

        $form = $this->createForm(CategoryFormType::class,$categoryAdd);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($categoryAdd);
            $entityManager->flush();

            return $this->redirectToRoute('app_page_admin');
        }

        return $this->render('category/add.html.twig', [
            'controller_name' => 'Ajout d une categorie',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/edit/{id}', name: 'app_edit_category')]
    public function editCategory(?Category $category, CategoryRepository $categoryRepository,
                                 Request $request,EntityManagerInterface $entityManager): Response
    {
        if (!$category){
            $category = new Category();
        }

        $form = $this->createForm(CategoryFormType::class,$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_page_admin');
        }

        $editCategory = $categoryRepository->find($request->attributes->get('id'));

        return $this->render('category/edit.html.twig',[
            'editLayoutName' => 'Edition de la categorie',
            'form' => $form->createView(),
            'categoryEdit' => $editCategory,
        ]);

    }

    #[Route('/category/delete/{id}', name: 'app_delete_category')]
    public function deleteCategory(Category $category, CategoryRepository $categoryRepository,
                                   Request $request,EntityManagerInterface $entityManager): Response
    {
        $category = $categoryRepository->find($request->attributes->get('id'));
        $categoryDelete = $categoryRepository->remove($category,true);
        return $this->redirectToRoute('app_page_admin');
    }
}
