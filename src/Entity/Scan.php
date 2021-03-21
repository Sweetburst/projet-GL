<?php

namespace App\Entity;

use App\Repository\ScanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScanRepository::class)
 */
class Scan
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
    private $nom_scan;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ValeurCodeBar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="scans")
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomScan(): ?string
    {
        return $this->nom_scan;
    }

    public function setNomScan(string $nom_scan): self
    {
        $this->nom_scan = $nom_scan;

        return $this;
    }

    public function getValeurCodeBar(): ?string
    {
        return $this->ValeurCodeBar;
    }

    public function setValeurCodeBar(string $ValeurCodeBar): self
    {
        $this->ValeurCodeBar = $ValeurCodeBar;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}
