<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designation;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $warningLevel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $waiting;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategorie::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productCategorie;

    /**
     * @ORM\ManyToOne(targetEntity=Workshop::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workshop;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="imageName")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\ManyToOne(targetEntity=Op::class, inversedBy="product")
     */
    private $op;

    /**
     * @ORM\ManyToMany(targetEntity=ProductionLine::class, inversedBy="products")
     */
    private $productionLine;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->productionLine = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->reference;
        return $this->designation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWarningLevel(): ?string
    {
        return $this->warningLevel;
    }

    public function setWarningLevel(string $warningLevel): self
    {
        $this->warningLevel = $warningLevel;

        return $this;
    }

    public function getWaiting(): ?string
    {
        return $this->waiting;
    }

    public function setWaiting(string $waiting): self
    {
        $this->waiting = $waiting;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getProductCategorie(): ?ProductCategorie
    {
        return $this->productCategorie;
    }

    public function setProductCategorie(?ProductCategorie $productCategorie): self
    {
        $this->productCategorie = $productCategorie;

        return $this;
    }

    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    public function setWorkshop(?Workshop $workshop): self
    {
        $this->workshop = $workshop;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getOp(): ?Op
    {
        return $this->op;
    }

    public function setOp(?Op $op): self
    {
        $this->op = $op;

        return $this;
    }

    /**
     * @return Collection|ProductionLine[]
     */
    public function getProductionLine(): Collection
    {
        return $this->productionLine;
    }

    public function addProductionLine(ProductionLine $productionLine): self
    {
        if (!$this->productionLine->contains($productionLine)) {
            $this->productionLine[] = $productionLine;
        }

        return $this;
    }

    public function removeProductionLine(ProductionLine $productionLine): self
    {
        if ($this->productionLine->contains($productionLine)) {
            $this->productionLine->removeElement($productionLine);
        }

        return $this;
    }
}
