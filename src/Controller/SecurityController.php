<?php

namespace App\Controller;
use App\Form\ResetPassType;
use App\Repository\UsersRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => 'Se connecter'
        ]);
    }

    /**
     * @Route("/deconnecter", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return $this->render('security/login.html.twig', [
            'title' => 'Se connecter'
        ]);
    }

    /**
     * Creer la recupération du Mot de passe de l\utilisateur
     * 
     * @Route("/oubli_pass", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, UsersRepository $usersRepo, \Swift_Mailer $mailer, 
    TokenGeneratorInterface $tokeGenerator): Response
    {
        //On créer la formulaire pour la récupération
        $form  = $this->createForm(ResetPassType::class);

        //On traite la formulaire
        $form->handleRequest($request);

        //Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            #On récupere les données
            $donnes = $form->getData();

            #On cherche si un utilisateur a cet e-mail
            $user = $usersRepo->findOneByEmail($donnes['email']);

            #Si l'utilisateur n'existe pas
            if (!$user) {
                #On envoie un message flash
                $this->addFlash('danger', 'Cette adresse mail n\'existe pas !');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/forgottenPassword.html.twig', [
            'controller_name' => 'Mot de passse oublié'
        ]);
    }
}
