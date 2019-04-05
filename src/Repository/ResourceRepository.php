<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use App\Entity\ResourceSearch;


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
        ->setMaxResults(12)
        ->getQuery()
        ->getResult()
        ;
        
    }
    
    /**
     * @return Query
     */
    public function findAllVisibleQuery(ResourceSearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        
        if ($search->getTitre()) {
            $query
                ->where('r.title like :title')
                ->setParameter('title', '%'.$search->getTitre(). '%');
        }
        
        if ($search->getAuteur()) {
            $query
                ->andWhere('r.auteur like :auteur')
                ->setParameter('auteur', '%'.$search->getAuteur().'%');
        }
        
        if ($search->getType()) {
            $query
                 ->andWhere('r.type like :type')
                 ->setParameter('type', '%'.$search->getType().'%');
        }
        
        if ($search->getPersons()->count() > 0) {
            $k = 0;
            foreach ($search->getPersons() as $k => $person) {
                $k++;
                $query = $query
                    ->andWhere(":person$k MEMBER OF r.persons")
                    ->setParameter("person$k", $person)
                    ;
            }
            
        }
            
        return $query->getQuery()
        ;
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    public function compteById($id)
    {
        return $this->createQueryBuilder('r')
        ->select('COUNT(r.id)')
        ->Where('r.id = :val')
        ->setParameter('val', $id)
        ->getQuery()
        ->getSingleScalarResult()
        ;
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    public function compteByIdcadic($idcadic)
    {
        return $this->createQueryBuilder('r')
        ->select('COUNT(r.id)')
        ->Where('r.idcadic like :val')
        ->setParameter('val', $idcadic)
        ->getQuery()
        ->getSingleScalarResult()
        ;
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    public function findById($id)
    {
        return $this->createQueryBuilder('r')
        ->select()
        ->Where('r.id = :val')
        ->setParameter('val', $id)
        ->getQuery()
        //->getResult()
        ->getOneOrNullResult()
        ;
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    public function findByIdcadic($idcadic)
    {
        return $this->createQueryBuilder('r')
        ->select()
        ->Where('r.idcadic = :val')
        ->setParameter('val', $idcadic)
        ->getQuery()
        ->getResult()
        //->getOneOrNullResult()
        ;
    }
    
    
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
        ->Where('r.analyse = true')
        ;
    }
    
    
}
