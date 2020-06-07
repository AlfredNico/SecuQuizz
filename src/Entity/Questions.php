<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attached;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texteComplementaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autreTexte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motif;

    /**
     * @ORM\OneToMany(targetEntity=Answers::class, mappedBy="questions", orphanRemoval=true,cascade={"persist","remove"})
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\Column(type="integer")
     */
    private $numc;

    /**
     * @ORM\ManyToOne(targetEntity=Types::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="questions")
     */
    private $competences;

    /**
     * @ORM\ManyToOne(targetEntity=Families::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->competences = new ArrayCollection();
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

    public function getAttached(): ?string
    {
        return $this->attached;
    }

    public function setAttached(string $attached): self
    {
        $this->attached = $attached;

        return $this;
    }

    public function getTexteComplementaire(): ?string
    {
        return $this->texteComplementaire;
    }

    public function setTexteComplementaire(?string $texteComplementaire): self
    {
        $this->texteComplementaire = $texteComplementaire;

        return $this;
    }

    public function getAutreTexte(): ?string
    {
        return $this->autreTexte;
    }

    public function setAutreTexte(?string $autreTexte): self
    {
        $this->autreTexte = $autreTexte;

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

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * @return Collection|Answers[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestions($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestions() === $this) {
                $answer->setQuestions(null);
            }
        }

        return $this;
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

    public function getNumc(): ?int
    {
        return $this->numc;
    }

    public function setNumc(int $numc): self
    {
        $this->numc = $numc;

        return $this;
    }

    public function getType(): ?Types
    {
        return $this->type;
    }

    public function setType(?Types $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

        return $this;
    }

    public function getArticle(): ?Families
    {
        return $this->article;
    }

    public function setArticle(?Families $article): self
    {
        $this->article = $article;

        return $this;
    }
}
