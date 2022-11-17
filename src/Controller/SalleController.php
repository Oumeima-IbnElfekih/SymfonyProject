<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\Membre;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/salle')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'app_salle_index', methods: ['GET'])]
    public function index(SalleRepository $salleRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $salles = $salleRepository->findAll();
        }
        else {
            $member = $this->getUser()->membre;
            $salles= $member->getSalles();
        }
        return $this->render('salle/index.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/new/{membre_id}', name: 'app_salle_new', methods: ['GET', 'POST'])]
    #[ParamConverter(
        'membre',
        class: Membre::class,
        options: ['id' => 'membre_id'],
    )]
    public function new(Request $request, SalleRepository $salleRepository,Membre $membre): Response
    {$membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $membre);
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's salle!");
                    }
        $salle = new Salle();
        $salle->setProprietaire($membre);
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salleRepository->save($salle, true);
            $this->addFlash('message', 'SALLE ajouté');
            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_show', methods: ['GET'])]
    public function show(Salle $salle): Response
    {$membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $salle->getProprietaire()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's salle!");
                    }
        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, SalleRepository $salleRepository): Response
    {$membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $salle->getProprietaire()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's salle!");
                    }
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salleRepository->save($salle, true);
            $this->addFlash('message', 'SALLE modifie');
            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, SalleRepository $salleRepository): Response
    {$membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $salle->getProprietaire()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's salle!");
                    }
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->request->get('_token'))) {
            $salleRepository->remove($salle, true);
        }
        $this->addFlash('message', 'salle suprimé');
        return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
    }
}
