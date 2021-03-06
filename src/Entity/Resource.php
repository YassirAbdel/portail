<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ResourceRepository")
 * @Vich\Uploadable
 */
class Resource
{
    const TypeDoc = [
        0 => 'Affiche',
        1 => 'Archives',
        2 => 'Article',
        3 => 'Carte postale',
        4 => 'Dessin / oeuvre graphique',
        5 => 'Document sonore',
        6 => 'Document vidéo',
        7 => 'Dossier documentaire',
        8 => 'Manuscrit',
        9 => 'Ouvrage',
        10 => 'Dossier documentaire',
        11 => 'Partition chorégraphique',
        12 => 'Partition musicale',
        13 => 'Photographie',
        14 => 'Revue'
    ];
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var String
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;
    
    /**
     * @var File|null
     * @Assert\Image{
     *      mineTypes="image\jpeg", "image\png",
     * }
     * @Vich\UploadableField(mapping="resource_image", fileNameProperty="fileName")
     */
    private $imageFile;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @Assert\Length(min=2)
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @Assert\Length(min=2)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lang;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $person;

    /**
     * @Assert\Length(min=2)
     * @ORM\Column(type="text", nullable=true)
     */
    private $oeuvre;

    /**
     * @Assert\Length(min=2)
     * @ORM\Column(type="text", nullable=true)
     */
    private $organisme;

    /**
     * @Assert\Length(min=2)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $geo;

    /**
     * @Assert\Length(min=2)
     * @ORM\Column(type="text", nullable=true)
     */
    private $tag;

    /**
     * @ORM\Column(type="boolean", nullable=false)
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
     * @ORM\Column(type="text")
     */
    private $auteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resp1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $editeur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $editeurlieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $anneedit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pagination;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $collection;
    
    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $idcadic;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="resources")
     */
    private $persons;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Work", inversedBy="resources")
     */
    private $works;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Structure", inversedBy="resources")
     */
    private $structures;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Basket", mappedBy="resources")
     */
    private $baskets;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Subject", inversedBy="resources")
     */
    private $subjects;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $auteurS;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteurM;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeS;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $peHisto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origDoc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $copyR;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rightsA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $support;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $formFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nbFiles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $supNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locaSupnum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coteNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locaSup;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $audio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlDoc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resEdit;

    /**
     * @ORM\Column(type="integer")
     */
    private $lecteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $allIndex;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $folderFront;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $front;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlFolfer;

   public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->persons = new ArrayCollection();
        $this->baskets = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->works = new ArrayCollection();
        $this->structures = new ArrayCollection();
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

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getResp1(): ?string
    {
        return $this->resp1;
    }

    public function setResp1(?string $resp1): self
    {
        $this->resp1 = $resp1;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(?string $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getEditeurlieu(): ?string
    {
        return $this->editeurlieu;
    }

    public function setEditeurlieu(?string $editeurlieu): self
    {
        $this->editeurlieu = $editeurlieu;

        return $this;
    }

    public function getAnneedit(): ?string
    {
        return $this->anneedit;
    }

    public function setAnneedit(?string $anneedit): self
    {
        $this->anneedit = $anneedit;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPagination(): ?string
    {
        return $this->pagination;
    }

    public function setPagination(?string $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(?string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }
    
    public function getIdcadic(): ?string
    {
        return $this->idcadic;
    }
    
    public function setIdcadic(?string $idcadic)
    {
        $this->idcadic = $idcadic;
    }
    
    /**
     * @return Collection|Person[]
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->addResource($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->persons->contains($person)) {
            $this->persons->removeElement($person);
            $person->removeResource($this);
        }

        return $this;
    }

     /**
     * @return Collection|Work[]
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(Work $work): self
    {
        if (!$this->works->contains($work)) {
            $this->works[] = $work;
            $work->addResource($this);
        }

        return $this;
    }

    public function removeWork(Work $work): self
    {
        if ($this->works->contains($work)) {
            $this->works->removeElement($work);
            $work->removeResource($this);
        }
        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Person $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->addResource($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->contains($structure)) {
            $this->structures->removeElement($structure);
            $structure->removeResource($this);
        }

        return $this;
    }
    
    /**
     * @return Collection\Basket[]
     */
    public function getBaskets(): Collection
    {
        return $this->baskets;
    }
    
    public function addBasket(Basket $basket): self
    {
        if (!$this->baskets->contains($basket)) {
            $this->baskets[] = $basket;
            $basket->addResource($this);
        }
        
        return $this;
    }
    
    public function removeBasket(Basket $basket): self
    {
        if ($this->baskets->contains($basket)) {
            $this->baskets->removeElement($basket);
            $basket->removeResource($this);
        }
        
        return $this;
    }
    
    /**
     * @return string|NULL
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\File\File|NULL
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
    
    /**
     * @param string|NULL $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\File\File|NULL $imageFile
     */
    public function setImageFile($imageFile)
    {
        //$today = date("Ym");
        //$this->imageFile = $today . '/' . $imageFile;
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    /**
     * @return Collection|Subject[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->addResource($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
            $subject->removeResource($this);
        }

        return $this;
    }

      /**
   * @return array
   */
  public function getNameSuggest()
  {
    return array(
      'input' => array_merge(
        array(
          $this->getTitle(),
          $this->getEditeur(),
        ),
        //$this->getStyles()
        $this->getType()
      ),
      'weight' => $this->calculateWeight(),
    );
  }
  /**
   * @return int
   */
  public function calculateWeight()
  {
    $weight = 0;
    if ($this->isPromoted()) {
      $weight += 5;
    }
    return $weight;
  }

  public function getAuteurS(): ?string
  {
      return $this->AuteurS;
  }

  public function setAuteurS(?string $AuteurS): self
  {
      $this->AuteurS = $AuteurS;

      return $this;
  }

  public function getAuteurM(): ?string
  {
      return $this->auteurM;
  }

  public function setAuteurM(?string $auteurM): self
  {
      $this->auteurM = $auteurM;

      return $this;
  }

  public function getAnneeS(): ?int
  {
      return $this->anneeS;
  }

  public function setAnneeS(?int $anneeS): self
  {
      $this->anneeS = $anneeS;

      return $this;
  }

  public function getPeHisto(): ?string
  {
      return $this->peHisto;
  }

  public function setPeHisto(?string $peHisto): self
  {
      $this->peHisto = $peHisto;

      return $this;
  }

  public function getOrigDoc(): ?string
  {
      return $this->origDoc;
  }

  public function setOrigDoc(?string $origDoc): self
  {
      $this->origDoc = $origDoc;

      return $this;
  }

  public function getCopyR(): ?string
  {
      return $this->copyR;
  }

  public function setCopyR(?string $copyR): self
  {
      $this->copyR = $copyR;

      return $this;
  }

  public function getRightsA(): ?string
  {
      return $this->rightsA;
  }

  public function setRightsA(?string $rightsA): self
  {
      $this->rightsA = $rightsA;

      return $this;
  }

  public function getSupport(): ?string
  {
      return $this->support;
  }

  public function setSupport(?string $support): self
  {
      $this->support = $support;

      return $this;
  }

  public function getCouleur(): ?string
  {
      return $this->couleur;
  }

  public function setCouleur(?string $couleur): self
  {
      $this->couleur = $couleur;

      return $this;
  }

  public function getFormat(): ?string
  {
      return $this->format;
  }

  public function setFormat(?string $format): self
  {
      $this->format = $format;

      return $this;
  }

  public function getFormFile(): ?string
  {
      return $this->formFile;
  }

  public function setFormFile(?string $formFile): self
  {
      $this->formFile = $formFile;

      return $this;
  }

  public function getDuree(): ?string
  {
      return $this->duree;
  }

  public function setDuree(?string $duree): self
  {
      $this->duree = $duree;

      return $this;
  }

  public function getNbFiles(): ?string
  {
      return $this->nbFiles;
  }

  public function setNbFiles(?string $nbFiles): self
  {
      $this->nbFiles = $nbFiles;

      return $this;
  }

  public function getCote(): ?string
  {
      return $this->cote;
  }

  public function setCote(?string $cote): self
  {
      $this->cote = $cote;

      return $this;
  }

  public function getSupNum(): ?string
  {
      return $this->supNum;
  }

  public function setSupNum(?string $supNum): self
  {
      $this->supNum = $supNum;

      return $this;
  }

  public function getLocaSupnum(): ?string
  {
      return $this->locaSupnum;
  }

  public function setLocaSupnum(?string $locaSupnum): self
  {
      $this->locaSupnum = $locaSupnum;

      return $this;
  }

  public function getCoteNum(): ?string
  {
      return $this->coteNum;
  }

  public function setCoteNum(?string $coteNum): self
  {
      $this->coteNum = $coteNum;

      return $this;
  }

  public function getLocaSup(): ?string
  {
      return $this->locaSup;
  }

  public function setLocaSup(?string $locaSup): self
  {
      $this->locaSup = $locaSup;

      return $this;
  }

  public function getImg(): ?string
  {
      return $this->img;
  }

  public function setImg(?string $img): self
  {
      $this->img = $img;

      return $this;
  }

  public function getPdf(): ?string
  {
      return $this->pdf;
  }

  public function setPdf(?string $pdf): self
  {
      $this->pdf = $pdf;

      return $this;
  }

  public function getAudio(): ?string
  {
      return $this->audio;
  }

  public function setAudio(?string $audio): self
  {
      $this->audio = $audio;

      return $this;
  }

  public function getVideo(): ?string
  {
      return $this->video;
  }

  public function setVideo(?string $video): self
  {
      $this->video = $video;

      return $this;
  }

  public function getUrlDoc(): ?string
  {
      return $this->urlDoc;
  }

  public function setUrlDoc(?string $urlDoc): self
  {
      $this->urlDoc = $urlDoc;

      return $this;
  }

  public function getResEdit(): ?string
  {
      return $this->resEdit;
  }

  public function setResEdit(?string $resEdit): self
  {
      $this->resEdit = $resEdit;

      return $this;
  }

  public function getLecteur(): ?int
  {
      return $this->lecteur;
  }

  public function setLecteur(int $lecteur): self
  {
      $this->lecteur = $lecteur;

      return $this;
  }

  public function getAllIndex(): ?string
  {
      return $this->allIndex;
  }

  public function setAllIndex(?string $allIndex): self
  {
      $this->allIndex = $allIndex;

      return $this;
  }

  public function getFront(): ?bool
  {
      return $this->front;
  }

  public function setFront(?bool $front): self
  {
      $this->front = $front;

      return $this;
  }

  public function getfolderFront(): ?bool
  {
      return $this->folderFront;
  }

  public function setfolderFront(?bool $folderFront): self
  {
      $this->folderFront = $folderFront;

      return $this;
  }
  
  public function getUrlFolder(): ?string
  {
      return $this->urlFolfer;
  }

  public function setUrlFolder(?string $urlFolfer): self
  {
      $this->urlFolfer = $urlFolfer;

      return $this;
  }
  
}
