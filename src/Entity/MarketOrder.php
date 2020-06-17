<?php

namespace App\Entity;

use App\Repository\MarketOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarketOrderRepository")
 */
class MarketOrder
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
    private $ot;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $add_at;

    /**
     * @ORM\Column(type="array")
     */
    private $productQuantity = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="marketOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Workshop::class, inversedBy="marketOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workshop;

    public function __construct()
    {
        $this->add_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOt(): ?string
    {
        return $this->ot;
    }

    public function setOt(string $ot): self
    {
        $this->ot = $ot;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getAddAt(): ?\DateTimeInterface
    {
        return $this->add_at;
    }

    public function setAddAt(\DateTimeInterface $add_at): self
    {
        $this->add_at = $add_at;

        return $this;
    }

    public function getProductQuantity(): ?array
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(array $productQuantity): self
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
