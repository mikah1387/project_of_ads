<?php

namespace App\Security\Voter;

use App\Entity\Annonces;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnoncesVoter extends Voter

{
    public const ANNONCE_EDIT = 'annonce_edit';
    public const ANNONCE_DELETE= 'annonce_delete';

    private $security;

    public function __construct(Security $security)
    {

          $this->security = $security ;
    }

    protected function supports(string $attribute, $annonce): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ANNONCE_EDIT, self::ANNONCE_DELETE])
            && $annonce instanceof \App\Entity\Annonces;
    }

    protected function voteOnAttribute(string $attribute, mixed $annonce, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) return true;

         // on verifier si l'annonce a un proprietaire
         if ($annonce->getUsers()===null){

            return false;
         }
         
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ANNONCE_EDIT:
                //on verifier si on peut editer 
                return $this->canEdit( $annonce,  $user);
                break;
            case self::ANNONCE_DELETE:
               // on verifier si on peut suprimer 
               return $this->canDelete( $annonce,  $user);

                break;
        }

        return false;
    }

    private function canEdit(Annonces $annonce, Users $user){
         // le proprietaire peut editer 
       return $annonce->getUsers() === $user ;
    }
    private function canDelete(Annonces $annonce, Users $user){

        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;  
        }else {
            return false;
        }
      
        
    }
}
