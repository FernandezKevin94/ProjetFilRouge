<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class, [
                'label' => 'titre',
                'attr' => [
                    'placeholder' => 'titre'
                ]
            ])
            ->add('Description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Entrez la description du projet',
                ],
            ])
            ->add('dateDebut', null, [
                'widget' => 'single_text',
                'label' => 'date de début',
            ])
            ->add('dateFin', null, [
                'widget' => 'single_text',
                'label' => 'date de fin',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'en_cours',
                    'Terminé' => 'Termine',
                    'En attente' => 'en_attente'
                ],
                'label' => 'statut'
            ])
            ->add('ChefDeProjet', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getFirstName() . ' ' . $user->getLastName(); // Affiche prénom et nom
                },
                'label' => 'Chef de Projet',
                'placeholder' => 'Sélectionnez un chef de projet',
            ])
    //         ->add('user', EntityType::class, [
    //             'class' => User::class, // L'entité liée
    //             'choice_label' => function(User $user) {
    //                 return $user->getFirstName() . ' ' . $user->getLastName(); // Affiche le prénom et le nom
    //             },
    //             'label' => 'Membres du Projet',
    //             'multiple' => true, // Permet la sélection multiple
    //             'expanded' => true, // Affiche des cases à cocher au lieu d'un menu déroulant
    //             'choice_attr' => function() {
    //     return ['class' => 'form-check-input custom-checkbox']; // Applique le CSS
    // },
    //     'label_attr' => ['class' => 'form-check-label'],
    //         ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}

