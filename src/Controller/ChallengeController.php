<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Form\ChallengeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/challenge")]
class ChallengeController extends AbstractController
{
    #[Route("/create", name: 'challengeCreate')]
    public function createChallenge(Request $request)
    {
        $challenge = new Challenge();

        $form = $this->createForm(ChallengeType::class, $challenge);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($challenge);
            $em->flush();

            //return $this->redirect($this->generateUrl('challenge.index'));
        }

        return $this->render('form/challenge.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
