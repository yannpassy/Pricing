<?php

namespace App\Entity;

use App\Repository\ConcurrentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcurrentRepository::class)
 */
class Concurrent
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
    private $vendeur;

    /**
     * @ORM\ManyToOne(targetEntity=Jeux::class, inversedBy="concurrents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jeuID;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVendeur(): ?string
    {
        return $this->vendeur;
    }

    public function setVendeur(string $vendeur): self
    {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getJeuID(): ?Jeux
    {
        return $this->jeuID;
    }

    public function setJeuID(?Jeux $jeuID): self
    {
        $this->jeuID = $jeuID;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
