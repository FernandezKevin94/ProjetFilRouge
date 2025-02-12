<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\Proxy;
use App\Entity\User;
use App\Entity\Adresse;
use App\Entity\Projet;
use App\Entity\Tache;

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;
    public function __construct(adminUrlGenerator $adminUrlGenerator)
    {
        return $this->adminUrlGenerator = $adminUrlGenerator;
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $url = $this->adminUrlGenerator
                    ->setController(UserCrudController::class)
                    ->generateUrl();
        return $this->redirect($url);

    }

    // public function configureActions(Actions $actions): configureActions
    // {
    //     return $actions
    //         ->remove(Crud::PAGE_INDEX, Actions::NEW);
    // }
    

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('ManaginProjects');
        
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Retour au site', 'fas fa-arrow-left', '/');

        yield MenuItem::section('Gestion de Projets');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-home', User::class);
        yield MenuItem::subMenu('Utilisateurs', 'fa fa-users', User::class)
        ->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fa fa-plus', User::class)
                ->setAction(Crud::PAGE_NEW), 
            MenuItem::linkToCrud('Visualiser', 'fa fa-eye', User::class)
        ]);

        yield MenuItem::linkToCrud('Adresses', 'fa fa-home', Adresse::class);
        yield MenuItem::subMenu('Adresse', 'fa fa-users', Adresse::class)
        ->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fa fa-plus', Adresse::class)
                ->setAction(Crud::PAGE_NEW), 
            MenuItem::linkToCrud('Visualiser', 'fa fa-eye', Adresse::class)
        ]);
        
        yield MenuItem::section('Projets');
        yield MenuItem::subMenu('Projets', 'fa fa-users', Projet::class)
        ->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fa fa-plus', Projet::class)
                ->setAction(Crud::PAGE_NEW), 
            MenuItem::linkToCrud('Visualiser', 'fa fa-eye', Projet::class)
        ]);

        yield MenuItem::section('Taches');
        yield MenuItem::subMenu('Taches', 'fa fa-users', Tache::class)
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter', 'fa fa-plus', Tache::class)
                    ->setAction(Crud::PAGE_NEW), 
                MenuItem::linkToCrud('Visualiser', 'fa fa-eye', Tache::class)
            ]);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}