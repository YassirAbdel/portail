<?php

namespace App\SearchRepository;

#use App\Model\ResourceModel;
use Elastica\Query\BoolQuery;
#use Elastica\Query\Wildcard;
use FOS\ElasticaBundle\Repository;
#use Elastica\Query\MultiMatch;
use Elastica\Query;


class SearchRepository extends Repository
{
    public function searchFull1(ResourceModel $model) {
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

public function fullsearch($search = null, $limit = 100)
    {
        $query = new Query();

        $boolQuery = new BoolQuery();

        if (!\is_null($search)) {
            $fieldQuery = new Query\MatchPhrasePrefix();
            $fieldQuery->setField('title', $search);
            //$fieldQuery->setField('person', $search);
            

            $boolQuery->addMust($fieldQuery);
        }

        $query->setQuery($boolQuery);
        $query->setSize($limit);

        return $this->find($query);
    }

    
}

 
