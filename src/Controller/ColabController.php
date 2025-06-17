<?php

namespace App\Controller;

use App\Entity\Colab;
use App\Form\ColabType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ColabController extends AbstractController
{
    #[Route('/colab', name: 'app_colab')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
      
        $colab= new Colab();
        $form = $this->createForm(ColabType::class, $colab);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $file = $request->files->get('colab')['ficher'];
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory'), $fileName);
                $colab->setFicher($fileName);
            }

            $em->persist($colab);
            $em->flush();

            $this->addFlash('success', 'Colab ajouté avec succès');

            return $this->redirectToRoute('app_colab');
        }


        return $this->render('colab/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
