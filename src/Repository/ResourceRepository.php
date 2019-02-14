<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Resource::class);
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    
    public function findAllOai(): array
    {
        return $this->findVisibleQuery()
            ->Where('r.analyse = true')
            ->getQuery()
            ->getResult()
            ;
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    public function findLast(): array
    {
        return $this->findVisibleQuery()
        ->Where('r.analyse = true')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult()
        ;
        
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
        ->Where('r.analyse = true')
        ;
    }
    
}
