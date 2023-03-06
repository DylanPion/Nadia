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

    // Fonction pour calculer le Total FNI versé ainsi que le Total FNI engagé
    public function getTotalFni()
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.FniAmountPaid) AS TotalFniAmountPaid', 'SUM(cs.FniAmountRequested) AS TotalFniAmountRequested')
            ->groupBy('cs.association');

        return $qb->getQuery()->getResult();
    }

    // Fonction pour calculer le Montant Total FNI Engagé par convention 
    public function getTotalAmountRequestedByAgreementNumber($agreementNumber)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.FniAmountRequested) as TotalAmountRequestedByAgreementNumber')
            ->where('cs.AgreementNumber = :AgreementNumber')
            ->setParameter('AgreementNumber', $agreementNumber);

        return $qb->getQuery()->getResult(); // GetSingleScalarResultat retourne une ligne et non un array comme le fait getResult()
    }

    // Fonction pour calculer le Montant Total FNI Versé par convention 
    public function getTotalAmountPaidByAgreementNumber($agreementNumber)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.FniAmountPaid) as TotalAmountPaidByAgreementNumber')
            ->where('cs.AgreementNumber = :AgreementNumber')
            ->setParameter('AgreementNumber', $agreementNumber);

        return $qb->getQuery()->getResult();
    }

    // Fonction pour calculer le Montant Engagé et Non Versé aux associations. 
    public function getTotalAmountRepaidToDateByAgreementNumber($agreementNumber)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.TotalAmountRepaidToDate) as TotalAmountRepaidToDateByAgreementNumber')
            ->where('cs.AgreementNumber = :AgreementNumber')
            ->setParameter('AgreementNumber', $agreementNumber);

        return $qb->getQuery()->getResult();
    }
}
