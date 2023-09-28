<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route("/register", name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $plaintextPassword = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $selectedRoles = $form->get('roles')->getData();
            $user->setRoles([$selectedRoles]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        return $this->render('form/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}