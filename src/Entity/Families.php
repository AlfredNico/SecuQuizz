<?php

namespace App\Entity;

use App\Repository\FamiliesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FamiliesRepository::class)
 */
class Families
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="families")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Competences::class, mappedBy="families", orphanRemoval=true)
     */
    private $competences;

    /**
     * @ORM\ManyToOne(targetEntity=Families::class, inversedBy="enfant")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Families::class, mappedBy="parent")
     */
    private $enfant;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->enfant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
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

    /**
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->setFamilies($this);
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
            // set the owning side to null (unless already changed)
            if ($competence->getFamilies() === $this) {
                $competence->setFamilies(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getEnfant(): Collection
    {
        return $this->enfant;
    }

    public function addEnfant(self $enfant): self
    {
        if (!$this->enfant->contains($enfant)) {
            $this->enfant[] = $enfant;
            $enfant->setParent($this);
        }

        return $this;
    }

    public function removeEnfant(self $enfant): self
    {
        if ($this->enfant->contains($enfant)) {
            $this->enfant->removeElement($enfant);
            // set the owning side to null (unless already changed)
            if ($enfant->getParent() === $this) {
                $enfant->setParent(null);
            }
        }

        return $this;
    }
}
