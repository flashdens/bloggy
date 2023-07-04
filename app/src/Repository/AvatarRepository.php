<?php

namespace App\Repository;

use App\Entity\Avatar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avatar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avatar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avatar[]    findAll()
 * @method Avatar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarRepository extends ServiceEntityRepository
{
    /**
     * AvatarRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avatar::class);
    }

    /**
     * Saves the Avatar entity.
     *
     * @param Avatar $entity The Avatar entity
     */
    public function save(Avatar $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes the Avatar entity.
     *
     * @param Avatar $entity The Avatar entity
     */
    public function remove(Avatar $entity): void
    {
        $this->getOrCreateQueryBuilder()
            ->delete(Avatar::class, 'a')
            ->where('a.user = :userId')
            ->setParameter('userId', $entity->getUser())
            ->getQuery()
            ->execute();

        $this->getEntityManager()->flush();
    }

    // # Uncomment and update the method if needed

    // /**
    //  * Finds Avatar objects by example field.
    //  *
    //  * @param mixed $value
    //  *
    //  * @return Avatar[]
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('a.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // /**
    //  * Finds a single Avatar object by some field.
    //  *
    //  * @param mixed $value
    //  *
    //  * @return Avatar|null
    //  */
    // public function findOneBySomeField($value): ?Avatar
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
