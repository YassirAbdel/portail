<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 * @Vich\Uploadable
 */
class Subject
{
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
     * @Vich\UploadableField(mapping="subject_image", fileNameProperty="fileName")
     */
    private $imageFile;
    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Resource", mappedBy="subjects")
     */
    private $resources;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="subjects")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subTitle;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->updated_at = new \DateTime();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
    
    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title); 
    }
}
