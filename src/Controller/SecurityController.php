<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on l'oriente vers sa page dédiée
        if ($this->getUser()) {
            return $this->redirectToRoute('app_login_redirect');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Route appelée UNIQUEMENT après la connexion pour orienter selon le rôle
     */
    #[Route(path: '/login-redirect', name: 'app_login_redirect')]
    public function loginRedirect(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_dashboard');
        }

        if ($this->isGranted('ROLE_STAFF')) {
            return $this->redirectToRoute('app_pos');
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode est interceptée par la clé logout du firewall.');
    }
}