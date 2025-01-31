<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RequestStack $stack): Response
    {
        $stack->getSession()->set('team',[
            [
                'id' => 1001,
                'matricule' => 'ABC'
            ],
            [
                'id' =>1002,
                'matricule' => 'AZERTY'
            ]
        ]);
        $team = $stack ->getSession()->get('team');
        
        return $this->render('home/home.html.twig', [
            // 'nom' => 'Kim Chae Won',
            // 'hum' => 'Elle fracasse Yunjin sans débat'
        ]);
    }


#[Route('/home2', name: 'app_home2')]
    public function index1(): Response
    {
        return $this->render('home/home2.html.twig', [
            // 'vrai' => 'Personne va te détroner Queen',
        ]);
    }
}

