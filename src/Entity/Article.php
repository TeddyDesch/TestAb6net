<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; // Importation de la classe permettant de faire un slug
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Vich\Uploadable
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contentShort;

    /**
     * @ORM\Column(type="string", length=10000)
     */
    private $contentLong;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startPublication;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endPublication;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="articles", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    // ...

    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $editDate;

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

    public function getContentShort(): ?string
    {
        return $this->contentShort;
    }

    public function setContentShort(string $contentShort): self
    {
        $this->contentShort = $contentShort;

        return $this;
    }

    public function getContentLong(): ?string
    {
        return $this->contentLong;
    }

    public function setContentLong(string $contentLong): self
    {
        $this->contentLong = $contentLong;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->addDate;
    }

    public function setAddDate(\DateTimeInterface $addDate): self
    {
        $this->addDate = $addDate;

        return $this;
    }

    public function getStartPublication(): ?\DateTimeInterface
    {
        return $this->startPublication;
    }

    public function setStartPublication(\DateTimeInterface $startPublication): self
    {
        $this->startPublication = $startPublication;

        return $this;
    }

    public function getEndPublication(): ?\DateTimeInterface
    {
        return $this->endPublication;
    }

    public function setEndPublication(\DateTimeInterface $endPublication): self
    {
        $this->endPublication = $endPublication;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }


    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }

    public function setEditDate(?\DateTimeInterface $editDate): self
    {
        $this->editDate = $editDate;

        return $this;
    }
}
