<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ResourceRepository")
 */
class Resource
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lang;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $person;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $oeuvre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $organisme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $geo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tag;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $analyse;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rights;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     */
    private $oai;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resp1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $editeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $editeurlieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anneedit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pagination;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $collection;
    
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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
    
    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title); 
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(?string $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getOeuvre(): ?string
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?string $oeuvre): self
    {
        $this->oeuvre = $oeuvre;

        return $this;
    }

    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    public function setOrganisme(?string $organisme): self
    {
        $this->organisme = $organisme;

        return $this;
    }

    public function getGeo(): ?string
    {
        return $this->geo;
    }

    public function setGeo(?string $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getAnalyse(): ?bool
    {
        return $this->analyse;
    }

    public function setAnalyse(?bool $analyse): self
    {
        $this->analyse = $analyse;

        return $this;
    }

    public function getRights(): ?bool
    {
        return $this->rights;
    }

    public function setRights(?bool $rights): self
    {
        $this->rights = $rights;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOai(): ?bool
    {
        return $this->oai;
    }

    public function setOai(?bool $oai): self
    {
        $this->oai = $oai;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getResp1(): ?string
    {
        return $this->resp1;
    }

    public function setResp1(string $resp1): self
    {
        $this->resp1 = $resp1;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(string $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getEditeurlieu(): ?string
    {
        return $this->editeurlieu;
    }

    public function setEditeurlieu(string $editeurlieu): self
    {
        $this->editeurlieu = $editeurlieu;

        return $this;
    }

    public function getAnneedit(): ?string
    {
        return $this->anneedit;
    }

    public function setAnneedit(string $anneedit): self
    {
        $this->anneedit = $anneedit;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPagination(): ?string
    {
        return $this->pagination;
    }

    public function setPagination(string $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }
}
