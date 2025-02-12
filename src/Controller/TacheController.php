<?php

namespace App\Controller;

use App\Classes\Recherche;
use App\Classes\GererSession;
use App\Entity\Tache;
use App\Entity\Projet;
use App\Form\TacheType;
use App\Form\RechercheType;
use App\Repository\TacheRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tache')]
final class TacheController extends AbstractController
{
    #[Route('/{id}',name: 'app_tache_index', methods: ['GET'])]
    public function index(Projet $projet, TacheRepository $tacheRepository): Response
    {

        $taches = $tacheRepository->findByProjet($projet);

        return $this->render('tache/indexTache.html.twig', [
            'taches' => $taches,
            'id_projet' => $projet->getId(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Projet $projet, Request $request, EntityManagerInterface $entityManager, TacheRepository $tacheRepository): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tache->setProjet($projet);
            // dd($tache);
            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index', ['id'=>$projet->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tache/newTache.html.twig', [
            'tache' => $tache,
            'form' => $form,
            'id_projet' =>$projet->getId(),
        ]);
    }

    #[Route('/{id}/show', name: 'app_tache_show', methods: ['GET'])]
    public function show(Tache $tache): Response
    {
        return $this->render('tache/showTache.html.twig', [
            'tache' => $tache,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tache/editTache.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tache->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/affecter/{id}', name: 'app_tache_affecter', methods: ['GET'])]
    public function affecter(Tache $tache, Request $request, UserRepository $userRepository, GererSession $gs): Response
    {
        $gs->addTache($tache);

        $projet = $tache->getProjet();

        $users = $userRepository->findUsersWithoutTacheForProjet($projet);

        // $id=$gs->getTache();
        // $gs->addTache($id);
        // $users = $userRepository->findAll();
        $search = new Recherche();
        $form = $this->createForm(RechercheType::class, $search);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findBySearch($search);
        }

        return $this->render('projet/affecterTache.html.twig', [
            'tache' => $tache->getId(),
            'users' => $users,
            'f' => $form->createView()
        ]);
    }

    #[Route('/mes-taches', name: 'app_mes_taches')]
    public function mesTaches(TacheRepository $tacheRepository, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour voir vos tâches.");
        }

        $taches = $tacheRepository->findByUser($user);

        return $this->render('tache/mes_taches.html.twig', [
            'taches' => $taches,
        ]);
    }

}
