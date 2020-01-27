<?php
namespace App\Model;

class BaseModel 
{
    protected $page;
    protected $perPage;
    protected $searchTerm;
    /** getters and setters **/

    public function getPage()
    {
        return $this->page;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    public function setPage(Int $page)
    {
        $this->page = $page;
    }

    public function setPerPage(Int $perPage)
    {
        $this->perPage = $perPage;
    }

    public function setSearchTerm(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

  }