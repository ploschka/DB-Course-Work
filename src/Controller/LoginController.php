<?php

namespace App\Controller;

use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $au): Response
    {
        $error = $au->getLastAuthenticationError();
        $lastLogin = $au->getLastUsername();

        $m = new MenuCreator;
        return $this->render('login.html.twig', [
            'last_login' => $lastLogin,
            'error' => $error,
            'menu' => $m->getMenu('login'),
        ]);
    }
}
