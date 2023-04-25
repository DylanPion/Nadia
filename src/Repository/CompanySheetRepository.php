<?php

namespace App\Repository;

use App\Entity\CompanySheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\GroupBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanySheet>
 *
 * @method CompanySheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanySheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanySheet[]    findAll()
 * @method CompanySheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanySheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanySheet::class);
    }

    public function save(CompanySheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompanySheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return CompanySheet[] Returns an array of CompanySheet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CompanySheet
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Fonction pour calculer le Montant Total FNI Engagé par convention 
    public function getTotalAmountRequestedByAgreement($agreement)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.FniAmountRequested) as TotalAmountRequestedByAgreement')
            ->where('cs.Agreement = :Agreement')
            ->setParameter('Agreement', $agreement);

        return $qb->getQuery()->getResult(); // GetSingleScalarResultat retourne une ligne et non un array comme le fait getResult()
    }

    // Fonction pour calculer le Montant Total des Payment One versé par convention 
    public function getTotalAmountPaymentOneByAgreement($agreement)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.PaymentOne) as TotalAmountPaymentOneByAgreement')
            ->where('cs.Agreement = :Agreement')
            ->setParameter('Agreement', $agreement);

        return $qb->getQuery()->getResult();
    }

    // Fonction pour calculer le Montant Total des Payment Two versé par convention 
    public function getTotalAmountPaymentTwoByAgreement($agreement)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.PaymentTwo) as TotalAmountPaymentTwoByAgreement')
            ->where('cs.Agreement = :Agreement')
            ->setParameter('Agreement', $agreement);

        return $qb->getQuery()->getResult();
    }

    // Fonction Total FNI Versé par Convention = Paymenet One + Payment Two
    public function getTotalAmountFNIPaidByAgreement($agreement)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.PaymentOne + cs.PaymentTwo) as TotalAmountFNIPaidByAgreement')
            ->where('cs.Agreement = :Agreement')
            ->setParameter('Agreement', $agreement);

        return $qb->getQuery()->getResult();
    }

    public function getTotalAmountRepaidByAgreement($agreement)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.PaymentOne + cs.PaymentTwo - cs.remainsToBeReceived) as TotalAmountRepaidByAgreement')
            ->where('cs.Agreement = :Agreement')
            ->setParameter('Agreement', $agreement);

        return $qb->getQuery()->getSingleScalarResult();
    }


    // Fonction pour calculer le Montant Total FNI Versé à une Association pour ses sociétés
    public function getTotalAmountPaidByAssociation($associationId)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.PaymentOne + cs.PaymentTwo) AS TotalAmountPaymentsByAssociation')
            ->where('cs.association = :association')
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getSingleScalarResult();
    }

    // Fonction pour calculer le Montant Total FNI Engagé par une Association pour ses sociétés 
    public function getTotalAmountRequestedByAssociation($associationId)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.FniAmountRequested) AS TotalAmountRequestedByAssociation')
            ->where('cs.association = :association')
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getSingleScalarResult();
    }

    // Fonction pour calculer le  Total FNI des remboursement à ce jour d'une Association pour ses sociétés
    public function getTotalAmountReceivedByAssociation($associationId)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.remainsToBeReceived) AS remainsToBeReceived')
            ->where('cs.association = :association')
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
