<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="ipn", message="Cet IPN est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $ipn;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $badge;

    const STATUS = [
        1 => 'Administrateur',
        2 => "Administrateur d'atelier",
        3 => 'Gestionnaire',
        4 => 'Conducteur de ligne'
    ];

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $add_at;

    const ACCESS = [
        0 => 'Non',
        1 => 'Oui'
    ];

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $access;

    /**
     * @ORM\ManyToOne(targetEntity=Workshop::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workshop;

    /**
     * @ORM\OneToMany(targetEntity=MarketOrder::class, mappedBy="user")
     */
    private $marketOrders;

    public function __toString()
    {
        return $this->ipn;
    }

    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->access = false;
        $this->add_at = new \DateTime();
        $this->marketOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpn(): ?string
    {
        return $this->ipn;
    }

    public function setIpn(string $ipn): self
    {
        $this->ipn = $ipn;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->ipn;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(?string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusType(): string
    {
        return self::STATUS[$this->status];
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

    public function getAccess(): ?bool
    {
        return $this->access;
    }

    public function setAccess(bool $access): self
    {
        $this->access = $access;

        return $this;
    }

    public function getAccessType(): string
    {
        return self::ACCESS[$this->access];
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
            $marketOrder->setUser($this);
        }

        return $this;
    }

    public function removeMarketOrder(MarketOrder $marketOrder): self
    {
        if ($this->marketOrders->contains($marketOrder)) {
            $this->marketOrders->removeElement($marketOrder);
            // set the owning side to null (unless already changed)
            if ($marketOrder->getUser() === $this) {
                $marketOrder->setUser(null);
            }
        }

        return $this;
    }
}
