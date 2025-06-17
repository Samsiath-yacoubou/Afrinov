<?php

namespace App\Form;

use App\Entity\Innovation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InnovationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('lien', TextType::class)
            ->add('secteur', ChoiceType::class, [
                'choices' => [
                    'Agriculture' => 'AgriTech',
                    'Énergie' => 'EnergyTech',
                    'Éducation' => 'EdTech',
                    'Santé' => 'HealthTech',
                    'Technologie' => 'Tech',
                    'Environnement' => 'EnviroTech',
                    'Finance' => 'FinTech',
                    'Transport' => 'TransTech',
                    'Industrie' => 'IndustryTech',
                    'Autre' => 'OtherTech',

                ],
                'placeholder' => 'Choisir un secteur',
            ])
            ->add('pays', ChoiceType::class, [
                'choices' => [
                    'Bénin' => 'Bénin',
                    'Burkina Faso' => 'Burkina Faso',
                    'Cap-Vert' => 'Cap-Vert',
                    'Côte d\'Ivoire' => 'Côte d\'Ivoire',
                    'Gambie' => 'Gambie',
                    'Ghana' => 'Ghana',
                    'Guinée' => 'Guinée',
                    'Guinée-Bissau' => 'Guinée-Bissau',
                    'Libéria' => 'Libéria',
                    'Mali' => 'Mali',
                    'Mauritanie' => 'Mauritanie',
                    'Niger' => 'Niger',
                    'Nigéria' => 'Nigéria',
                    'Sénégal' => 'Sénégal',
                    'Sierra Leone' => 'Sierra Leone',
                    'Togo' => 'Togo',
                ],
                'placeholder' => 'Choisir un pays',
            ])
            ->add('description', TextType::class)
            ->add('logo', FileType::class, [
                'label' => 'Logo de l\'innovation',
                'mapped' => false, // car non lié à l'entité directement
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Innovation::class,
        ]);
    }
}
