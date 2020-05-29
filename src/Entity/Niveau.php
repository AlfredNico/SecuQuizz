<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @ORM\OneToMany(targetEntity=Families::class, mappedBy="niveau")
     */
    private $families;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->families = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection|Families[]
     */
    public function getFamilies(): Collection
    {
        return $this->families;
    }

    public function addFamily(Families $family): self
    {
        if (!$this->families->contains($family)) {
            $this->families[] = $family;
            $family->setNiveau($this);
        }

        return $this;
    }

    public function removeFamily(Families $family): self
    {
        if ($this->families->contains($family)) {
            $this->families->removeElement($family);
            // set the owning side to null (unless already changed)
            if ($family->getNiveau() === $this) {
                $family->setNiveau(null);
            }
        }

        return $this;
    }
}
