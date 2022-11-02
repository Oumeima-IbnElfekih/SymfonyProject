<?php

namespace App\Controller;
use App\Entity\Materiel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
class MaterielController extends AbstractController
{
    #[Route('/materiel', name: 'materiel')]
    public function index(): Response
    {
        return $this->render('materiel/index.html.twig', [
            'welcome' => "Bienvenue Materiel",
        ]);
    }
    /**
 * Lists all salles.
 *
 * @Route("/listmateriel", name = "materiel_list", methods="GET")
 */
public function listAction(ManagerRegistry $doctrine): Response
{
    $entityManager= $doctrine->getManager();
    $materiels = $entityManager->getRepository(Materiel::class)->findAll();

    dump($materiels);

    return $this->render('materiel/liste.html.twig',
        [ 'materiels' => $materiels ]
        );
} 
/**
 * Finds and displays a salle entity.
 *
 * @Route("/{id}", name="showm", requirements={ "id": "\d+"}, methods="GET")
 */
public function showAction(Materiel $materiel): Response
{
    return $this->render('materiel/show.html.twig',
    [ 'materiel' => $materiel ]
    );
} 
#[Route('/materiel/{id}', name: 'materiel_show')]
    public function show(ManagerRegistry $doctrine, $id)
{
    $materielRepository = $doctrine->getRepository(Materiel::class);
$materiel = $materielRepository->find($id);
if (!$materiel) {
    throw $this->createNotFoundException(
        'Aucune materiel pour l\'id: ' . $id
    );
}
$res="";
$nom=$materiel->getNom();
$res.='<p>   <a href="'.$this->generateUrl('materiel_list').'">Back <a/></p>';
return new Response('<html><body>'.$res.$nom.'</body></html>');
}  
}
