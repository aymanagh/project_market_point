<?php

namespace App\Entity;

use App\Repository\OpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OpRepository")
 */
class Op
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="op")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=ProductionLine::class, inversedBy="ops")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productionLine;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setOp($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getOp() === $this) {
                $product->setOp(null);
            }
        }

        return $this;
    }

    public function getProductionLine(): ?ProductionLine
    {
        return $this->productionLine;
    }

    public function setProductionLine(?ProductionLine $productionLine): self
    {
        $this->productionLine = $productionLine;

        return $this;
    }
}
