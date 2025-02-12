<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Classes\Mail;
use Symfony\Bundle\SecurityBundle\Security;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RequestStack $stack, Security $security): Response
    {
        $user = $security->getUser();
    
        if (!$user) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour voir vos projets.");
        }
        
        $email = new Mail();

        $content ="welcome.html";
        $user = $this->getUser();
        if($user){
        $vars = [
            'prenom' => $user->getFirstName() . ' ' .  $user->getLastName(),
            'service' => $user->getService()
        ];
    } else{
        $vars= NULL;
    }

        $email->send("tobynatsume11@gmail.com", "Luffy", "Bienvenue", $content, $vars);

        return $this->render('home/home.html.twig', [
            'nom' => 'projects solution',
        ]);
    }
}

// #[Route('/home2', name: 'app_home2')]
//     public function index1(): Response
//     {
//         return $this->render('home/home2.html.twig', [
//             // 'vrai' => 'Personne va te détroner Queen',
//         ]);
//     }
// }

