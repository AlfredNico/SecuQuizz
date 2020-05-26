<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;

use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #Obtenier et chiffré le Mot de Passe de l'utilisateur
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            #On va générer la Token de l'activation
            $user->setActivationToken(md5(uniqid()));

            #Enregister dans nos base de donnée
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            #On créer le message 
            $message = (new \Swift_Message('Activation de votre compte'))
                #On attribue l'expediteur
                ->setFrom('notre@adresse.fr')
                #On attribue le destinateur
                ->setTo($user->getEmail())
                #On créer le contenue
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );
            #On envoie le message dans l'E-mail de l'User
            $mailer->send($message);

            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'main' // firewall name in security.yaml
            $this->addFlash('success', 'Votre compte à bien été enregistré.');
            return $this->redirectToRoute('app_login');
            //);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'title' => 'Inscription'
        ]);
    }

    /**
     * Aller vérifier le Token de l'utilisateur
     * 
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UsersRepository $userRepo){
        #On verifie si un utilisateur a son Token
        $user = $userRepo->findOneBy(['activation_token' => $token]);

        #Si aucun Utilisateur a cet Token
        if (!$user) {
            # code...
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas!');
        }

        #On supprime le Toker si l'utilisateur est connecter
        $user->setActivationToken(null);
        
        #On Enregistre dans nos base de donnée
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        #On envoie le message Flash
        $this->addFlash('message', 'Vous avez bien activer votre compte');

        #On return à la page d'Authentification
        return $this->redirectToRoute('app_login');
    }
}
