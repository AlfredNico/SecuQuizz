<?php

namespace App\Controller;
use App\Entity\Questions;
use App\Form\QuestionType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 * 
 * @Route("/questions", name="questions_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(UserInterface $user=null)
    {
        //$user = new UserInterface();
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findAll();
        //dd($userId);
        //dd($user->getId());

        return $this->render('question/index.html.twig', [
            'controller_name' => 'Listes des questions',
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/new", name="question_new")
     */
    public function new(Request $request, UserInterface $user = null): Response
    {
        $questions = new Questions();
        $form = $this->createForm(QuestionType::class, $questions);
        $form->handleRequest($request);
        //$questions->getUsers($user->getId());

        if($form->isSubmitted() && $form->isValid()){
            //$userId = new User
            //On recupere les images transmises;
            $images = $form->get('images')->getData();
            // $fichier = 'a.jpg';
            if($images) {
                foreach ($images as $image) {
                    # code... 
                    //recuperer l'extension d'un image
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();  
                    //Copier le fichier dans notre reperitoire pour le stocker
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    ); 
                    
                    //On stocke l'image dans la base de données (de son nom)
                    $questions->setAttached($fichier);
                }
            }
            
            $questions->setUsers($user);
        
            dd($questions);
            // $entityManager =$this->getDoctrine()->getManager();
            // $entityManager->persist($questions);
            // $entityManager->flush();

            return redirectToRoute('question_list');
        }

        return $this->render('question/new.html.twig', [
            'questionForm' => $form->createView()
        ]);
    }
}
