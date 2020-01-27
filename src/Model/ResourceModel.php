<?php
namespace App\Model;

class ResourceModel extends BaseModel 
{
    protected $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
}