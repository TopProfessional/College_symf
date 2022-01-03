<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher implements IdentifiableInterface
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $salary = null;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="teachers")
     */
    private Collection $courses;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\ManyToMany(targetEntity=UserClass::class, mappedBy="teachers")
     */
    private Collection $classes;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->classes = new ArrayCollection();
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->addTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            $course->removeTeacher($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserClass[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClasses(UserClass $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
            $class->addTeacher($this);
        }

        return $this;
    }

    public function removeClasses(UserClass $class): self
    {
        if ($this->classes->removeElement($class)) {
            $class->removeTeacher($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->user;
    }
}
