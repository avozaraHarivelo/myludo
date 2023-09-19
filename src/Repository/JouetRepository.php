<?php

namespace App\Repository;

use App\Entity\Jouet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jouet>
 *
 * @method Jouet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jouet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jouet[]    findAll()
 * @method Jouet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JouetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jouet::class);
    }

    public function save(Jouet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Jouet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Jouet[] Returns an array of Jouet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findOneById($jouetId): ?Jouet
   {
       return $this->createQueryBuilder('j')
           ->andWhere('j.id = :val')
           ->setParameter('val', $jouetId)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

//    public function listOurJouet($client)
//    {
//        return $this->createQueryBuilder('j')
//            ->join('j.prets', 'p')
//            ->where(':client = p.user')
//            ->setParameter('client', $client)
//            ->getQuery()
//            ->getResult();
//    }

   public function listAllJouetsByIds(array $jouetsId)
   {
       return $this->createQueryBuilder('j')
           ->where('j.id IN (:jouets)')
           ->setParameter('jouets', array_values($jouetsId))
           ->getQuery()
           ->getResult();
   }
}
