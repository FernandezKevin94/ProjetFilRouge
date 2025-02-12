<?php

namespace App\Controller;

use App\Entity\Notifications;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notifications')]
class NotificationsController extends AbstractController
{
    #[Route('/lu/{id}', name: 'app_notification_lu')]
    public function markAsRead(Notifications $notification, EntityManagerInterface $entityManager): Response
    {
        // Vérification si la notification appartient bien à l'utilisateur connecté
        if ($notification->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // // Vérification si la notification existe
        // if (!$notification) {
        //     throw $this->createNotFoundException('Notification not found');
        // }

        // Marquer la notification comme lue
        $notification->setIsRead(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_notifications');
    }

    #[Route('/notifications', name: 'app_notifications')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Récupération des notifications de l'utilisateur triées par date de création
        $notifications = $entityManager->getRepository(Notifications::class)->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        // Si tu souhaites déboguer la variable notifications
        // dump($notifications); 
        // die();

        return $this->render('notifications/notifications.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
