<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Form\ChallengeType;
use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/challenge")]
class ChallengeController extends AbstractController
{
    #[Route("/", name: 'indexChallenge')]
    public function indexChallenge()
    {
        return $this->render('base.html.twig');
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
            
            return $this->redirect($this->generateUrl('indexChallenge'));
        }

        return $this->render('form/challenge.html.twig', [
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
            return $this->redirect($this->generateUrl('indexChallenge'));
        }

        return $this->render('form/challenge.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name: 'deleteChallenge')]
    public function deleteChallenge(Challenge $challenge)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($challenge);
        $em->flush();
            
        return $this->redirect($this->generateUrl('indexChallenge'));
    }
}
