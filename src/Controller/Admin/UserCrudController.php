<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('matricule'),
            TextField::new('email'),
            TextField::new('password'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            DateField::new('birthday'),
            TextField::new('telephone'),
            TextField::new('service'),
            TextField::new('speciality'),

            ChoiceField::new('roles')
                ->setLabel('Permissions')
                ->setHelp('Choix des rôles des membres')
                ->setChoices([
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_CFIEF' => 'ROLE_CHIEF',
                'ROLE_USER' => 'ROLE_USER',
            ])->allowMultipleChoices(),
        ];
    }
}
