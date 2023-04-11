<?php

namespace App\Controller\admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'admin_categories_')]

class CategoriesController extends AbstractController
{
#[Route('/', name: 'index')]

    public function index(CategoriesRepository $categoriesRepository): Response
    {

        $categories=$categoriesRepository->findAll();
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/add', name: 'add')]

    public function add( Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $categorie = new Categories;
        $form = $this->createForm(CategoriesType::class,$categorie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
             
        
             $slug= $slugger->slug($categorie->getName());
               $categorie->setSlug($slug);
             
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'la categorie '.$categorie->getName().' est bien ajouté');
                        return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/add.html.twig',['add_categorieform' => $form->createView()]);

       
    }
}
