<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Materiel;
use App\Form\HallType;
use App\Repository\HallRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[Route('/hall')]
#[IsGranted("IS_AUTHENTICATED_FULLY")]
class HallController extends AbstractController
{
    #[Route('/', name: 'app_hall_index', methods: ['GET'])]
    public function index(HallRepository $hallRepository): Response
    {
        $privatehalls = array();
       
        $user = $this->getUser();
        dump($user->membre);
        if($user) {
         
            $member = $user->membre;
            $privatehalls = $hallRepository->findBy(
        [
              'publie' => false,
              'membre' => $member
        ]);
}
if ($this->isGranted('ROLE_ADMIN')) {
    $privatehalls = $hallRepository->findAll();
}

        return $this->render('hall/index.html.twig', [
            'halls' => $privatehalls,
        ]);
    }

    #[Route('/new', name: 'app_hall_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HallRepository $hallRepository): Response
    {  
        $hall = new Hall();
        $form = $this->createForm(HallType::class, $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hallRepository->save($hall, true);
        
            $this->addFlash('message', 'Hall ajouté');
            var_dump($hall->getId());
            return $this->redirectToRoute('app_hall_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hall/new.html.twig', [
            'hall' => $hall,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hall_show', methods: ['GET'])]
    public function show(Hall $hall): Response
    {   $membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $hall->getMembre()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's hall!");
                    }


        return $this->render('hall/show.html.twig', [
            'hall' => $hall,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_hall_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hall $hall, HallRepository $hallRepository): Response
    {    



                    $hasAccess = false;
                    if($this->isGranted('ROLE_ADMIN') || $hall->isPublie()) {
                        $hasAccess = true;
                    }
                    else {
                        $user = $this->getUser();
                        if( $user ) {
                            
                            if ( $user &&  ($user == $hall->getMembre()->getUser()) ) {
                                $hasAccess = true;
                            }
                        }
                    }
                    if(! $hasAccess) {
                        throw $this->createAccessDeniedException("You cannot access the requested resource!");
                    }


        $form = $this->createForm(HallType::class, $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hallRepository->save($hall, true);
            $this->addFlash('message', 'Hall modifié');
            return $this->redirectToRoute('app_hall_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hall/edit.html.twig', [
            'hall' => $hall,
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_hall_delete', methods: ['POST'])]
    public function delete(Request $request, Hall $hall, HallRepository $hallRepository): Response
    {    $membreUser =$this->getUser();
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($membreUser== $hall->getMembre()->getUser());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's hall!");
                    }
        if ($this->isCsrfTokenValid('delete'.$hall->getId(), $request->request->get('_token'))) {
            $hallRepository->remove($hall, true);
        }
        $this->addFlash('message', 'Hall suprime');

        return $this->redirectToRoute('app_hall_index', [], Response::HTTP_SEE_OTHER);
    }

#[Route('/{hall_id}/materiel/{materiel_id}', name: 'app_hall_materiel_show', methods: ['GET'])]
#[ParamConverter(
    'hall',
    class: Hall::class,
    options: ['id' => 'hall_id'],
)]
#[ParamConverter(
    'materiel',
    class: Materiel::class,
    options: ['id' => 'materiel_id'],
)]
public function materielShow(Hall $hall, Materiel $materiel): Response
{ 
    $hasAccess = false;
    if($this->isGranted('ROLE_ADMIN') || $hall->isPublie()) {
        $hasAccess = true;
    }
    else {
        $user = $this->getUser();
        if( $user ) {
            
            if ( $user &&  ($user == $hall->getMembre()->getUser()) ) {
                $hasAccess = true;
            }
        }
    }
    if(! $hasAccess) {
        throw $this->createAccessDeniedException("You cannot access the requested resource!");
    }
    if(! $hall->getMateriels()->contains($materiel)) {
        throw $this->createNotFoundException("Couldn't find such a materiel in this hall !");
    }

    if(! $hall->isPublie()) {
        throw $this->createAccessDeniedException("You cannot access the requested ressource!");
    }
    return $this->render('hall/materiel_show.html.twig', [
        'materiel' => $materiel,
        'hall' => $hall
    ]);
}
}
