<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Families;
use App\Form\FamiliesType;
use Doctrine\ORM\Mapping\Id;
use App\Form\ChoixniveauType;
use App\Form\FamiliesActivationType;
use App\Form\FamiliesValidationType;
use App\Form\FamiliesAffectationType;
use App\Repository\FamiliesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'families' => $familiesRepository->findby(array('niveau' => $niveau)), 'familis' => null
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

    // /**
    //  * @Route("/{id}/{parent2}/Niveau", name="families_niveau", methods={"GET"})
    //  */
    // public function niveau2(FamiliesRepository $familiesRepository, $id, $parent2): Response
    // {
    //     // dd($familiesRepository->findAll());

    //     return $this->render('families/index.html.twig', [
    //         'families' => $familiesRepository->findby(array('parent' => $id)), 'familis' => $familiesRepository->findby(array('id' => $id, 'parent2'))
    //     ]);
    // }

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
            $family->setEtat(false);
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
            $family->setEtat(false);
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
            return $this->redirectToRoute('families_index', array('niveau' => '1'));
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
     * @Route("/{id}/show/{min}", name="families_show", methods={"GET"})
     */
    public function show(Families $family, $min): Response
    {
        return $this->render('families/show.html.twig', [
            'family' => $family,
            'min' => $min,
        ]);
    }

    /**
     * @Route("/{id}/activation/{min}", name="families_activation", methods={"GET","POST"})
     */
    public function activation(Request $request, Families $family, $min): Response
    {
        $form = $this->createForm(FamiliesActivationType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($family->getParent() != null) {
                $article = $family->getParent()->getId();
                return $this->redirectToRoute('families_niveau', array('id' => $article));
            } else {
                // $article = $family->getParent()->getId();
                return $this->redirectToRoute('families_index', array('niveau' => $min));
            }
        }

        return $this->render('families/activation.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/{min}", name="families_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Families $family, $min): Response
    {
        $form = $this->createForm(FamiliesType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($family->getParent() != null) {
                $article = $family->getParent()->getId();
                return $this->redirectToRoute('families_niveau', array('id' => $article));
            } else {
                // $article = $family->getParent()->getId();
                return $this->redirectToRoute('families_index', array('niveau' => $min));
            }
        }

        return $this->render('families/edit.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/affectation/{min}", name="families_affectation_user", methods={"GET","POST"})
     */
    public function affectation(Request $request, Families $family, $min): Response
    {
        $form = $this->createForm(FamiliesAffectationType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($family->getParent() != null) {
                $article = $family->getParent()->getId();
                return $this->redirectToRoute('families_niveau', array('id' => $article));
            } else {
                // $article = $family->getParent()->getId();
                return $this->redirectToRoute('families_index', array('niveau' => $min));
            }
        }

        return $this->render('families/affectation.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/{min}", name="families_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Families $family, $min): Response
    {

        if ($this->isCsrfTokenValid('delete' . $family->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($family);
            $entityManager->flush();
        }
        // $article = $family->getParent()->getId();
        // return $this->redirectToRoute('families_niveau', array('id' => $article));
        if ($family->getParent() != null) {
            $article = $family->getParent()->getId();
            return $this->redirectToRoute('families_niveau', array('id' => $article));
        } else {
            // $article = $family->getParent()->getId();
            return $this->redirectToRoute('families_index', array('niveau' => $min));
        }
    }
}
