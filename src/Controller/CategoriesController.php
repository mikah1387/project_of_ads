<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/categories', name: 'categories_')]

class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function index(Categories $categorie, AnnoncesRepository $annoncesRepository): Response
    {
         
        $annonces = $annoncesRepository->findBy(['categories'=>$categorie->getId(),
                                                  'is_active'=>true],['created_at'=>'asc']);
        
        return $this->render('categories/index.html.twig', [
            'annonces' => $annonces
        ]);
    }
}
