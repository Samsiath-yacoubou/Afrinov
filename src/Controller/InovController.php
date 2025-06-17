<?php

namespace App\Controller;

use App\Entity\Innovation;
use App\Form\InnovationType;
use App\Repository\InnovationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class InovController extends AbstractController
{
   #[Route('/inov', name: 'app_inov')]
public function index(Request $request, EntityManagerInterface $em): Response
{
    $user = $this->getUser(); // On récupère l'utilisateur connecté

    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour ajouter une innovation.');
    }

    $innovation = new Innovation();
    $form = $this->createForm(InnovationType::class, $innovation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Associer l'utilisateur connecté à l'innovation
        $innovation->setUser($user);

        $logoFile = $form->get('logo')->getData();

        if ($logoFile) {
            $newLogoName = uniqid() . '.' . $logoFile->guessExtension();
            $logoFile->move(
                $this->getParameter('uploads_directory'),
                $newLogoName
            );
            $innovation->setLogo($newLogoName);
        }

        $em->persist($innovation);
        $em->flush();

        $this->addFlash('success', 'Innovation ajoutée avec succès');

        return $this->redirectToRoute('app_list');
    }

    return $this->render('inov/index.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/inov/edit/{id}', name: 'app_inovmodif')]
    public function index2(Request $request, Innovation $innovation, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(InnovationType::class, $innovation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            $this->addFlash('success', 'Innovation modifiée avec succès');

            return $this->redirectToRoute('app_list');
        }


        return $this->render('inov/index2.html.twig', [
            'form' => $form->createView(),
            'innovations' => $innovation,
        ]);
    }

    #[Route('/inovlist', name: 'app_list')]
    
public function list(Request $request, InnovationRepository $innovationRepository): Response
{
    // Récupération robuste des paramètres
    $user = $this->getUser();


    // On récupère les innovations associées à l'utilisateur
    $innovations = $innovationRepository->findBy(['user' => $user]);
    // $queryParams = $request->query->all();
    
    // $pays = $this->normalizeFilterParameter($queryParams['pays'] ?? null);
    // $secteurs = $this->normalizeFilterParameter($queryParams['secteur'] ?? null);
    
    // $innovations = $innovationRepository->findByFilters($pays, $secteurs);
    
    return $this->render('inov/list.html.twig', [
        'innovations' => $innovations,
        // 'selectedPays' => $pays,
        // 'selectedSecteurs' => $secteurs
    ]);
}

private function normalizeFilterParameter($param): array
{
    if (is_array($param)) {
        return array_filter($param); // Supprime les valeurs vides
    }
    
    if (!empty($param)) {
        return [$param];
    }
    
    return [];
}
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(Request $request, Innovation $innovation,EntityManagerInterface $em): Response
    {
        $em->remove($innovation);
        $em->flush();

        

        $this->addFlash('success', 'Innovation supprimée avec succès');
        return $this->redirectToRoute('app_inov');
    }
}
