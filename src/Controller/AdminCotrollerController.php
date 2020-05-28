<?php

namespace App\Controller;
use App\Entity\Users;
use App\Form\EditUserType;
use App\Form\EditUserType2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin", name="admin_")
 */
class AdminCotrollerController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Administrateur',
        ]);
    }

    /**
     * Listes des utilisateur
     * 
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function usersList(UsersRepository $users){
        return $this->render('admin/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * Edier un Utilisateur
     * 
     * @Route("/utilisateur/Editer/{id}", name="modifier_utilisateur")
     */
    public function EditUser(Users $user, Request $request){
        $form = $this->createForm(EditUserType::class, $user);
        //Analyser les requete apres la validation du champs
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            dump($request);

            $this->addFlash('message', 'Utilisatuer modifié avec succès!');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * Edier un Utilisateur
     * 
     * @Route("/utilisateur/EditerAdmin/{id}", name="modifier_admin_user")
     */
    public function EditUser2(Users $user, Request $request){
        $form = $this->createForm(EditUserType2::class, $user);
        //Analyser les requete apres la validation du champs
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            dump($request);

            $this->addFlash('message', 'Utilisatuer modifié avec succès!');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
