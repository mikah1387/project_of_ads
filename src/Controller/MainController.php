<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\ContactType;
use App\Form\SearchAnnonceType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Services\SendMailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/', name: 'home_')]

class MainController extends AbstractController
{
     #[Route('/', name: 'index')]

    public function index(CategoriesRepository $categoriesRepository): Response
    {
         $categories = $categoriesRepository->findby([], ['name'=>'asc']);
          return $this->render('main/index.html.twig', [
            'categories' => $categories 
           
        ]);
    }

    #[Route('/contact', name: 'contact')]

    public function contact(Request $request, SendMailService $serviceMail): Response
    {
        
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            // $email = (new TemplatedEmail())
            //         ->from($form->get('email')->getData())
            //         ->to('achache.hakim@gmail.com')
            //         ->subject('contact depuis le site boncoin')
            //         ->htmlTemplate('emails/contact.html.twig')

            // // pass variables (name => value) to the template
            //          ->context([
            //             // ne pas maitre name comme email dans le context
                        
            //           ]);
                    // $mailer->send($email);


                    $serviceMail->sendMail(
                      $form->get('email')->getData(),
                      'achache.hakim@gmail.com',
                      'contact depuis le site boncoin',
                      'emails/contact.html.twig',
                      [
                        'e_mail' =>$form->get('email')->getData(),
                        'sujet'=>$form->get('sujet')->getData(),
                        'message' => $form->get('message')->getData(),
                      ]
                    );
                    $this->addFlash('success', 'Votre mail a bien été  enovyé');
                    return $this->redirectToRoute('home_contact');


        }
        
         
               return $this->render('main/contact.html.twig', [
                     'formContact'=>$form->createView()
            
           
        ]);
    }

 
   
}
