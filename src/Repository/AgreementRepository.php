<?php

namespace App\Repository;

use App\Entity\Agreement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agreement>
 *
 * @method Agreement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agreement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agreement[]    findAll()
 * @method Agreement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgreementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agreement::class);
    }

    public function save(Agreement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Agreement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Agreement[] Returns an array of Agreement objects
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

    //    public function findOneBySomeField($value): ?Agreement
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Retourne le nombre de ligne de l'entité
    public function agreementLineNumber()
    {
        $qb = $this->createQueryBuilder('agreement');
        $qb->select('COUNT(agreement.id)');
        $result = $qb->getQuery()->getSingleScalarResult();

        return $result;
    }

    public function idValueAgreement()
    {
        $qb = $this->createQueryBuilder('agreement');
        $qb->select('agreement.id');
        $results = $qb->getQuery()->getResult();

        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result['id'];
        }
        return $ids;
    }
}
