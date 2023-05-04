<?php

namespace App\Repository;

use App\Entity\TotalAmountRepaidToDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TotalAmountRepaidToDate>
 *
 * @method TotalAmountRepaidToDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method TotalAmountRepaidToDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method TotalAmountRepaidToDate[]    findAll()
 * @method TotalAmountRepaidToDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TotalAmountRepaidToDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TotalAmountRepaidToDate::class);
    }

    public function save(TotalAmountRepaidToDate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TotalAmountRepaidToDate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return TotalAmountRepaidToDate[] Returns an array of TotalAmountRepaidToDate objects
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

    //    public function findOneBySomeField($value): ?TotalAmountRepaidToDate
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    // Fonction pour récupérer les lignes du tableau TotalAmountRepaidToDate en fonction de l'id de l'URL 
    public function getTotalAmountRepaidToDateById($id)
    {
        $qb = $this->createQueryBuilder('TotalAmountRepaidToDate')
            ->select('TotalAmountRepaidToDate')
            ->where('TotalAmountRepaidToDate.CompanySheet = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }

    // Fonction pour calculer la somme des Paiement Reçu par Fiche Société
    public function getTotalPaymentReceivedByCompany($CompanySheet)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.Payment) as TotalPaymentByCompany')
            ->where('cs.CompanySheet = :CompanySheet')
            ->setParameter('CompanySheet', $CompanySheet);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
