<?php

namespace App\Controller;

use App\Classes\Recherche;
use App\Classes\GererSession;
use App\Entity\Projet;
use App\Form\ProjetType;
use App\Form\RechercheType;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/projet')]
final class ProjetController extends AbstractController
{
    #[Route(name: 'app_projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository,Security $security): Response
    {
        $user = $security->getUser();
    
        if (!$user) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour voir vos projets.");
        }

        return $this->render('projet/indexProjet.html.twig', [
            'projets' => $projetRepository->findAll(),
            
        ]);
    }

    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // $membre = $form['MembreDeProjet']->getData();
            // //  dd($membre);
            // $membre1 = implode(',',$membre);
            // // dd($membre1);
            // $projet->setMembreDeProjet($membre1);
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/newProjet.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet, GererSession $gs): Response
    {
        $gs->addProjet($projet->getId());
        return $this->render('projet/showProjet.html.twig', [
            'projet' => $projet,
            'membres' => $projet->getUser(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Projet $projet,Request $request,EntityManagerInterface $entityManager): Response
    {
        // dd($projet);
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/editProjet.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/affecter/{id}', name: 'app_projet_affecter', methods: ['GET'])]
    public function affecter($id, Request $request, UserRepository $userRepository, GererSession $projet): Response
    {
        $projet->addProjet($id);

        $users = $userRepository->findUsersWithoutProjet();
        
        $search = new Recherche();
        $form = $this->createForm(RechercheType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findBySearch($search);
        }

        return $this->render('projet/affecterProjet.html.twig', [
            'prj_id' => $id,
            'users' => $users,
            'f' => $form->createView()
        ]);
    }

    #[Route('/mes-projets', name: 'app_mes_projets')]
    public function mesProjets(ProjetRepository $projetRepository, Security $security): Response
    {
        $user = $security->getUser();
    
        if (!$user) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour voir vos projets.");
        }
    
        $projets = $projetRepository->findByUser($user);
    
        return $this->render('projet/mes_projets.html.twig', [
            'projets' => $projets,
        ]);
    }    
    
}