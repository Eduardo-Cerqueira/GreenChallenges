<?php

use App\Form\AuthenticationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends AbstractController
{
    public function showForm(Request $request): Response
    {
        $user = new User(); // Create a new User entity instance
        $form = $this->createForm(AuthenticationFormType::class, $user);
    
        return $this->render('authentification.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private $form;
    
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
}
