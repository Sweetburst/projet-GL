<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $age;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profils")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Allergene::class, mappedBy="profils")
     */
    private $allergenes;

    /**
     * @ORM\OneToMany(targetEntity=Scan::class, mappedBy="profil")
     */
    private $scans;

    public function __construct()
    {
        $this->allergenes = new ArrayCollection();
        $this->scans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection|Allergene[]
     */
    public function getAllergenes(): Collection
    {
        return $this->allergenes;
    }

    public function addAllergene(Allergene $allergene): self
    {
        if (!$this->allergenes->contains($allergene)) {
            $this->allergenes[] = $allergene;
            $allergene->addProfil($this);
        }

        return $this;
    }

    public function removeAllergene(Allergene $allergene): self
    {
        if ($this->allergenes->removeElement($allergene)) {
            $allergene->removeProfil($this);
        }

        return $this;
    }

    /**
     * @return Collection|Scan[]
     */
    public function getScans(): Collection
    {
        return $this->scans;
    }

    public function addScan(Scan $scan): self
    {
        if (!$this->scans->contains($scan)) {
            $this->scans[] = $scan;
            $scan->setProfil($this);
        }

        return $this;
    }

    public function removeScan(Scan $scan): self
    {
        if ($this->scans->removeElement($scan)) {
            // set the owning side to null (unless already changed)
            if ($scan->getProfil() === $this) {
                $scan->setProfil(null);
            }
        }

        return $this;
    }
}
