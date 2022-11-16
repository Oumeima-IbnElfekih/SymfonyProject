<?php

namespace App\DataFixtures;

use App\Entity\Materiel;
use App\Entity\Typemateriel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Membre;
use App\Entity\Salle;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
class AppFixtures extends Fixture implements DependentFixtureInterface
{

    private function membersData()
    {
        // todo = [title, completed];
        yield ['Guermazi','Mohamed','med@g.com'];
        yield ['chris','chris','chris@localhost'];
        yield ['anna','anna','anna@localhost'];
        
        
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
      /**
     * Generates initialization data for materiel : [nom,description]
     * @return \\Generator
     */
    private static function typematerielDataGenerator()
    {
        yield ['fitness','aaa'];
        
    }
    public function load(ObjectManager $manager): void
    {   $membreRepo = $manager->getRepository(Membre::class);
        foreach (self::membersData() as [$name,$prenom,$useremail] ) 
        {
            $member = new Membre();
            if ($useremail) {
                $user = $manager->getRepository(User::class)->findOneByEmail($useremail);
                dump($user);
                $member->setUser($user);

            }
            $member->setNom($name);
            $member->setPrenom($prenom);
            $manager->persist($member);        
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
       // partie le load  pour typemateriel
       $typeMaterileRepo = $manager->getRepository(Typemateriel::class);
        foreach (self::typematerielDataGenerator() as [$nom,$description] ) 
        { $typemateriel= new Typemateriel();
            $typemateriel->setNom($nom);
            $typemateriel->setDescription($description);  
            $manager->persist($typemateriel);     
        }
    
       $manager->flush();


    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }


}
