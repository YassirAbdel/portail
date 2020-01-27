<?php
    namespace App\Factory;


use App\Model\Search\Result as ResultModel;
use App\Model\Search\Resource as ResourceModel;
use Elastica\ResultSet;
     
    class ModelFactory
    {
        public function createResourceSearchResult(ResultSet $resultSet)
        {
            $resultModel = new ResultModel();

            if ($resultSet->getTotalHits() < 1) {
                return $resultModel;
            }
            
            $resultModel->total = $resultSet->getTotalHits();
            $resultModel->aggregations = array_column($resultSet->getAggregations()['awards']['buckets'], 'doc_count', 'key');
     
            /** @var \Elastica\Result $item */
            foreach ($resultSet->getResults() as $item) {
                $data = $item->getData();
                
                $resourceModel = new ResourceModel();
                $resourceModel->id = $data['id'];
                $resourceModel->title = $data['title'];
                $resourceModel->type = $data['person'];
     
                $resultModel->resources[] = $resourceModel;
            }
     
            return $resultModel;
        }
    }