<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 */
class Mark implements EntityInterface
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $mark = null;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Teacher $teacher = null;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Student $student = null;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Course $course = null;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $date = null;

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(?int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
