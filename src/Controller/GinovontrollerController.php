<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GinovontrollerController extends AbstractController
{
    #[Route('/ginovontroller', name: 'app_ginovontroller')]
    public function index(): Response
    {
        return $this->render('ginovontroller/index.html.twig', [
            'controller_name' => 'GinovontrollerController',
        ]);
    }
}
