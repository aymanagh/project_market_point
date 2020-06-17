<?php

namespace App\Entity;

use App\Repository\WorkshopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkshopRepository")
 */
class Workshop
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="workshop")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="workshop")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=MarketOrder::class, mappedBy="workshop")
     */
    private $marketOrders;

    /**
     * @ORM\OneToMany(targetEntity=ProductionLine::class, mappedBy="workshop")
     */
    private $productionLines;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->marketOrders = new ArrayCollection();
        $this->productionLines = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setWorkshop($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getWorkshop() === $this) {
                $user->setWorkshop(null);
            }
        }

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
            $product->setWorkshop($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getWorkshop() === $this) {
                $product->setWorkshop(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MarketOrder[]
     */
    public function getMarketOrders(): Collection
    {
        return $this->marketOrders;
    }

    public function addMarketOrder(MarketOrder $marketOrder): self
    {
        if (!$this->marketOrders->contains($marketOrder)) {
            $this->marketOrders[] = $marketOrder;
            $marketOrder->setWorkshop($this);
        }

        return $this;
    }

    public function removeMarketOrder(MarketOrder $marketOrder): self
    {
        if ($this->marketOrders->contains($marketOrder)) {
            $this->marketOrders->removeElement($marketOrder);
            // set the owning side to null (unless already changed)
            if ($marketOrder->getWorkshop() === $this) {
                $marketOrder->setWorkshop(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductionLine[]
     */
    public function getProductionLines(): Collection
    {
        return $this->productionLines;
    }

    public function addProductionLine(ProductionLine $productionLine): self
    {
        if (!$this->productionLines->contains($productionLine)) {
            $this->productionLines[] = $productionLine;
            $productionLine->setWorkshop($this);
        }

        return $this;
    }

    public function removeProductionLine(ProductionLine $productionLine): self
    {
        if ($this->productionLines->contains($productionLine)) {
            $this->productionLines->removeElement($productionLine);
            // set the owning side to null (unless already changed)
            if ($productionLine->getWorkshop() === $this) {
                $productionLine->setWorkshop(null);
            }
        }

        return $this;
    }
}
