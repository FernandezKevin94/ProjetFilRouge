<?php

namespace App\Controller\Admin;

use App\Entity\Tache;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class TacheCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tache::class;
    }

    // public function configureActions(Actions $actions): configureActions
    // {
    //     return $actions
    //         ->remove(Crud::PAGE_INDEX, Actions::NEW)
    //         ->disable(Crud::PAGE_DETAIL, Actions::DELETE)
    //         ->disable(Crud::PAGE_DETAIL, Actions::EDIT)
    //         ;
            
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
            DateField::new('beginDate'),
            DateField::new('endDate'),
            DateField::new('realEndDate'),
            IntegerField::new('priority'),
            BooleanField::new('isFinished'),
            TextareaField::new('descriptionDelay'),
            AssociationField::new('projets'),

        ];
    }
    
}
