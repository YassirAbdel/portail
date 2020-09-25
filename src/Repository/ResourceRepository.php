<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use App\Entity\ResourceSearch;
use App\Model\ResourceModel;
use Elastica\Query\BoolQuery;
use Elastica\Query\Wildcard;
use Elastica\Query\Match;
//use Elastica\QueryBuilder\DSL\Query as ElasticaQuery;
#use Elastica\Rescore\Query;
use FOS\ElasticaBundle\Repository;
use Elastica\Query\MultiMatch;
use Elastica\Query as ElasticaQuery;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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
    
    public function findFront(): array
    {
        return $this->findVisibleQuery()
            ->Where('r.front = true')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return Resource[] Returns an array of Resource objects
     */
    
    public function findFolderFront(): array
    {
        return $this->findVisibleQuery()
            ->Where('r.folderFront = true')
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
            ->Where('r.front = false')
            ->Where('r.folderFront = false')
            //->orWhere('r.folderFront is null')
            ->addOrderBy('r.updated_at', 'DESC')
            ->setMaxResults(18)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Resource[] Returns an array of Resource objects
     */
    public function findAdminLast(): array
    {
        return $this->findVisibleQuery()
        ->addOrderBy('r.updated_at', 'DESC')
        ->setMaxResults(30)
        ->getQuery()
        ->getResult()
        ;
    }

    /**
     * @return Resource[] Returns an array of Resource objects
     */

     public function searchFullElastic ($q, $field, $facet, TransformedFinder  $resourcesFinder, Request $request)
     {
         $boolQuery = new BoolQuery();
         $boolTermQuery = new BoolQuery();
        
         $termAllIndex= new Wildcard();
         $termAllIndex->setParams(['allIndex' => $q]);
         $boolTermQuery->addMust($termAllIndex);

         $termTitle= new Wildcard();
         $termTitle->setParams(['title' => $q]);
         $boolTermQuery->addShould($termTitle);

         $termAuteur= new Wildcard();
         $termAuteur->setParams(['title' => $q]);
         $boolTermQuery->addShould($termAuteur);

         $termTag= new Wildcard();
         $termTag->setParams(['tag' => $q]);
         $boolTermQuery->addShould($termTag);

         $termOeuvre= new Wildcard();
         $termOeuvre->setParams(['oeuvre' => $q]);
         $boolTermQuery->addShould($termOeuvre);

         $termPerson= new Wildcard();
         $termPerson->setParams(['person' => $q]);
         $boolTermQuery->addShould($termPerson);

         $termOrganisme= new Wildcard();
         $termOrganisme->setParams(['organisme' => $q]);
         $boolTermQuery->addShould($termOrganisme);

         $termType= new Wildcard();
         $termType->setParams(['type' => $q]);
         $boolTermQuery->addShould($termType);
         
         if ($field == 'type' || $field == 'tag' || $field == 'person' || $field == 'oeuvre' || $field == 'organisme' || $field == 'geo') {

            //dump($field);dump($facet);
            //die();
            $termFacet = new Wildcard();
            $termFacet->setParams([$field => '*'.$facet.'*']);
            $boolTermQuery->addMust($termFacet);
         }
         $boolQuery->addMust($boolTermQuery);

         $query = new ElasticaQuery();
         $query->setQuery($boolQuery);
         //$query->addSort(array('type' => array('order' => 'desc')));
         $query->addSort(array('type' => array('order' => 'asc')));
         
         return $query;
     }

    
    /**
     * @return Query
     */
    public function findAllVisibleQuery(ResourceSearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        
        /**
        if ($search->getTitle()) {
            $query
                ->where('r.title like :title')
                ->setParameter('title', '%'.$search->getTitle(). '%');
        }
        **/
        if ($search->getId()) {
            $query
                 ->andWhere('r.id like :id')
                 ->setParameter('id', '%'.$search->getId().'%');
        }

        if ($search->getFront()) {
            $query
                 ->andWhere('r.front like :front')
                 ->setParameter('front', '%'.$search->getFront().'%');
        }

        if ($search->getTitle()) {
            $title = $search->getTitle();
            if (isset($title)){
            $motsTitle = $this->extraireMotsDUnePhrase($title);
            $i=0;
            foreach ($motsTitle as $i => $title) {
            $i++;
            $query = $query
                ->andWhere("r.title like :title$i")
                ->setParameter("title$i", '%'.$title.'%')
                ;
            }
            }
        }

        if ($search->getAuteur()) {
            $auteur = $search->getAuteur();
            if (isset($auteur)){
            $motsAuteur = $this->extraireMotsDUnePhrase($auteur);
            $i=0;
            foreach ($motsAuteur as $i => $auteur) {
            $i++;
            $query = $query
                ->andWhere("r.auteur like :auteur$i")
                ->setParameter("auteur$i", '%'.$auteur.'%')
                ;
            }
        }
        }
        //$this->container->get('fos_elastica.finder.app.resource');
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
        //$queryFirst = $query;
        if ($search->getTexte()) {
            $texte = $search->getTexte();
            if(isset($texte)){
            dump($texte);
            $motsTexte = $this->extraireMotsDUnePhrase($texte);
            $i=0;
            foreach ($motsTexte as $i => $item) {
                $i++;
                $query = $query
                    ->andWhere("r.title like :title$i")
                    //->andWhere("r.title like :title$i")
                    ->setParameter("title$i", '%'.$item.'%')
                    //->setParameter("title$i", '%'.$item.'%')
                ;
                dump($query->getQuery());
            }
            $results = $query->getQuery();
            $nbRsults = count($results->getResult());
            
            
                
                //dump($nbRsults);
                if ($nbRsults == 0){
                    //$query = "";
                    //$query = $this->findVisibleQuery();
                    $i=0;
                    foreach ($motsTexte as $i => $item) {
                    $i++;
                    $query = $query
                    //->andWhere("r.auteur like :auteur$i")
                    ->andWhere("r.auteur like :auteur$i")
                    //->setParameter("auteur$i", '%'.$item.'%')
                    ->setParameter("auteur$i", '%'.$item.'%')
                    
                ;
                dump($query->getQuery());
                    }
                }
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
    public function findByDocRef($idcadic)
    {
        return $this->createQueryBuilder('r')
        ->select()
        ->Where('r.idcadic = :val')
        ->setParameter('val', $idcadic)
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
    
    public function searchFull(ResourceModel $model) {
        $boolQuery = new BoolQuery();
        
        
        $boolTermQuery = new BoolQuery();
        $termTitle = new Wildcard();
        
        
        $termTitle->setParams(['title' => '*'.$model->getSearchTerm().'*']);
        $boolTermQuery->addShould($termTitle);
        
        //$query = new \Elastica\Query();
        $boolQuery->addMust($boolTermQuery);
        $query = new Query();
        $query->setQuery($boolQuery);
        
         
         $adapter = $this->finder->createPaginatorAdapter($query);
                $result = $adapter->getResults($this->getOffset($model->getPage(), $model->getPerPage()), $model->getPerPage())->toArray();
                $count = $adapter->getTotalHits();
                return [
                      'total' => $count,
                      'result' => $result,
                      'page' => $model->getPage(),
                      'perPage' => $model->getPerPage()
                ];
      }
      

    private function extraireMotsDUnePhrase($phrase)
    {    
     
        /* caractères que l'on va remplacer (tout ce qui sépare les mots, en fait) */
        $aremplacer = array(",",".",";",":","!","?","(",")","[","]","{","}","\"","'"," "," La "," Le "," le "," la "," depuis ");
 
        /* ... on va les remplacer par un espace, il n'y aura donc plus dans $phrase 
        que des mots et des espaces */
 
        $enremplacement = " ";
 
      
        /* on fait le remplacement (comme dit ci-avant), puis on supprime les espaces de
        // début et de fin de chaîne (trim) */
        $sansponctuation = trim(str_replace($aremplacer, $enremplacement, $phrase));
    
        /* on coupe la chaîne en fonction d'un séparateur, et chaque élément est une
        // valeur d'un tableau */
        $separateur = "#[ ]+#"; // 1 ou plusieurs espaces
        $mots = preg_split($separateur, $sansponctuation);
      
        return $mots;
    }
    
    public function searchAll(ResourceModel $model) {
        $boolQuery = new BoolQuery();
        
        
        $boolTermQuery = new BoolQuery();
        $termTitle = new Wildcard();
        
        
        $termTitle->setParams(['title' => '*'.$model->getSearchTerm().'*']);
        $boolTermQuery->addShould($termTitle);
        
        $query = new \Elastica\Query();
        $boolQuery->addMust($boolTermQuery);
        //$query = new MultiMatch();
        //$query->setque
        
        $query = new Query();
        $query->setQuery($boolQuery);
        $adapter = $this->finder->createPaginatorAdapter($query);
            $result = $adapter->getResults($this->getOffset($model->getPage(), $model->getPerPage()), $model->getPerPage())->toArray();
            $count = $adapter->getTotalHits();
            $boolQuery->addMust($boolTermQuery);
            return [
                  'total' => $count,
                  'result' => $result,
                  'page' => $model->getPage(),
                  'perPage' => $model->getPerPage()
            ];
      }

    }
