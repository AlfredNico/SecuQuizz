<?php

namespace App\Controller;

use App\Entity\Families;
use App\Form\FamiliesType;
use App\Repository\FamiliesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/families")
 */
class FamiliesController extends AbstractController
{
    /**
     * @Route("/", name="families_index", methods={"GET"})
     */
    public function index(FamiliesRepository $familiesRepository): Response
    {
        // dd($familiesRepository->findAll());

        return $this->render('families/index.html.twig', [
            'families' => $familiesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="families_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $user=null): Response
    {
        $family = new Families();
        $form = $this->createForm(FamiliesType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $family->setUsers($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($family);
            $entityManager->flush();

            return $this->redirectToRoute('families_index');
        }

        return $this->render('families/new.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="families_show", methods={"GET"})
     */
    public function show(Families $family): Response
    {
        return $this->render('families/show.html.twig', [
            'family' => $family,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="families_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Families $family): Response
    {
        $form = $this->createForm(FamiliesType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('families_index');
        }

        return $this->render('families/edit.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="families_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Families $family): Response
    {
        if ($this->isCsrfTokenValid('delete'.$family->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($family);
            $entityManager->flush();
        }

        return $this->redirectToRoute('families_index');
    }
}
