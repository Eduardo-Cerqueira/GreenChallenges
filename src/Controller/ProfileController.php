<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Repository\CurrentChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Security $security, CurrentChallengeRepository $currentChallengeRepository): Response
    {
        // Obtenez l'utilisateur connecté
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Chargez les défis en cours de l'utilisateur
        $currentChallenges = $currentChallengeRepository->findBy(['user_uuid' => $user]);
        $completedChallenges = $currentChallengeRepository->findBy(['user_uuid' => $user, 'status' => 'Completed']);
        $total_points = 0;
        foreach($completedChallenges as $challenge){
            $total_points += $challenge->getPoints();
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'currentChallenges' => $currentChallenges,
            'points' => $total_points
        ]);
    }

    #[Route('/profile/change-password', name: 'app_change_password')]
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $form = $this->createForm(ChangePasswordType::class, null, [
            'action' => $this->generateUrl('app_change_password'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez le nouveau mot de passe de l'utilisateur depuis le formulaire
            $newPassword = $form->get('newPassword')->getData();

            // Encodez le nouveau mot de passe
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);

            // Mettez à jour le mot de passe de l'utilisateur
            $user->setPassword($encodedPassword);

            // Enregistrez les modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

            // Redirigez l'utilisateur vers la page de profil ou une autre page
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/change_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
