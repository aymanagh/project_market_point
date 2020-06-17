<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionLineRepository")
 */
class ProductionLine
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Workshop", inversedBy="productionLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workshop;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="productionLine")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Op::class, mappedBy="productionLine")
     */
    private $ops;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->ops = new ArrayCollection();
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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addProductionLine($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeProductionLine($this);
        }

        return $this;
    }

    /**
     * @return Collection|Op[]
     */
    public function getOps(): Collection
    {
        return $this->ops;
    }

    public function addOp(Op $op): self
    {
        if (!$this->ops->contains($op)) {
            $this->ops[] = $op;
            $op->setProductionLine($this);
        }

        return $this;
    }

    public function removeOp(Op $op): self
    {
        if ($this->ops->contains($op)) {
            $this->ops->removeElement($op);
            // set the owning side to null (unless already changed)
            if ($op->getProductionLine() === $this) {
                $op->setProductionLine(null);
            }
        }

        return $this;
    }
}
