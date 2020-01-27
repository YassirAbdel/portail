<?php
namespace App\Model\Search;

class Result 
{
    /**
     * @var int
     */
    public $total;

    /**
     * var array
     */
    public $aggregations;

    /**
     * var Resource[]
     */
    public $resources;

}