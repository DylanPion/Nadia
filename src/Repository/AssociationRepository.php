<?php

namespace App\Repository;

use App\Entity\Association;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Association>
 *
 * @method Association|null find($id, $lockMode = null, $lockVersion = null)
 * @method Association|null findOneBy(array $criteria, array $orderBy = null)
 * @method Association[]    findAll()
 * @method Association[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Association::class);
    }

    public function save(Association $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Association $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Association[] Returns an array of Association objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Association
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Calcul : FNI Engagé/Payé et le Remboursement Reçu par Associatoin
    public function getAssociationTotals(): array
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a.id AS id')
            ->addSelect('a.Name AS associationName')
            ->addSelect('SUM(cs.FniAmountRequested) AS totalFniRequested')
            ->addSelect('SUM(cs.PaymentOne + cs.PaymentTwo) AS totalFniPaid')
            ->addSelect('(SUM(cs.PaymentOne + cs.PaymentTwo) - SUM(cs.remainsToBeReceived)) AS totalRepaidToDate')
            ->leftJoin('a.Company', 'cs')
            ->groupBy('a.id');

        return $qb->getQuery()->getResult();
    }
}
