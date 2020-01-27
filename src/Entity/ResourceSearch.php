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
    private $title;
    
    /**
     * @var string|null
     */
    private $auteur;
    
    /**
     * @var string|null
     */
    private $texte;
    

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string|NULL
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @return string|NULL
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * @param string|NULL $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string|NULL $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    
    /**
     * @param string|NULL $lang
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    }
            
}


