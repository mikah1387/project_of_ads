<?php

namespace App\Controller;

use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile/update', name: 'app_profile_update')]
    public function update(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
               
            //    $slug= $slugger->slug($annonce->getTitle());
            //    $annonce->setUsers($user);
            //    $annonce->setSlug($slug);
            //    $annonce->setIsActive(false);

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'votre profil  est mis a jour');
                        return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/editprofil.html.twig',['update_userform' => $form->createView()]);

        
    }
    #[Route('/profile/pass/update', name: 'app_profile_update_pass')]
    public function updatePass(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UserInterface $user): Response
    {
        // $user = $this->getUser();
        if($request->isMethod('POST')){
          if($request->request->get('pass')==$request->request->get('pass2')){
          
            $user->setPassword($passwordHasher->hashPassword($user,$request->request->get('pass')));
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'votre mot de passe  est mis a jour');
                        return $this->redirectToRoute('app_profile');

          }else{

            $this->addFlash('alert','les mots de passe ne sont pas identiques');
          }

        }

        return $this->render('profile/editpass.html.twig');

        
    }
}
