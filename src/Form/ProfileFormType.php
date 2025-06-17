<?php

namespace App\Form;

use App\Entity\User;
use Dom\Text;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterfacE $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('email', TextType::class) // Meilleur que TextType pour les emails
            ->add('lieu', TextType::class, ['required' => false])
            ->add('tel', TextType::class, ['required' => false])
            ->add('apropos', TextareaType::class, [
                'label' => 'À propos de moi',
                'required' => false
            ])
            ->add('facebook', TextType::class, ['required' => false])
            ->add('linkedin', TextType::class, ['required' => false])
            ->add('twiter', TextType::class, ['required' => false])
            ->add('photoprofil', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Format accepté : JPG, PNG ou WEBP',
                    ])
                ],
            ])
            ->add('competences', ChoiceType::class, [
                'label' => 'Métiers Tech',
                'choices' => [
                    'Développeur·se front-end' => 'Développeur·se front-end',
                    'Développeur·se back-end' => 'Développeur·se back-end',
                    'Développeur·se full-stack' => 'Développeur·se full-stack',
                    'Développeur·se mobile' => 'Développeur·se mobile',
                    'Data scientist' => 'Data scientist',
                    'Data analyst' => 'Data analyst',
                    'Ingénieur·e en machine learning' => 'Ingénieur·e en machine learning',
                    'DevOps' => 'DevOps',
                    'Ingénieur·e cloud' => 'Ingénieur·e cloud',
                    'UX/UI designer' => 'UX/UI designer',
                    'Intégrateur·trice web' => 'Intégrateur·trice web',
                    'Product owner' => 'Product owner',
                    'Scrum master' => 'Scrum master',
                    'Ingénieur·e logiciel' => 'Ingénieur·e logiciel',
                    'Testeur·se QA' => 'Testeur·se QA',
                    'Architecte logiciel' => 'Architecte logiciel',
                    'Administrateur·trice système' => 'Administrateur·trice système',
                    'Consultant·e en cybersécurité' => 'Consultant·e en cybersécurité',
                    'Chef·fe de projet technique' => 'Chef·fe de projet technique',
                    'Ingénieur·e DevOps' => 'Ingénieur·e DevOps',
                    'Développeur·se Python' => 'Développeur·se Python',
                    'Développeur·se Java' => 'Développeur·se Java',
                    'Développeur·se C++' => 'Développeur·se C++',
                    'Développeur·se Ruby' => 'Développeur·se Ruby',
                    'Développeur·se .NET' => 'Développeur·se .NET',
                    'Ingénieur·e en intelligence artificielle' => 'Ingénieur·e en intelligence artificielle',
                    'Analyste en sécurité informatique' => 'Analyste en sécurité informatique',
                    'Consultant·e en blockchain' => 'Consultant·e en blockchain',
                    'Consultant·e en big data' => 'Consultant·e en big data',
                    'Architecte cloud' => 'Architecte cloud',
                    'Développeur·se JavaScript' => 'Développeur·se JavaScript',
                    'Développeur·se React' => 'Développeur·se React',
                    'Développeur·se Angular' => 'Développeur·se Angular',
                    'Développeur·se Vue.js' => 'Développeur·se Vue.js',
                    'Développeur·se Node.js' => 'Développeur·se Node.js',
                    'Spécialiste SEO' => 'Spécialiste SEO',
                    'Chef·fe de projet web' => 'Chef·fe de projet web',
                    'Analyste fonctionnel' => 'Analyste fonctionnel',
                    'Ingénieur·e en réalité virtuelle' => 'Ingénieur·e en réalité virtuelle',
                    'Ingénieur·e en réalité augmentée' => 'Ingénieur·e en réalité augmentée',
                    'Spécialiste en automatisation des tests' => 'Spécialiste en automatisation des tests',
                    'Ingénieur·e en bases de données' => 'Ingénieur·e en bases de données',
                    'Administrateur·trice de bases de données' => 'Administrateur·trice de bases de données',
                    'Développeur·se WordPress' => 'Développeur·se WordPress',
                    'Consultant·e en transformation numérique' => 'Consultant·e en transformation numérique',
                    'Ingénieur·e en système embarqué' => 'Ingénieur·e en système embarqué',
                    'Analyste de données' => 'Analyste de données',
                    'Consultant·e en intelligence d’affaires' => 'Consultant·e en intelligence d’affaires',
                    'Chef·fe de produit numérique' => 'Chef·fe de produit numérique',
                    'Développeur·se PHP' => 'Développeur·se PHP',
                    'Développeur·se Swift' => 'Développeur·se Swift',
                    'Développeur·se Kotlin' => 'Développeur·se Kotlin',
                    'Développeur·se Ruby on Rails' => 'Développeur·se Ruby on Rails',
                    'Architecte cloud' => 'Architecte cloud',
                    'Développeur·se d’applications mobiles' => 'Développeur·se d’applications mobiles',
                    'Spécialiste en systèmes d’information' => 'Spécialiste en systèmes d’information',
                    'Consultant·e en stratégie digitale' => 'Consultant·e en stratégie digitale',
                    'Développeur·se game' => 'Développeur·se game',

                    // ... (autres options)
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'empty_data' => [],
                'attr' => [
                    'class' => 'form-control select2',  // Classe pour appliquer Select2
                    'style' => 'width: 100%;'  // Assure-toi que la largeur est bien ajustée
                 // Vous pouvez ajuster cette taille pour définir combien d'éléments sont visibles dans la liste
            ],
            'label' => 'Sélectionnez vos compétences',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
