<?php

namespace App\Repository;

use App\Entity\Typemateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typemateriel>
 *
 * @method Typemateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typemateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typemateriel[]    findAll()
 * @method Typemateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypematerielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typemateriel::class);
    }

    public function save(Typemateriel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Typemateriel $entity, bool $flush = false): void
    {
        // $this->getEntityManager()->remove($entity);

        // if ($flush) {
        //     $this->getEntityManager()->flush();
        // }


        // get rid of the ManyToMany relation with the [Category1] and [objet]
$typesmateriels= $entity->getTypemateriels();
foreach($typesmateriels as $typesmateriel) {
    $entity->removeTypemateriel($typesmateriel);
    $this->getEntityManager()->persist($typesmateriel);
}
//...

$this->getEntityManager()->remove($entity);

if ($flush) {
    $this->getEntityManager()->flush();
}

        
    }

//    /**
//     * @return Typemateriel[] Returns an array of Typemateriel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typemateriel
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
