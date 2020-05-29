<?php

namespace App\Controller;

use App\Entity\Families;
use App\Entity\Niveau;
use App\Form\FamiliesType;
use App\Form\ChoixniveauType;
use App\Repository\FamiliesRepository;
use Doctrine\ORM\Mapping\Id;
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
     * @Route("/{niveau}", name="families_index", methods={"GET"})
     */
    public function index(FamiliesRepository $familiesRepository, $niveau): Response
    {
        // dd($familiesRepository->findAll());

        return $this->render('families/index.html.twig', [
            'families' => $familiesRepository->findby(array('niveau' => $niveau)),
        ]);
    }

    /**
     * @Route("/{id}/Niveau", name="families_niveau", methods={"GET"})
     */
    public function niveau(FamiliesRepository $familiesRepository, $id): Response
    {
        // dd($familiesRepository->findAll());

        return $this->render('families/index.html.twig', [
            'families' => $familiesRepository->findby(array('parent' => $id)), 'familis' => $familiesRepository->findby(array('id' => $id))
        ]);
    }

    /**
     * @Route("/new/{niveau}/{parent}", name="families_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $user = null, Niveau $niveau, Families $parent): Response
    {
        $family = new Families();

        // $repository = $this->getDoctrine()->getRepository(Families::class);

        $form = $this->createForm(FamiliesType::class, $family);
        $form->handleRequest($request);

        // $session = $request->getSession();

        // if (!$session->has('family')) {
        //     $session->set('family', array());
        // }
        // $Tabcomm = $session->get('family', []);

        if ($form->isSubmitted() && $form->isValid()) {
            $family->setUsers($user);
            $em = $this->getDoctrine()->getManager();
            $fam = $em->getRepository(Families::class)->find($parent);

            $family->setParent($parent);
            $family->setNiveau($niveau);
            // for ($i = 1; $i <= 2; $i++) {
            //     $prod = $repository->findOneBy(array('id' => $Tabcomm[$i]->getFamilies()));
            //     $family->setParent($prod);
            // }

            // $repository = $em->getRepository('AppBundle:Piece');
            // $RechPieces = $repository->FindAllDetailsPieces($data);
            // var_dump($RechPieces);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($family);
            $entityManager->flush();
            // $article = $family->getParent()->getId();
            return $this->redirectToRoute('families_niveau', array('id' => $fam->getId()));
        }

        return $this->render('families/new.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/{niveau}", name="families_new_niveau1", methods={"GET","POST"})
     */
    public function newNiveau1(Request $request, UserInterface $user = null, Niveau $niveau): Response
    {
        $family = new Families();

        // $repository = $this->getDoctrine()->getRepository(Families::class);

        $form = $this->createForm(FamiliesType::class, $family);
        $form->handleRequest($request);

        // $session = $request->getSession();

        // if (!$session->has('family')) {
        //     $session->set('family', array());
        // }
        // $Tabcomm = $session->get('family', []);

        if ($form->isSubmitted() && $form->isValid()) {
            $family->setUsers($user);
            $family->setNiveau($niveau);
            // for ($i = 1; $i <= 2; $i++) {
            //     $prod = $repository->findOneBy(array('id' => $Tabcomm[$i]->getFamilies()));
            //     $family->setParent($prod);
            // }

            // $repository = $em->getRepository('AppBundle:Piece');
            // $RechPieces = $repository->FindAllDetailsPieces($data);
            // var_dump($RechPieces);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($family);
            $entityManager->flush();
            // $article = $family->getParent()->getId();
            return $this->redirectToRoute('families_niveau', array('id' => '1'));
        }

        return $this->render('families/new.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/choixNiveau", name="families_choix_niveau", methods={"GET","POST"})
     */
    public function choixNiveau(Request $request, UserInterface $user = null): Response
    {
        $family = new Families();
        $form = $this->createForm(ChoixniveauType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $family->setUsers($user);
            $entityManager = $this->getDoctrine()->getManager();

            return $this->redirectToRoute('families_new');
        }

        return $this->render('families/new.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="families_show", methods={"GET"})
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
            $article = $family->getParent()->getId();
            return $this->redirectToRoute('families_niveau', array('id' => $article));
        }

        return $this->render('families/edit.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="families_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Families $family): Response
    {
        $article = $family->getParent()->getId();
        if ($this->isCsrfTokenValid('delete' . $family->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($family);
            $entityManager->flush();
        }
        return $this->redirectToRoute('families_niveau', array('id' => $article));
    }
}
