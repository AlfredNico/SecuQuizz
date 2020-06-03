<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answers
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
     * @ORM\Column(type="boolean")
     */
    private $isAnswer;

    /**
     * @ORM\ManyToOne(targetEntity=Questions::class, inversedBy="answers",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $questions;

    /**
     * @ORM\Column(type="integer")
     */
    private $numc;

    /**
     * @ORM\Column(type="integer")
     */
    private $lig;

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

    public function getIsAnswer(): ?bool
    {
        return $this->isAnswer;
    }

    public function setIsAnswer(bool $isAnswer): self
    {
        $this->isAnswer = $isAnswer;

        return $this;
    }

    public function getQuestions(): ?Questions
    {
        return $this->questions;
    }

    public function setQuestions(?Questions $questions): self
    {
        $this->questions = $questions;

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

    public function getLig(): ?int
    {
        return $this->lig;
    }

    public function setLig(int $lig): self
    {
        $this->lig = $lig;

        return $this;
    }
}
