<?php

namespace App\Controller;
use App\Form\ResetPassType;
use App\Repository\UsersRepository;
use App\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
#use Symfony\Component\CssSelector\Parser\Token\TokenGeneratorInterface;

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
    public function forgottenPassword(Request $request, UsersRepository $usersRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokeGenerator)
    {
        //On créer la formulaire pour la récupération
        $form  = $this->createForm(ResetPassType::class);

        //On traite la formulaire
        $form->handleRequest($request);

        //Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            #On récupere les données
            $donnees = $form->getData();
            #On cherche si un utilisateur a cet e-mail
            $user = $usersRepo->findOneByEmail($donnees['email']);
            
            //dd($user);
            #Si l'utilisateur n'existe pas
            if (!$user) {
                #On envoie un message flash
                $this->addFlash('danger', 'Cette adresse mail n\'existe pas !');
                return $this->redirectToRoute('app_login');
            }

            #On génère un token
            $token = $tokeGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur est survvenue', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            #On génére l'URL de l'initialisation
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            #On envoie le message
            $message = (new \Swift_Message('Mot de passe oublié | Secu Quizz'))
                ->setFrom('notre@adresse.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    "<h3>Bonjour!,<h3> <p>Une demande de réinisialisation de mot de passe a été effectuée pour le site Secu Quizz, Veuillez cliquez sur ce lien suivant : " . $url . "</p>",
                    'text/html'
                );
            #On envoie le Mail
            $mailer->send($message);

            #On créer le message Flush
            $this->addFlash('message', 'Vérifiez votre e-mail, Un e-mail de réinisialisation de Mot de passe a été envoyé !');
        }

        #On envoie vers la page de demande d'E-mail
        return $this->render('security/forgottenPassword.html.twig', [
            'controller_name' => 'Mot de passse oublié',
            'emailForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        #On cherche l'utilisateur avec le Token fournie
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['reset_token' => $token]);

        if (!$user) {
            # code...
            $this->addFlash('danger', 'Token Inconue');
            return $this->redirectToRoute('app_login');
        }

        #Si le formulaire est envoyé en methode POST
        if($request->isMethod('POST')){
            #On supprime le Token
            $user->setResetToken(null);

            #On chiffre le mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $request->request->get('password'))
            );

            #On Enregistre dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addflash('message', 'Mot de passe Modifier avec succeès!');
            return $this->redirectToRoute('app_login');
        }
        #S'il n'est pas envoué en METHODE POST
        else {
            # code...
            return $this->render('security/resetPassword.html.twig', ['controller_name' => 'Nouveau Mot de Passe', 'token' => $token]);
        }

        return $this->render('security/resetPassword.html.twig', [
            'controller_name' => 'Nouveau Mot de Passe'
        ]);
    }
}
