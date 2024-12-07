<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Form\AdminUserFormType;
use App\Form\LoginFormType;
use App\Repository\AdminUserRepository;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use App\Service\AdvertWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AdminUser;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Component\Workflow\Registry;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_page_admin')]
    public function index(AdminUserRepository $adminUserRepository,
                          CategoryRepository $categoryRepository,
                          AdvertRepository $advertRepository,
                          PictureRepository $pictureRepository,
                          Request $request,
                          EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $adminUser = $adminUserRepository->findAll();
        $category = $categoryRepository->findAll();
        $advert = $advertRepository->findAll();
        $picture = $pictureRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Interface administrateur',
            'allAdmin' => $adminUser,
            'allCategory' => $category,
            'allAdvert' => $advert,
            'picture' => $picture,
            'user' => $user,
        ]);
    }

    #[Route('/admin/edit/{id}',name: 'app_edit')]
    public function editUser(?AdminUser $adminUser,UserPasswordHasherInterface $passwordHasher,AdminUserRepository $adminUserRepository,Request $request,EntityManagerInterface $entityManager) : Response{

        if (!$adminUser){
            $adminUser = new AdminUser();
        }

        $form = $this->createForm(AdminUserFormType::class,$adminUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $plainPassword = $form->get('plainpassword');
            $hashedPassword = $passwordHasher->hashPassword(
                $adminUser,
                $adminUser->getPlainPassword()
            );
            $adminUser->setPassword($hashedPassword);
            $entityManager->persist($adminUser);
            $entityManager->flush();
            return $this->redirectToRoute('app_page_admin');
        }

        $adminUserEdit = $adminUserRepository->find($request->attributes->get('id'));

        return $this->render('registration/edit.html.twig',[
            'editLayoutName' => 'Edition',
            'form' => $form->createView(),
            'userEdit' => $adminUserEdit
        ]);

    }

    #[Route('/admin/delete/{id}',name: 'app_delete')]
    public function deleteUser(AdminUserRepository $adminUserRepository,Request $request,EntityManagerInterface $entityManager) : Response{

        $adminUser = $adminUserRepository->find($request->attributes->get('id'));
        $editUser = $adminUserRepository->remove($adminUser,true);
        return $this->redirectToRoute('app_page_admin');
    }

    #[Route('/admin/validation/publish/{id}',name: 'app_validation_publish')]
    public function publishAdvert(AdvertRepository $advertRepository,Registry $registry,Request $request,EntityManagerInterface $entityManager) : Response{
        AdvertWorkflow::Publish($request->attributes->get('id'),$advertRepository,$registry);
        return $this->redirectToRoute('app_page_admin');
    }

    #[Route('/admin/validation/reject/{id}',name: 'app_validation_reject')]
    public function rejectAdvert(AdvertRepository $advertRepository,Registry $registry,Request $request,EntityManagerInterface $entityManager) : Response{
        AdvertWorkflow::Rejected($request->attributes->get('id'),$advertRepository,$registry);
        return $this->redirectToRoute('app_page_admin');
    }
}
