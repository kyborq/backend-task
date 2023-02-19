<?php

namespace App\Repository;

use App\Entity\RequestEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestEntity>
 *
 * @method RequestEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestEntity[]    findAll()
 * @method RequestEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestEntity::class);
    }

    public function save(RequestEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RequestEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllPage(int $count = 3, int $page = 1): array
    {
       return $this->createQueryBuilder('r')
           ->orderBy('r.id', 'ASC')
           ->setMaxResults($count)
           ->setFirstResult(($page - 1) * $count)
           ->getQuery()
           ->getResult()
       ;
   }
}
