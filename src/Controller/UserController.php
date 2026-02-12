<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {

        $user = $security->getUser();

        if (!$user)
         {
            throw $this->createNotFoundException("Aucun utilisateur connecté.");
         }

        return $this->render('user/indexUser.html.twig', [
            'users' => [$user],
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $uphi): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mdp
            // récupérer le mdp à partir du form
            $user = $form->getData();
            $mdp = $user->getPassword();
            $mdp = $uphi->hashPassword($user, $mdp);
            //remettre le mdp dans l'objet user
            $user->setPassword($mdp);
            // Récupérer photo et rénitialiser le chemin
            $chemin = "assets/img/user";
            $fichier = $form['photo']->getData();
            $fichier->move($chemin, $fichier->getClientOriginalName());
            $user->setPhoto("assets/img/user" . "/" . $fichier->getClientOriginalName());
            // Sauvegarde dans la BDD
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/newUser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/showUser.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $uphi): Response
    {
        $user->setPhoto(null);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Hasher le mdp
            // récupérer le mdp à partir du form
            $user = $form->getData();
            $mdp = $user->getPassword();
            $mdp = $uphi->hashPassword($user, $mdp);
            //remettre le mdp dans l'objet user
            $user->setPassword($mdp);
            
            $chemin = "assets/img/user";
            $fichier = $form['photo']->getData();
            $fichier->move($chemin, $fichier->getClientOriginalName());
            $user->setPhoto("assets/img/user" . "/" . $fichier->getClientOriginalName());
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/editUser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
