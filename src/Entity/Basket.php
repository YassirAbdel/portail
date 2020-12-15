<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketRepository")
 * @ORM\Table(name="basket")
 */
class Basket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creat_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Resource", inversedBy="baskets")
     * @ORM\JoinTable(
     *  name="basket_resource",
     *  joinColumns={
     *      @ORM\JoinColumn(name="basket_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     *  }
     * )
     */
    
    private $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->creat_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creat_at;
    }

    public function setCreatAt(\DateTimeInterface $creat_at): self
    {
        $this->creat_at = $creat_at;

        return $this;
    }
    
    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }
    

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->addBasket($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
        }

        return $this;
    }
    
    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
