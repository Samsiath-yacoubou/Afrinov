<?php

namespace App\Controller;

use App\Repository\InnovationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublicControllersController extends AbstractController
{
    #[Route('/public', name: 'app_public_controllers')]
    public function index(InnovationRepository $innovationRepository): Response
    {
        $lastInnovations = $innovationRepository->findLastThree();

        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
            'innovations' => $lastInnovations,
        ]);
    }
}
