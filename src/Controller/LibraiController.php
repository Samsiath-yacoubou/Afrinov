<?php

namespace App\Controller;

use App\Repository\InnovationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibraiController extends AbstractController
{
    #[Route('/librai', name: 'app_librai')]
    public function librai(Request $request, InnovationRepository $innovationRepository): Response
    {
        // Récupération des paramètres de la requête
        $queryParams = $request->query->all();

        $pays = $this->normalizeFilterParameter($queryParams['pays'] ?? null);
        $secteurs = $this->normalizeFilterParameter($queryParams['secteur'] ?? null);

        $innovations = $innovationRepository->findByFilters($pays, $secteurs);

        return $this->render('librai/index.html.twig', [
            'innovations' => $innovations,
            'selectedPays' => $pays,
            'selectedSecteurs' => $secteurs
        ]);
    }

    private function normalizeFilterParameter(null|string|array $param): array
    {
        if (is_array($param)) {
            return $param;
        }

        if (is_string($param)) {
            return [$param];
        }

        return [];
    }
}
