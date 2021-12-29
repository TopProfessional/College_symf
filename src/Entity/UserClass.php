<?php

namespace App\Entity;

use App\Repository\UserClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserClassRepository::class)
 */
class UserClass implements IdentifiableInterface
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private ?string $name = null;

    /**
     * @ORM\OneToOne(targetEntity=Teacher::class, inversedBy="classes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Teacher $teacher = null;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="classes")
     */
    private Collection $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClasses($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClasses() === $this) {
                $student->setClasses(null);
            }
        }

        return $this;
    }
}
