<?php

namespace App\Controller;
use App\Form\UserType;
use App\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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
    public function editProfil(Request $request, Users $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('profil_index');
        }

        return $this->render('profil/editProfil.html.twig', [
            'controller_name' => 'Modifier profile',
            'form' => $form->createView(),
        ]);
    }
}
