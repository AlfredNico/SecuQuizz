<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Compteur;
use App\Entity\Questions;
use App\Form\AnswersType;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/questions")
 */
class QuestionsController extends AbstractController
{
    /**
     * @Route("/", name="questions_index", methods={"GET"})
     */
    public function index(QuestionsRepository $questionsRepository): Response
    {
        return $this->render('questions/index.html.twig', [
            'questions' => $questionsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="questions_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, $id): Response
    {
        // $question = new Questions();
        // $form = $this->createForm(QuestionsType::class, $question);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($question);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('questions_index');
        // }

        // return $this->render('questions/new.html.twig', [
        //     'question' => $question,
        //     'form' => $form->createView(),
        // ]);
        $repository = $this->getDoctrine()->getRepository(Compteur::class);
        // $repository1 = $this->getDoctrine()->getRepository(Produit::class);
        $compteur = $repository->find(1);
        $numc = $compteur->getNumcom();
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);
        $reponse = new Answers();
        $f = $this->createForm(AnswersType::class, $reponse);
        $f->handleRequest($request);

        $session = $request->getSession();


        if (!$session->has('question')) {
            $session->set('question', array());
        }
        $choix = "";
        $Tabcomm = $session->get('question', []);


        if ($form->isSubmitted() || $f->isSubmitted()) {
            $choix = $request->get('bt');

            if ($choix == 'Valider') {
                $em = $this->getDoctrine()->getManager();
                $question->setNumc($numc);
                $question->setEtat('En attente de validation');
                $question->setUsers($this->getUser());

                $pj = $form->get('pj')->getData();
                var_dump($pj);

                if ($pj) {
                    $originalFilename = pathinfo($pj->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pj->guessExtension();

                    try {
                        $pj->move(
                            $this->getParameter('pj_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $question->setAttached($newFilename);
                }

                $lig = sizeof($Tabcomm);
                for ($i = 1; $i <= $lig; $i++) {
                    $reponse = new Answers();
                    $reponse->setQuestions($question);
                    // $prod = $repository1->findOneBy(array('id' => $Tabcomm[$i]->getProduit()));
                    // $reponse->setProduit($prod);
                    $reponse->setLig($i);
                    $reponse->setNumc($Tabcomm[$i]->getNumc());
                    $reponse->setTitle($Tabcomm[$i]->getTitle());
                    $reponse->setIsAnswer($Tabcomm[$i]->getIsAnswer());
                    $reponse->setIsAnswer($Tabcomm[$i]->getIsAnswer());
                    $em->persist($reponse);
                    $em->flush();
                }
                // $prod = $repository1->findOneBy(array('id' => $Tabcomm[$i]->getProduit()));
                // $reponse->setProduit($prod);





                $em->persist($question);

                $compteur->setNumcom($numc + 1);
                $em->persist($compteur);
                $em->flush();


                $session->clear();

                return $this->redirectToRoute('questions_index');
            } else if ($choix == "Add") {
                $lig = sizeof($Tabcomm) + 1;
                $reponse->setNumc($numc);
                $reponse->setLig($lig);
                $Tabcomm[$lig] = $reponse;
                $session->set('question', $Tabcomm);
            }

            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($question);
            // $entityManager->flush();

            // return $this->redirectToRoute('questions_index');
        }

        return $this->render('questions/new.html.twig', [
            'question' => $question,
            'reponse' => $reponse,
            'numc' => $numc,
            'lcomm' => $Tabcomm,
            'form' => $form->createView(),
            'f' => $f->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questions_show", methods={"GET"})
     */
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="questions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Questions $question): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('questions_index');
        }

        return $this->render('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questions_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Questions $question): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('questions_index');
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer($id, Request $request)
    {
        $session = $request->getSession();
        $Tabcomm = $session->get('question', []);
        if (array_key_exists($id, $Tabcomm)) {
            unset($Tabcomm[$id]);
            $session->set('question', $Tabcomm);
        }
        return $this->redirectToRoute('questions_new');
    }
}
