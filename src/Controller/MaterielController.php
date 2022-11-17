<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/materiel')]
class MaterielController extends AbstractController
{
    #[Route('/', name: 'app_materiel_index', methods: ['GET'])]
    public function index(MaterielRepository $materielRepository): Response
    {    if ($this->isGranted('ROLE_ADMIN')) {
        $materiels =$materielRepository->findAll();
    }
    else {
        $member = $this->getUser()->membre;
        $materiels =$materielRepository->findMemberMateriels($member);
    }
        return $this->render('materiel/index.html.twig', [
            'materiels' => $materiels,
        ]);
    }

    #[Route('/new', name: 'app_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MaterielRepository $materielRepository): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materielRepository->save($materiel, true);
            $this->addFlash('message', 'materiel ajouté');
            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_show', methods: ['GET'])]
    public function show(Materiel $materiel): Response
    { $membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $materiel->getSalle()->getProprietaire()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's materiel!");
                    }
        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, MaterielRepository $materielRepository): Response
    {$membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $materiel->getSalle()->getProprietaire()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's materiel!");
                    }
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materielRepository->save($materiel, true);
            $this->addFlash('message', 'materiel modifie');
            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, MaterielRepository $materielRepository): Response
    {$membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $materiel->getSalle()->getProprietaire()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's materiel!");
                    }
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $materielRepository->remove($materiel, true);
        }
        $this->addFlash('message', 'materiel suprime');
        return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
    }
}
