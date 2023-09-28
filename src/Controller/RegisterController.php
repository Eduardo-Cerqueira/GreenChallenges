<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class RegisterController extends AbstractController
{
    #[Route("/register", name: 'register')]
    public function register(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()){

            $selectedRoles = $form->get('role')->getData();
            $user->setRole([$selectedRoles]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            return $this->redirect($this->generateUrl('challengeIndex'));
        }

        return $this->render('form/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}