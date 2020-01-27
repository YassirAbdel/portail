<?php

namespace App\Entity;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Component\Validator\Constraint as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class ResourceSearch1 
{
    
    /**
     * @var string|null
     */
    private $title;
    
    
    
    public function getTitle()
    {
        return $this->title;
    }

    
    public function setTitle($title)
    {
        $this->title = $title;
    }

            
}


