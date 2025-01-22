<?php
// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
// Si l'utilisateur est déjà connecté, on le redirige
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

// Récupérer l'erreur de connexion s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();

// Dernier identifiant saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
        //dd ($lastUsername);
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
// Le logout est géré automatiquement par Symfony, ce contrôleur peut rester vide
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
