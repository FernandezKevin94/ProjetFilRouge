<?php

namespace App\Controller;

use App\Classes\GererSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TacheRepository;
use App\Repository\UserRepository;
use App\Entity\Projet;
use App\Entity\Tache;
use App\Entity\User;


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
            // 'tache' => $team->getTache()

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
    public function validateChoice(Projet $projet, GererSession $team, UserRepository $userRepository): Response
    {
        // dd($project->getUser()[2]);
        $users = $userRepository->findByProjet($projet);

        foreach($team->getFull() as $ind => $array) { 
            $user = $array['user'];
            $user->setIsAffected(true);
            $user->addProjet($projet);
            // $tache->setUser($user);
            $this->em->persist($user);
            $this->em->flush();
            $team->delete($user->getId());
        }
        // return $this->redirectToRoute('team');
        return $this->render('gerer_session/membre.html.twig', [
            'team' => $projet->getUser(),
            'prj_id' => $team->getProjet(),
            // 'tache' => $team->getTache()
        ]);
    }
    
    #[Route('validate/affectation/{id}', name: 'app_tache_liste')]
    public function validateTache(User $user, GererSession $gs, TacheRepository $tacheRepository): Response
    {
        // dd($project->getUser()[2]);
            // $user = $array['user'];
            // $user->setIsAffected(true);
            $tache=$gs->getTache();
            // $t=$tacheRepository->findOneById(array($tache));
            // dd($t);
            // $user->addTache($tache);
            $projet=$gs->getProjet();

            // if (!$user->isAvailable()) {
            //     $this->addFlash('erreur', 'Utilisateur déjà affilié a une tâche.');
            //     return $this->redirectToRoute('app_tache_index',['id'=>$projet]);
            // }
            // dd($tache);
            $tache->setUser($user);
            $user->setAvailable(false);
            // $this->em->persist($tache);
            $cnx=$this->em->getConnection();
            $idUser=$user->getId();
            $idTache=$tache->getId();
            $sql="UPDATE tache SET user_id = :userId WHERE id = :tacheId";
            $cnx->executeQuery($sql,[
                'userId' =>$idUser,
                'tacheId' =>$idTache
            ]);

            // $sql2  = "UPDATE user SET is_available = 0 WHERE id = :idUser";
            // $cnx->executeQuery($sql2, ['idUser' => $idUser]);
            // $this->em->flush();

        return $this->redirectToRoute('app_tache_index', ['id'=>$projet]);
    
        // return $this->render('gerer_session/membre.html.twig', [
            // 'team' => $projet->getUser(),
            // 'prj_id' => $team->getProjet(),
            // 'tache' => $user->getTache()
        // ]);
    }

    #[Route('validate/exclude/{id}', name: 'exclude_from_tache')]
    public function excludeUserFromTache(Tache $tache, GererSession $gs, TacheRepository $tacheRepository): Response
    {
    
            // $tache=$gs->getTache();
            $projet=$gs->getProjet();
            // dd($projet);
            // $tache->setUser($user);
            $user = $tache->getUser();
            $user->setAvailable(false);
            $cnx=$this->em->getConnection();
            // $idUser=$user->getId();
            $idTache=$tache->getId();
            $sql="UPDATE tache SET user_id = NULL WHERE id = :tacheId";
            $cnx->executeQuery($sql,['tacheId' =>$idTache]);

        return $this->redirectToRoute('app_tache_index', ['id'=>$projet]);
    
    }

    #[Route('/gerer/session/exclude/{id}', name: 'excludeUser')]
    public function excludeUser(User $user, ManageSession $team, ProjectRepository $projectRepository): Response
    {
        // dd($project->getUser()[2]);
        $project = $projectRepository->find($team->getProjet());
        $user->setIsAffected(false);
        $user->removeProjet($projet);
        // $user->removeTache($tache);
        $this->em->persist($user);
        $this->em->flush();

        // return $this->redirectToRoute('team');
        return $this->render('gerer_session/membre.html.twig', [
            'team' => $projet->getUser(),
            'projetId' => $team->getProjet(),
            // 'tache' => $team->getTache()
        ]);
    }
}
