<?php

namespace App\Controller;

use App\Entity\Salle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class SalleController extends AbstractController
{    
    
 
 /**
  * @Route("/salle", name = "salle", methods="GET")
  */
 public function indexAction()
 {
     return $this->render('salle/index.html.twig',
        [ 'welcome' => "Bienvenue SALLE" ]
    );
 }
/**
 * Lists all salles.
 *
 * @Route("/listsalle", name = "salle_list", methods="GET")
 */
public function listAction(ManagerRegistry $doctrine): Response
{
    $entityManager= $doctrine->getManager();
    $salles = $entityManager->getRepository(Salle::class)->findAll();

    dump($salles);

    return $this->render('salle/liste.html.twig',
        [ 'salles' => $salles ]
        );
}   
/**
 * Finds and displays a salle entity.
 *
 * @Route("/{id}", name="show", requirements={ "id": "\d+"}, methods="GET")
 */
public function showAction(Salle $salle): Response
{
    return $this->render('salle/show.html.twig',
    [ 'salle' => $salle ]
    );
} 
#[Route('/salle/{id}', name: 'salle_show')]
    public function show(ManagerRegistry $doctrine, $id)
{
    $salleRepository = $doctrine->getRepository(Salle::class);
$salle = $salleRepository->find($id);
if (!$salle) {
    throw $this->createNotFoundException(
        'Aucune salle pour l\'id: ' . $id
    );
}
$res="";
$nom=$salle->getNomSalle();
$res.='<p>   <a href="'.$this->generateUrl('salle_list').'">Back <a/></p>';
return new Response('<html><body>'.$res.$nom.'</body></html>');
}
}
