<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\SecurityController;
use App\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/{reactRouting?}', name: 'main', priority: "-1", requirements: ['reactRouting'=>'.+'])]
    //#[Route('/', name: 'main')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
         // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // if($this->isGranted('ADMIN'))
        // return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        // else {
            return $this->render('principal/index.html.twig', [
                'user' => $this->getUser(),
                'last_username' => $lastUsername, 
                'error' => $error
            ]);
        // }
    }
}
