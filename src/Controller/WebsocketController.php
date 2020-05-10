<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebsocketController extends AbstractController
{
        /**
         * @Route("/chat", name = "chat")
         */
        public function getPage(){
            return $this->render('chat.html.twig',[
                'controller_name' => 'WesSocketController',
            ]);
        }
}