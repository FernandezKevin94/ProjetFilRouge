<?php

namespace App\Classes;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;

class GererSession
{
    private $session;
    private $em;
    public function __construct(EntityManagerInterface $em,RequestStack $stack)
    {
        $this->em = $em;
        $this->session = $stack->getSession();
    }

    // Ajouter un user à l'équipe
    public function addTache($id){
        // Créer  un tableau vide si la session vient d'être crée
        $tache = $this->session->get('tache','');
        $tache=$id;

        $this ->session->set('tache', $tache);
    }

    public function getTache(){
        return $this->session->get('tache'); 
    }

    // Ajouter un user à l'équipe
    public function addProjet($id){
        // Créer  un tableau vide si la session vient d'être crée
        $projet = $this->session->get('prj','');
        $projet=$id;

        $this ->session->set('prj', $projet);
    }

    public function getProjet(){
        return $this->session->get('prj'); 
    }

    // Ajouter un user à l'équipe
    public function addUser($id){
        // Créer  un tableau vide si la session vient d'être crée
        $team = $this->session->get('team',[]);
        if(empty($team[$id])){
            $team[$id] = 1;
        }

        $this->session->set('team', $team);
    }

    //  Récuperer le contenu de la session par sa clé : return ,[]
    public function getUser(){
        return $this->session->get('team'); 
    }
        // vider une entrée de la session par sa clé
        public function remove() {
            return $this->session->remove('team');
        }
    
        // Retirer un User de l'equipe 
        public function delete($id){
            $team = $this->session->get('team',[]);
            unset($team[$id]);
            return $this->session->set('team', $team);
        }

        // Récupérer le contenu de la session avec tout les détails des user
    public function getFull()
    {
        $teamFull = [];
        if($this->getUser()){
            foreach($this->getUser() as $id => $point){
                $userObject = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
                if(!$userObject){
                    $this->delete($id);
                    continue;
                }
                $teamFull[] = [
                    'user' => $userObject,
                    'point' => $point
                ];
            }
        }
        return $teamFull;
    }
}