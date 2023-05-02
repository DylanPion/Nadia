<?php

namespace App\Repository;

use App\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Weather>
 *
 * @method Weather|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weather|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weather[]    findAll()
 * @method Weather[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weather::class);
    }

    public function save(Weather $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Weather $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Weather[] Returns an array of Weather objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Weather
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Fonction pour calculer le Total du Montant de la Casse par Société
    public function getTotalamountOfDamageByCompany($CompanySheet)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.amountOfDamage) as TotalamountOfDamage')
            ->where('cs.CompanySheet = :CompanySheet')
            ->setParameter('CompanySheet', $CompanySheet);

        return $qb->getQuery()->getSingleScalarResult();
    }

    // Fonction pour calculer le Total du Montant Provision Comptable
    public function getTotalamountOfAccountingProvision($CompanySheet)
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('SUM(cs.amountOfAccountingProvision) as TotalamountOfAccountingProvision')
            ->where('cs.CompanySheet = :CompanySheet')
            ->setParameter('CompanySheet', $CompanySheet);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
