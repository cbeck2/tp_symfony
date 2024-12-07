<?php

namespace App\Controller;

use App\Repository\AdminUserRepository;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advert;
use App\Entity\Category;
use App\Form\HomeUserConnectedFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(?Category $category,AdvertRepository $advertRepository,CategoryRepository $categoryRepository,Request $request,EntityManagerInterface $entityManagerInterface): Response
    {

        $idCategory = $request->request->get('category');
        if ($idCategory) {
            return $this->redirectToRoute('app_show_advert_byCategory',[
                'id' => $idCategory
            ]);

        }
        
        $advertGeneral = $advertRepository->findAll();
        $categoryGeneral = $categoryRepository->findAll();
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'LEBONANGLE',
            'allAdvert' => $advertGeneral,
            'allCategory' => $categoryGeneral,
        ]);
    }

    #[Route('/show/advert/{id}', name: 'app_show_advert')]
    public function showAdvert(AdvertRepository $advertRepository,Request $request) : Response{
        
        $advertShow = $advertRepository->find($request->attributes->get('id'));
        return $this->render('home/show.html.twig',[
            'showLayoutName' => 'Affichage',
            'advert' => $advertShow,
        ]);
    }


    #[Route('/show/advert/byCategorie/{id}', name: 'app_show_advert_byCategory')]
    public function showAdvertByCategory(CategoryRepository $categoryRepository,AdvertRepository $advertRepository,Request $request) : Response{
        
       
        $advertByCategoryShow = $advertRepository->findBy(
            ['category'=>$request->attributes->get('id')]
        );
        
        return $this->render('home/cate.html.twig',[
            'showLayoutName' => 'Affichage',
            'advertByCategory' => $advertByCategoryShow,
        ]);
    }
}
