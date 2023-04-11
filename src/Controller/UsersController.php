<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Images;
use App\Entity\Users;
use App\Form\AnnoncesType;
use App\Services\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/users', name: 'users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    #[Route('/annonces/add', name: 'annonces_add')]
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $em,UserInterface $user, PictureService $pictureService): Response
    {
        $annonce = new Annonces;
        $form = $this->createForm(AnnoncesType::class,$annonce);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
             
            $images = $form->get('images')->getData();
          foreach ($images as $image ) {
            
            $folder = 'annonces';
            $fichier = $pictureService->add($image,$folder);
             $img = new Images;
             $img->setName($fichier);
             $annonce->addImage($img);

          }
               $slug= $slugger->slug($annonce->getTitle());
               $annonce->setUsers($user);
               $annonce->setSlug($slug);
               $annonce->setIsActive(false);
             
            $em->persist($annonce);
            $em->flush();
            $this->addFlash('success', 'l\'annonce  '.$annonce->getTitle().' est bien ajouté');
                        return $this->redirectToRoute('home_index');
        }

        return $this->render('users/annonces/add.html.twig',['add_annoncesform' => $form->createView()]);

        
    }
    #[Route('/annonces/update/{id}', name: 'annonces_update')]
    public function update(Request $request, SluggerInterface $slugger, EntityManagerInterface $em,UserInterface $user, Annonces $annonce, PictureService $pictureService): Response
    {
         
        $this->denyAccessUnlessGranted('annonce_edit',$annonce);
        $form = $this->createForm(AnnoncesType::class,$annonce);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image ) {
              
              $folder = 'annonces';
              $fichier = $pictureService->add($image,$folder);
               $img = new Images;
               $img->setName($fichier);
               $annonce->addImage($img);
  
            }
        
               $slug= $slugger->slug($annonce->getTitle());
               $annonce->setUsers($user);
               $annonce->setSlug($slug);
               $annonce->setIsActive(false);
             
            $em->persist($annonce);
            $em->flush();
            $this->addFlash('success', 'la categorie '.$annonce->getTitle().' est bien modifier');
                        return $this->redirectToRoute('app_profile');
        }

        return $this->render('users/annonces/update.html.twig',[
            'add_annoncesform' => $form->createView(),
            'annonce'=>$annonce
    ]);

        
    }
    #[Route('/annonces/delete/{id}', name: 'annonces_delete')]
    public function delate( EntityManagerInterface $em, Annonces $annonce)
    {

        // ManagerRegistry $doctrine:
        // $em = $doctrine->getManager();
        $images = $annonce->getImages();
        if ($images) {
            foreach ($images as $image) {
                $nomImage = $this->getParameter('images_directory') . '/annonces/' . $image->getName();

                // $nomImageMin = $this->getParameter('images_directory') . '/products/min/300x300-' . $image->getName();
                if (file_exists($nomImage)) {
                    unlink($nomImage);
                }
                // if (file_exists($nomImageMin)) {
                //     unlink($nomImageMin);
                // }
            }
        }
        
        $em->remove($annonce);
        $em->flush();
        $this->addFlash('success', 'l\'annonce '.$annonce->getTitle().' est bien suprimer');
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/annonces/images/delete/{id}', name: 'delate_image') ]
    public function delateImage( Images $image,
     EntityManagerInterface $em, Request $request)
    
     {
      
        $annonce = $image->getAnnonces();
        $nomImage = $this->getParameter('images_directory') . '/annonces/' . $image->getName();

        // $nomImageMin = $this->getParameter('images_directory') . '/products/min/300x300-' . $image->getName();
        if (file_exists($nomImage)) {
            unlink($nomImage);
        }
          
           $em->remove($image);
           $em->flush();
           $this->addFlash('success', 'l\'image  est bien suprimer');
           return $this->redirectToRoute('users_annonces_update',['id'=>$annonce->getId()]);

      }
       


    

    
}
