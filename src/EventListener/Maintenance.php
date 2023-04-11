<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;



class Maintenance{

       private $maintenance;
       private $twig;

       public function __construct($maintenance,Environment $twig){

         $this->maintenance = $maintenance;
         $this->twig = $twig;
       }
     

    public function onKernelRequest(RequestEvent $event){

        if (!file_exists($this->maintenance)){

            return;
        }

        // on definit la reponse 
        
       $event->setResponse(
          new Response(
             $this->twig->render('maintenance/maintenance.html.twig'),
             Response::HTTP_SERVICE_UNAVAILABLE
          )
          );
        // on stop le traitement des évènements 
        $event->stopPropagation();  


    }


}