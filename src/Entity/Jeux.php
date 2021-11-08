<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JeuxRepository::class)
 */
class Jeux
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $editeur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Concurrent::class, mappedBy="jeuID")
     */
    private $concurrents;

    public function __construct()
    {
        $this->concurrents = new ArrayCollection();
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

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(?string $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Concurrent[]
     */
    public function getConcurrents(): Collection
    {
        return $this->concurrents;
    }

    public function addConcurrent(Concurrent $concurrent): self
    {
        if (!$this->concurrents->contains($concurrent)) {
            $this->concurrents[] = $concurrent;
            $concurrent->setjeuID($this);
        }

        return $this;
    }

    public function removeConcurrent(Concurrent $concurrent): self
    {
        if ($this->concurrents->removeElement($concurrent)) {
            // set the owning side to null (unless already changed)
            if ($concurrent->getjeuID() === $this) {
                $concurrent->setjeuID(null);
            }
        }

        return $this;
    }
}
