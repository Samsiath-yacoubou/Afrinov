<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function index(EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(Message::class)
            ->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.replyTo', 'r')
            ->addSelect('a')
            ->addSelect('r')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();

        return $this->render('chat/index.html.twig', [
            'messages' => array_reverse($messages),
            'currentUser' => $this->getUser()
        ]);
    }

    #[Route('/chat/send', name: 'send', methods: ['POST'])]
    public function send(
        Request $request,
        EntityManagerInterface $em,
        #[CurrentUser] ?User $user
    ): Response {
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $content = $request->request->get('content');
        $replyTo = $request->request->get('replyTo');

        if (!empty($content)) {
            $message = new Message();
            $message->setContent($content)
                    ->setAuthor($user)
                    ->setCreatedAt(new \DateTimeImmutable());

            if ($replyTo) {
                $parent = $em->find(Message::class, $replyTo);
                if ($parent) {
                    $message->setReplyTo($parent);
                }
            }

            $em->persist($message);
            $em->flush();
        }

        return $this->redirectToRoute('chat');
    }

    #[Route('/chat/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(
        Message $message,
        EntityManagerInterface $em,
        #[CurrentUser] ?User $user
    ): Response {
        if (!$user || $message->getAuthor()->getId() !== $user->getId()) {
            $this->addFlash('error', 'Action non autorisée');
            return $this->redirectToRoute('chat');
        }

        $em->remove($message);
        $em->flush();

        $this->addFlash('success', 'Message supprimé');
        return $this->redirectToRoute('chat');
    }

    #[Route('/chat/updates', name: 'updates', methods: ['GET'])]
    public function getUpdates(
        Request $request,
        EntityManagerInterface $em,
        #[CurrentUser] ?User $user
    ): JsonResponse {
        $lastId = $request->query->getInt('lastId', 0);

        $messages = $em->getRepository(Message::class)
            ->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.replyTo', 'r')
            ->addSelect('a')
            ->addSelect('r')
            ->where('m.id > :lastId')
            ->setParameter('lastId', $lastId)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        $formattedMessages = array_map([$this, 'formatMessage'], $messages);

        return $this->json([
            'messages' => $formattedMessages,
            'lastId' => $messages ? end($messages)->getId() : $lastId
        ]);
    }

    private function formatMessage(Message $message): array
    {
        return [
            'id' => $message->getId(),
            'content' => $message->getContent(),
            'createdAt' => $message->getCreatedAt()->format('H:i'),
            'author' => [
                'id' => $message->getAuthor()->getId(),
                'name' => $message->getAuthor()->getPrenom(),
                'avatar' => $message->getAuthor()->getPhotoprofil() 
            ],
            'replyTo' => $message->getReplyTo() ? [
                'id' => $message->getReplyTo()->getId(),
                'author' => $message->getReplyTo()->getAuthor()->getPrenom(),
                'content' => $message->getReplyTo()->getContent()
            ] : null
        ];
    }
}