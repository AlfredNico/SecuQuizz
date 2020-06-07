<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\Families;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use App\Repository\FamiliesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/competence")
 */
class CompetenceController extends AbstractController
{
    /**
     * @Route("/{article}/{parent}", name="competence_index", methods={"GET"})
     */
    public function index(CompetenceRepository $competenceRepository, $article, $parent): Response
    {
        return $this->render('competence/index.html.twig', [
            'competences' => $competenceRepository->findByArticle($article), 'article' => $article, 'parent' => $parent
        ]);
    }

    /**
     * @Route("/new/{article}/{parent}", name="competence_new", methods={"GET","POST"})
     */
    public function new(Request $request, $article, $parent): Response
    {
        $competence = new Competence();
        $repository = $this->getDoctrine()->getRepository(Families::class);
        $EntiteArticle = $repository->findOneBy(array('id' => $article));
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            // $EntiteArticle = FamiliesRepository->findById($article);
            $competence->setArticle($EntiteArticle);
            $entityManager->persist($competence);
            $entityManager->flush();
            return $this->redirectToRoute('competence_index', array('article' => $article, 'parent' => $parent));
        }

        return $this->render('competence/new.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
            'article' => $article,
            'parent' => $parent,
        ]);
    }

    /**
     * @Route("/{id}/{article}/{parent}", name="competence_show", methods={"GET"})
     */
    public function show(Competence $competence, $article, $parent): Response
    {
        return $this->render('competence/show.html.twig', [
            'competence' => $competence,
            'article' => $article,
            'parent' => $parent,
        ]);
    }

    /**
     * @Route("/{id}/edit/{article}/{parent}", name="competence_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Competence $competence, $article, $parent): Response
    {
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('competence_index', array('article' => $article, 'parent' => $parent));
        }

        return $this->render('competence/edit.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
            'article' => $article,
            'parent' => $parent,
        ]);
    }

    /**
     * @Route("/{id}/{article}/{parent}", name="competence_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Competence $competence, $article, $parent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $competence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($competence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('competence_index', array('article' => $article, 'parent' => $parent));
    }
}
