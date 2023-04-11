<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Comments;
use App\Form\AnnonceContactType;
use App\Form\CommentsType;
use App\Form\SearchAnnonceType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\CommentsRepository;
use App\Services\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonces', name: 'annonces_')]

class AnnoncesController extends AbstractController
{
    #[Route('/details/{slug}', name: 'detail')]
    public function index(Annonces $annonce, Request $request,SendMailService $mailer, EntityManagerInterface $em , CommentsRepository $commentRepo ): Response
    {
         
      if (!$annonce){

        throw new NotFoundHttpException('pas d\'annonce trouvée');
      }
      
      $form = $this->createForm(AnnonceContactType::class);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          
            $e_mail = $form->get('email')->getData();
            $message = $form->get('message')->getData();
            $mailer->sendMail(
              $e_mail,
              $annonce->getUsers()->getEmail(),
              'Contact au sujet de votre annonce "'.$annonce->getTitle().'"',
              'emails/contact_ven.html.twig',
               [
                'annonce' => $annonce,
                'e_mail' =>$e_mail,
                'message' => $message,
               ]

               );
            $this->addFlash('success', 'votre message a bien été  envoyé(e) au vendeur');
            return $this->redirectToRoute('annonces_detail',['slug'=>$annonce->getSlug()]);
        
       }

        $comment = new Comments;
       $formcomment = $this->createForm(CommentsType::class,$comment);
       $formcomment->handleRequest($request);

       if ($formcomment->isSubmitted() && $formcomment->isValid()) {
            
          $parentId = $formcomment->get('parentid')->getData();
          if ($parentId){
            $parent = $commentRepo->find($parentId);
            $comment->setParent($parent);
          }
          
          $comment-> setAnnonces($annonce);
          // dd($comment);
           $em->persist($comment);
           $em->flush();
           $this->addFlash('success', 'votre commentaire est bien envoyé');
           return $this->redirectToRoute('annonces_detail',['slug'=>$annonce->getSlug()]);
       }
         
      //  $commentaires = $commentRepo->findBy(['annonces'=>$annonce]); 
        return $this->render('annonces/index.html.twig', [
            'annonce' => $annonce,
            'formcontact'=> $form->createView(),
            'formcomment'=> $formcomment->createView()

        ]);
    }

    #[Route('/favoris/add/{id}', name: 'add_favori')]
    public function addfavori(Annonces $annonce, EntityManagerInterface $em ):Response
    {
         
      if (!$annonce){

        throw new NotFoundHttpException('pas d\'annonce trouvée');
      }
     
       $annonce->addFavori($this->getUser());
       $em->persist($annonce);
       $em->flush();

       $this->addFlash('success', 'vous avez ajouté(e) l\'annonce '.$annonce->getTitle() .' a vos favoris');
       return $this->redirectToRoute('categories_list',['slug'=>$annonce->getCategories()->getSlug()]);
      
    }

    #[Route('/favoris/remove/{id}', name: 'remove_favori')]
    public function removefavori(Annonces $annonce, EntityManagerInterface $em ):Response
    {
         
      if (!$annonce){

        throw new NotFoundHttpException('pas d\'annonce trouvée');
        }
     
       $annonce->removeFavori($this->getUser());
       $em->persist($annonce);
       $em->flush();

       $this->addFlash('alert', 'vous avez retiré(e) l\'annonce '.$annonce->getTitle() .' de vos favoris');
       return $this->redirectToRoute('categories_list',['slug'=>$annonce->getCategories()->getSlug()]);
      
    }

    #[Route('/All', name: 'all')]
    public function allAnnonces( Request $request, AnnoncesRepository $annoncesRepository, CategoriesRepository $catyrepo): Response
    {         
             $limit = 3;
             $page = $request->query->getInt('page',1);
             
             $filters = $request->get('categories');
              
            $annonces = $annoncesRepository->getPaginatedAnnonces($page,$limit,$filters);
            $totalan = $annoncesRepository->countPages($filters);
          
            $categories = $catyrepo->findcatygorieparent(); 

            if ($request->get('ajax')){

              return new JsonResponse([

                'content'=>$this->renderView('annonces/_content_annonces.html.twig', [
                  'annonces' => $annonces, 
                   'limit'=>$limit,
                   'page'=>$page,
                   'totalan'=>$totalan
                  
              ])
              ]);
            }

            return $this->render('annonces/allannonces.html.twig', [
              'annonces' => $annonces,
              'categories'=>$categories,
           
               'limit'=>$limit,
               'page'=>$page,
               'totalan'=>$totalan
              
          ]);
       
    }

   

    #[Route('/search', name:'search')]
    public function searchAnnonces( Request $request, AnnoncesRepository $annonceRepo): Response
    { 

         $annonces = $annonceRepo->findBy(['is_active'=>true],[ 'created_at'=>'asc']);

            $form = $this->createForm(SearchAnnonceType::class);
            $form->handleRequest($request);
    
            if( $form->isSubmitted() && $form->isValid()){
               // on recherche les annonces correspondant aux mots cles
              //  dd($form->get('categories')->getData()->getName());
             $annonces = $annonceRepo->search($form->get('mots')->getData(),$form->get('categories')->getData());
             
            }

            return $this->render('annonces/searchannonces.html.twig', [
              'annonces' => $annonces,
              'formsearch'=>$form->createView()
               
              
          ]);

    }

  }
