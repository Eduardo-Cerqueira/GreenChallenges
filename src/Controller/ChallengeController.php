<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\CurrentChallenge;
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
            'challenges' => $challenges,
            'user' => $this->getUser()
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
            'challenge' => $challenge,
            'user' => $this->getUser()
        ]);
    }

    #[Route("/accept/{uuid}", name: 'acceptChallenge')]
    public function acceptChallenge($uuid)
    {
        if (!uuid_is_valid($uuid)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $em = $this->getDoctrine()->getManager();
        $challenge = $em->getRepository(Challenge::class)->findOneBy(array('uuid' => $uuid));

        if (is_null($challenge)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $user = $this->getUser();
        $currentChallenge = $em->getRepository(CurrentChallenge::class)->findOneBy(array('challenge_id_id' => $challenge, 'user_uuid_id' => $user, 'status' => 'Doing'));

        if (!is_null($currentChallenge)) {
            return $this->redirect($this->generateUrl('showChallenge', array($uuid)));
        }

        $currentChallenge = new CurrentChallenge();

        $currentChallenge->setChallengeId($challenge);
        $currentChallenge->setUserUuid($user);
        $currentChallenge->setStatus('Doing');
        $currentChallenge->setPoints($challenge->getPoints());

        $em = $this->getDoctrine()->getManager();

        $em->persist($currentChallenge);
        $em->flush();

        return $this->redirect($this->generateUrl('indexChallenges'));
    }

    #[Route("/complete/{uuid}", name: 'completeChallenge')]
    public function completeChallenge($uuid)
    {
        if (!uuid_is_valid($uuid)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $em = $this->getDoctrine()->getManager();
        $currentChallenge = $em->getRepository(CurrentChallenge::class)->findOneBy(array('uuid' => $uuid));

        if (is_null($currentChallenge)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $currentChallenge->setStatus('Completed');
        $em->persist($currentChallenge);
        $em->flush();

        return $this->redirect($this->generateUrl('indexChallenges'));
    }

    #[Route("/abandon/{uuid}", name: 'abandonChallenge')]
    public function abandonChallenge($uuid)
    {
        if (!uuid_is_valid($uuid)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $em = $this->getDoctrine()->getManager();
        $currentChallenge = $em->getRepository(CurrentChallenge::class)->findOneBy(array('uuid' => $uuid));
        
        if (is_null($currentChallenge)) {
            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        $currentChallenge->setStatus('Abandoned');
        $em->persist($currentChallenge);
        $em->flush();

        return $this->redirect($this->generateUrl('indexChallenges'));
    }


    #[Route("/admin/create", name: 'createChallenge')]
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

    #[Route("/admin/edit/{uuid}", name: 'editChallenge')]
    public function editChallenge($uuid, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $challenge = $em->getRepository(Challenge::class)->findOneBy(array('uuid' => $uuid));
        
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            $em->flush();

            return $this->redirect($this->generateUrl('indexChallenges'));
        }

        return $this->render('challenge/form/challenge.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/admin/delete/{uuid}", name: 'deleteChallenge')]
    public function deleteChallenge(Challenge $challenge)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($challenge);
        $em->flush();
            
        return $this->redirect($this->generateUrl('indexChallenges'));
    }
}
