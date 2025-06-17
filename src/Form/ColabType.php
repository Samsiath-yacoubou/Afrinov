<?php

namespace App\Form;

use App\Entity\Colab;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomprenom')
            ->add('fonction')
            ->add('nomentreprise')
            ->add('site')
            ->add('email', EmailType::class)
            ->add('tel')
            ->add('linkedin')
            ->add('pays', ChoiceType::class, [
                'choices' => [
                    'Bénin' => 'Bénin',
                    'Burkina Faso' => 'Burkina Faso',
                    'Cap-Vert' => 'Cap-Vert',
                    'Côte d\'Ivoire' => 'Côte d\'Ivoire',
                    'Gambie' => 'Gambie',
                    'Cameroun' => 'Cameroun',
                    'Ghana' => 'Ghana',
                    'Guinée' => 'Guinée',
                    'Guinée-Bissau' => 'Guinée-Bissau',
                    'Liberia' => 'Liberia',
                    'Mali' => 'Mali',
                    'Mauritanie' => 'Mauritanie',
                    'Niger' => 'Niger',
                    'Nigéria' => 'Nigéria',
                    'Sénégal' => 'Sénégal',
                    'Sierra Leone' => 'Sierra Leone',
                ],
                'label' => 'Pays de résidence'
            ])

            ->add('typetalent', ChoiceType::class, [
                'label' => 'Vous souhaitez entrer en contact avec',
                'choices' => [
                    'Une startup' => 'startup',
                    'Un talent tech' => 'talent',
                    'Je ne sais pas encore' => 'indéterminé'
                ],
            ])
            ->add('secteur', ChoiceType::class, [
                'label' => 'Choissez le secteur que vous explorez',
                'choices' => [
                    'Agriculture' => 'Agriculture',
                    'Agroalimentaire' => 'Agroalimentaire',
                    'Énergie' => 'Énergie',
                    'Éducation' => 'Éducation',
                    'Santé' => 'Santé',
                    'Finance / Fintech' => 'Finance / Fintech',
                    'Technologie / TIC' => 'Technologie / TIC',
                    'Transport et logistique' => 'Transport et logistique',
                    'Artisanat' => 'Artisanat',
                    'Commerce / E-commerce' => 'Commerce / E-commerce',
                    'Construction / BTP' => 'Construction / BTP',
                    'Tourisme / Hôtellerie' => 'Tourisme / Hôtellerie',
                    'Mode / Textile' => 'Mode / Textile',
                    'Médias / Communication' => 'Médias / Communication',
                    'Environnement / Climat' => 'Environnement / Climat',
                    'Services' => 'Services',
                    'Autres' => 'Autres',
                ],
                'placeholder' => 'Sélectionnez un secteur',
                'required' => false,
            ])
            
            ->add('typecolab', ChoiceType::class, [
                'label' => 'Type de collaboration',
                'choices' => [
                    'Recrutement' => 'recrutement',
                    'Partenariat' => 'partenariat',
                    'Mentorat' => 'mentorat',
                    'Financement' => 'financement',
                    'Autre' => 'autre'
                ],
            ])
            ->add('durecolab', ChoiceType::class, [
                'label' => 'Durée estimée de la collaboration',
                'choices' => [
                    'Court terme' => 'court terme',
                    'Long terme' => 'long terme',
                    'Ponctuelle' => 'ponctuelle',
                    'À discuter' => 'à discuter'
                ]
            ])
            ->add('message', TextareaType::class)
            ->add('ficher', FileType::class, [
                'label' => 'Joindre un fichier (facultatif)',
                'required' => false,
                'mapped' => true // si tu ne veux pas le lier automatiquement à l'entité
            ])
            ->add('politique', CheckboxType::class, [
                'label' => 'J’accepte que mes informations soient partagées...',
                'required' => true,
                'mapped' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Colab::class,
        ]);
    }
}
