<?php

namespace App\Controller;
use App\Form\UserType;
use App\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* @Security("is_granted('ROLE_USER')")

* @Route("/profil", name="profil_")
*/

class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'profile',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editProfil(Request $request, Users $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #Chiffre le mot de passe de l'utilisateur
            #On chiffre le mot de passe
            // ($request->request->get('password'));
            // $user->setPassword(
            //     $passwordEncoder->encodePassword($user, $request->request->get('password'))
            // );

            //dd($request);
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($user);
            // $entityManager->flush();

            $this->addFlash('message', 'Modification Mot de passe effectué avec succé!');
            return $this->redirectToRoute('profil_index');
        }

        return $this->render('profil/editProfil.html.twig', [
            'controller_name' => 'Modifier profile',
            'form' => $form->createView(),
        ]);
    }
}
