<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        
        // Création du formulaire d'édition de profil
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de la photo de profil
            $photoFile = $form->get('photoprofil')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();
                
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $user->setPhotoprofil($newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception si le téléchargement échoue
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo');
                }
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/index.html.twig', [
            'profileForm' => $form->createView(),
            'user' => $user,
        ]);
    }
}