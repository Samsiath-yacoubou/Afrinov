<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\DiscussionRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends AbstractController
{
    #[Route('/discussion/{id}', name: 'discussion_show')]
    public function show(Discussion $discussion, Request $request, EntityManagerInterface $em): Response
    {
        // Créer un nouveau message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le message
            $message->setAuthor($this->getUser());
            $message->setDiscussion($discussion);
            $message->setSentAt(new \DateTimeImmutable());

            $em->persist($message);
            $em->flush();

            // Retourner la vue avec les messages actualisés
            return $this->redirectToRoute('discussion_show', ['id' => $discussion->getId()]);
        }

        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/discussion/{id}/messages', name: 'load_messages', methods: ['GET'])]
    public function loadMessages(Discussion $discussion, MessageRepository $messageRepository): JsonResponse
    {
        // Récupérer les messages de la discussion spécifiée
        $messages = $messageRepository->findBy(
            ['discussion' => $discussion],
            ['sentAt' => 'ASC'] // Trier par date d'envoi
        );

        // Transformer les messages en format JSON
        $data = [];
        foreach ($messages as $message) {
            $data[] = [
                'author' => $message->getAuthor()->getUsername(),
                'content' => $message->getContent(),
                'sentAt' => $message->getSentAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    // Optionnel : Si tu veux ajouter la possibilité d'envoyer des messages en AJAX
    #[Route('/discussion/{id}/send_message', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Discussion $discussion, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $content = $request->request->get('content');

        if ($content) {
            $message = new Message();
            $message->setContent($content);
            $message->setAuthor($this->getUser());
            $message->setDiscussion($discussion);
            $message->setSentAt(new \DateTimeImmutable());

            $em->persist($message);
            $em->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Message envoyé avec succès'
            ]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => 'Erreur lors de l\'envoi du message'
        ], 400);
    }
}
