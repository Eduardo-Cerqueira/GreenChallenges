<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Form\ChallengeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/challenge")]
class ChallengeController extends AbstractController
{
    #[Route("/", name: 'indexChallenges')]
    public function indexChallenge()
    {
        $em = $this->getDoctrine()->getManager();
        $challenges = $em->getRepository(Challenge::class)->findAll();

        return $this->render('challenge/challengeList.html.twig', [
            'challenges' => $challenges
        ]);
    }

    #[Route("/show/{uuid}", name: 'showChallenge')]
    public function showChallenge($uuid)
    {
        if (!uuid_is_valid($uuid)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $em = $this->getDoctrine()->getManager();
        $challenge = $em->getRepository(Challenge::class)->findOneBy(array('uuid' => $uuid));

        if (is_null($challenge)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        return $this->render('challenge/challengeInfo.html.twig', [
            'challenge' => $challenge
        ]);
    }

    #[Route("/create", name: 'createChallenge')]
    public function createChallenge(Request $request)
    {
        $challenge = new Challenge();

        $form = $this->createForm(ChallengeType::class, $challenge);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($challenge);
            $em->flush();
            
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        return $this->render('challenge/form/challenge.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/edit/{id}", name: 'editChallenge')]
    public function editChallenge($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $challenge = $em->getRepository(Challenge::class)->findOneBy(array('id' => $id));
        
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            $data = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        return $this->render('challenge/form/challenge.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name: 'deleteChallenge')]
    public function deleteChallenge(Challenge $challenge)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($challenge);
        $em->flush();
            
        return $this->redirect($this->generateUrl('indexChallenges'));
    }
}
