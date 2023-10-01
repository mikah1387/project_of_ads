<?php

namespace App\Controller\admin;

use App\Entity\Categories;
use App\Entity\Regions;
use App\Form\CategoriesType;
use App\Form\RegionsType;
use App\Repository\CategoriesRepository;
use App\Repository\RegionsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/regions', name: 'admin_regions_')]

class RegionsController extends AbstractController
{
#[Route('/', name: 'index')]

    public function index(RegionsRepository $regionsRepository): Response
    {

        $regions=$regionsRepository->findAll();
        return $this->render('admin/regions/index.html.twig', [
            'regions' => $regions,
        ]);
    }
    #[Route('/add', name: 'add')]

    public function add( Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $region = new Regions;
        $form = $this->createForm(RegionsType::class,$region);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
               
            $em->persist($region);
            $em->flush();
            $this->addFlash('success', 'la region '.$region->getName().' est bien ajouté');
                        return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/regions/add.html.twig',['add_regionform' => $form->createView()]);

       
    }
}
