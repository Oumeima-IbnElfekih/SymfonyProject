<?php

namespace App\DataFixtures;

use App\Entity\Materiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Membre;
use App\Entity\Salle;

class AppFixtures extends Fixture
{
    private function membersData()
    {
        // todo = [title, completed];
        yield ['Guermazi','Mohamed'];
       
        
    }
    /**
     * Generates initialization data for salle : [NomSalle,NomMembre,Prenom]
     * @return \\Generator
     */
    private static function salleDataGenerator()
    {
        yield ["Romance",'Guermazi','Mohamed'];
        yield ["gymbox",'Guermazi','Mohamed'];
        
    }
      /**
     * Generates initialization data for materiel : [Nom,Marque,nomsalle]
     * @return \\Generator
     */
    private static function materielDataGenerator()
    {
        yield ['altere','nike',"Romance"];
        
    }

    public function load(ObjectManager $manager): void
    {   $membreRepo = $manager->getRepository(Membre::class);
        foreach (self::membersData() as [$nom,$prenom] ) 
        {
            $membre = new Membre();
            $membre->setNom($nom);
            $membre->setPrenom($prenom);
            $manager->persist($membre);          
        }
        $manager->flush();
        // partie le load  pour Salle
        $membreRepo = $manager->getRepository(Membre::class);
        foreach (self::salleDataGenerator() as [$nom,$nomp,$prenom] ) 
        {
            $membre = $membreRepo->findOneBy(['Nom' => $nomp, 'prenom' => $prenom]);
            $salle= new Salle();
            $salle->setNomSalle($nom);
            
            $membre->addSalle($salle);
            $manager->persist($membre);          
        }
    
       $manager->flush();

       // partie le load  pour materiel
       $MaterileRepo = $manager->getRepository(Salle::class);
        foreach (self::materielDataGenerator() as [$nom,$marque,$nomsalle] ) 
        {
            $salle = $MaterileRepo->findOneBy(['nomsalle' => $nomsalle ]);
            $materiel= new Materiel();
            $materiel->setNom($nom);
            $materiel->setMarque($marque);
            $salle->addMateriel($materiel);
            $manager->persist($salle);          
        }
    
       $manager->flush();

    }
}
