<?php

namespace App\Controller;

use App\Classes\GererSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Projet;
use App\Entity\Tache;


final class GererSessionController extends AbstractController
{
    private $em;
    public function __construct (EntityManagerInterface $em)
    {
        $this->em =$em;
    }

    #[Route('/gerer/session', name: 'app_gerer_session')]
    public function index(GererSession $team): Response
    {
        // $team->remove();
        return $this->render('gerer_session/team.html.twig', [
            'team' => $team->getFull(),
            'prj_id' => $team->getProjet(),
            'tache' => $team->getTache()

        ]);
    }

    #[Route('/gerer/session/add/{id}', name: 'app_add_team')]
    public function addTeam($id,GererSession $team): Response
    {
        $team->addUser($id);
        // $team->remove();
        return $this->redirectToRoute('app_gerer_session');
        
    }

    #[Route('/gerer/session/validate/affectation/{id}', name: 'app_membre_liste')]
    public function validateChoice(Projet $projet, GererSession $team, Tache $tache): Response
    {
        // dd($project->getUser()[2]);
        foreach($team->getFull() as $ind => $array) { 
            $user = $array['user'];
            $user->setIsAffected(true);
            $user->addProjet($projet);
            $user->addTache($tache);
            $this->em->persist($user);
            $this->em->flush();
            $team->delete($user->getId());
        }
        // return $this->redirectToRoute('team');
        return $this->render('gerer_session/membre.html.twig', [
            'team' => $projet->getUser(),
            'prj_id' => $team->getProjet(),
            'tache' => $team->getTache()
        ]);
    }

    #[Route('/gerer/session/exclude/{id}', name: 'excludeUser')]
    public function excludeUser(User $user, ManageSession $team, ProjectRepository $projectRepository): Response
    {
        // dd($project->getUser()[2]);
        $project = $projectRepository->find($team->getProjet());
        $user->setIsAffected(false);
        $user->removeProjet($projet);
        $user->removeTache($tache);
        $this->em->persist($user);
        $this->em->flush();

        // return $this->redirectToRoute('team');
        return $this->render('gerer_session/membre.html.twig', [
            'team' => $projet->getUser(),
            'projetId' => $team->getProjet(),
            'tache' => $team->getTache()
        ]);
    }
}
