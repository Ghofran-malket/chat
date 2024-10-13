<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Form\ChatMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/chat', name: 'chat')]
class ChatController extends AbstractController
{
    
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/{user}', name:'_view')]
    public function chatView($user, Request $request): Response
    {
        $messages = $this->entityManager->getRepository(Chat::class)->findBy(['receiver' => $user]);
        $sentMessages = $this->entityManager->getRepository(Chat::class)->findBy(['sender' => $user]);
        $allMessages = array_merge($messages, $sentMessages);

        usort($allMessages, function ($a, $b) {
            return $a->getTimestamp() <=> $b->getTimestamp();
        });

        // Create and handle the chat message form
        $chat = new Chat();
        $form = $this->createForm(ChatMessageType::class, $chat);
        $form->get('receiver')->setData($user); // Set receiver automatically

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chat->setSender('YourUsername'); // Replace with actual username
            $chat->setTimestamp(new \DateTime());

            $this->entityManager->persist($chat);
            $this->entityManager->flush();

            return $this->redirectToRoute('chat_view', ['user' => $user]);
        }

        return $this->render('chat/index.html.twig', [
            'messages' => $allMessages,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
