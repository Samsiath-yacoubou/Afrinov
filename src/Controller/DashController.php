<?php

namespace App\Controller;

use App\Entity\Colab;
use App\Repository\ColabRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashController extends AbstractController
{
    #[Route('/dash', name: 'app_dash')]
public function index(UserRepository $userRepository, ColabRepository $colabRepository): Response
{
    $currentUser = $this->getUser();
    $isAdmin = $this->isGranted('ROLE_ADMIN');

    $users = [];
    if ($isAdmin) {
        $users = $userRepository->createQueryBuilder('u')
            ->leftJoin('u.innovations', 'i')
            ->addSelect('i')
            ->orderBy('u.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Récupère toutes les demandes de collaboration, triées par id décroissant
    $demandes = $colabRepository->findBy([], ['id' => 'DESC']);

    // Rend la vue Twig en passant toutes les données nécessaires
    return $this->render('dash/index.html.twig', [
        'current_user' => $currentUser,
        'is_admin' => $isAdmin,
        'users' => $users,
        'demandes' => $demandes,
    ]);
}


    #[Route('/dash/user/delete/{id}', name: 'app_user_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);

        if ($user) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } else {
            $this->addFlash('error', 'Utilisateur non trouvé');
        }

        return $this->redirectToRoute('app_dash');
    }

    #[Route('/user/{id}/innovations', name: 'user_innovations')]
    public function showUserInnovations(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('dash/user_innovations.html.twig', [
            'user' => $user,
            'innovations' => $user->getInnovations(),
        ]);
    }

    
   
}
