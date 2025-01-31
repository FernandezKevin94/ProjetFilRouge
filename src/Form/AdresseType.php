<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;


class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'titre',
                'attr' => [
                    'placeholder' => 'titre'
                ]
            ])
            ->add('firstname',TextType::class, [
                'label' => 'prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'nom',
                'attr' => [
                    'placeholder' => 'votre nom'
                ]
            ])
            ->add('adresse',TextType::class, [
                'label' => 'adresse',
                'attr' => [
                    'placeholder' => 'votre adresse'
                ]
            ])
            ->add('ville',TextType::class, [
                'label' => 'ville',
                'attr' => [
                    'placeholder' => 'votre ville'
                ]
            ])
            ->add('codepostal', TextType::class,[
                'label' => 'code postal',
                'attr' => [
                'placeholder' => 'Votre code postal'
                ]
            ])
            ->add('pays', CountryType::class, [
                'label' => 'pays'
            ])
            // ->add('user', EntityType::class, [
            //     'class' => user::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
