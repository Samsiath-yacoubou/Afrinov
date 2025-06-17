<?php

namespace App\Controller;

use App\Entity\Colab;
use App\Repository\ColabRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GcolabController extends AbstractController
{
    #[Route('/admin/demandes-collaboration', name: 'app_gcolab')]
    public function index(ColabRepository $colabRepository): Response
    {
        $demandes = $colabRepository->findBy([], ['id' => 'DESC']);
        
        return $this->render('gcolab/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    #[Route('/admin/demandes-collaboration/{id}/supprimer', name: 'app_gcolab_delete', methods: ['POST'])]
    public function delete(Request $request, Colab $colab, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$colab->getId(), $request->request->get('_token'))) {
            $entityManager->remove($colab);
            $entityManager->flush();
            
            $this->addFlash('success', 'La demande a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_gcolab', [], Response::HTTP_SEE_OTHER);
    }
}
