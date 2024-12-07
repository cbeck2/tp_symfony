<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Advert;
use App\Form\AdvertFormType;
use App\Repository\AdminUserRepository;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends AbstractController
{
    #[Route('/advert/add', name: 'app_advert')]
    public function addAdvert(AdminUserRepository $adminUserRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $advert = new Advert();
        
        $form = $this->createForm(AdvertFormType::class,$advert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $advert->setState('draft');
            $advert->setCreatedAt(new \DateTime());
            $entityManager->persist($advert);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        $confirmation_link = $this->renderView(
            'mail/mail.html.twig', ['advertID' => $advert->getID()]
        );
    
        $confirmation_linkReject = $this->renderView(
            'mail/rejected.html.twig', ['advertID' => $advert->getID()]
        );

        $adminUser = $adminUserRepository->findAll();
        
        for ($i = 0; $i < count($adminUser); ++$i) {

            $mailAdmin = new Mail();
            $mailAdmin->send($adminUser[$i]->getEmail(), 
            $advert->getAuthor(), 
            'Annonce Creer', 
            'Annonce : '.$advert->getTitle(), 
            'Cette annonces est en attente de validation',
            "",
            '',
            "Veuillez la valider"
            );
        }
      
        $mail = new Mail();
            $mail->send($advert->getEmail(), 
            $advert->getAuthor(), 
            'Annonce en cours de publication', 
            'Votre annonce : '.$advert->getTitle(), 
            "Votre annonces est en attente de validation",
            "",
            '',
            "Merci de votre fideliter"
    );

    return $this->render('advert/add.html.twig', [
        'controller_name' => 'AdvertController',
        'form' => $form->createView(),
    ]);

    }
    
    #[Route('/advert/delete/{id}', name: 'app_delete_advert')]
    public function deleteCategory(?Advert $advert,AdvertRepository $advertRepository,
                                   Request $request,EntityManagerInterface $entityManager): Response
    {
        $advert = $advertRepository->find($request->attributes->get('id'));
        $advertDelete = $advertRepository->remove($advert,true);
        return $this->redirectToRoute('app_page_admin');
    }
}
