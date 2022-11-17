<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\Materiel;
use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiel>
 *
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    public function save(Materiel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Materiel $entity, bool $flush = false): void
    {
       
        $hallRepository = $this->getEntityManager()->getRepository(Hall::class);

    // get rid of the ManyToMany relation with [galeries]
    $halls = $hallRepository->findMaterielHalls($entity);   
    foreach($halls as $hall) {
        $hall->removeMateriel($entity);
        $this->getEntityManager()->persist($hall);
    }
    if ($flush) {
          $this->getEntityManager()->flush();
    }



    




    }
        /**
 * @return Materiel[] Returns an array of Materiel objects
 */
 public function findMemberMateriels(Membre $member): array
 {
     return $this->createQueryBuilder('o')
          ->leftJoin('o.salle', 'i')
          ->andWhere('i.proprietaire = :member')
          ->setParameter('member', $member)
          ->getQuery()
          ->getResult()
      ;
 }
//    /**
//     * @return Materiel[] Returns an array of Materiel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Materiel
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
