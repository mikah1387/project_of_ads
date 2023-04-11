<?php

namespace App\Controller\admin;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/annonces', name: 'admin_annonces_')]

class AnnoncesController extends AbstractController
{
    #[Route('/', name: 'index')]

    public function index(AnnoncesRepository $annoncesRepository): Response
    {

        $annonces = $annoncesRepository->findAll();
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/active/{id}', name: 'active')]

    public function active(Annonces $annonce,EntityManagerInterface $em)
    {

        $annonce->setIsActive( ($annonce->isIsActive())?false:true);
        $em->persist($annonce);
        $em->flush();
        $this->addFlash('success', 'l\'annonce '.$annonce->getTitle().' est bien activée');
       
        return  $this->redirectToRoute('admin_annonces_index');
       
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delate( EntityManagerInterface $em, Annonces $annonce)
    {

        // ManagerRegistry $doctrine:
        // $em = $doctrine->getManager();
        
        $em->remove($annonce);
        $em->flush();
        $this->addFlash('success', 'la categorie '.$annonce->getTitle().' est bien suprimer');
        return $this->redirectToRoute('admin_annonces_index');
    }
   
}
