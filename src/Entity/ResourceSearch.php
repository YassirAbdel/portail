<?php

namespace App\Entity;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Component\Validator\Constraint as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class ResourceSearch 
{
    /**
     * @var string|null
     */
    private $type;
    
    /**
     * @var string|null
     */
    private $titre;
    
    /**
     * @var string|null
     */
    private $auteur;
    
    /**
     * @var ArrayCollection
     */
    private $persons;
    
    
    function __construct()
    {
        $this->persons = new ArrayCollection();
    }
    /**
     * @return string|NULL
     */
    public function getType()
    {
        return $this->type;
    }
    
    
    /**
     * @return string|NULL
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @return string|NULL
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param string|NULL $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string|NULL $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @param string|NULL $auteur
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPersons()
    {
        return $this->persons;
    }
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $persons
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;
    }
    
            
}


