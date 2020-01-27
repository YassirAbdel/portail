<?php
namespace App\Service;

use App\Factory\ModelFactory;
use Elastica\Query;
use Elastica\Type;

#use Elastica\Type;


class ResourceService
{
    private $modelFactory;
    private $resourceType;
 
    public function __construct(
        ModelFactory $modelFactory,
        Type $resourceType
    ) {
        $this->modelFactory = $modelFactory;
        $this->resourceType = $resourceType;
    }
 
    public function fullsearch($q)
    {
        $query['query']['match']['q']['query'] = $q;
        $query['aggs']['person']['terms']['field'] = 'person';
       
        $result = $this->resourceType->search(new Query($query));
        
        return $this->modelFactory->createResourceSearchResult($result);
    }
}

