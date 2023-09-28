<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\CurrentChallenge;
Use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    #[Route('/classement', name: 'app_ranking')]
    public function ranking(EntityManagerInterface $entityManager): Response
    {
        // Récupérez les 10 premiers utilisateurs avec le plus de points
        $userRepository = $entityManager->getRepository(User::class);
        $topUsers = $userRepository->findTopUsers(10);

        return $this->render('ranking/index.html.twig', [
            'topUsers' => $topUsers,
        ]);
    }
}
