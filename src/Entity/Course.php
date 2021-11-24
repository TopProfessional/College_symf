<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Course (many to many, inverse side) Student
     * 
     * @ORM\Column(type="ManyToMany")
     */
    private $students;

    public function getStudents():Collection
    {
        $this->$students;
    }

    public function addStudent(Student $student):void
    {
        if (!$this->$students->contains($student) ) {
            //$this->$students[] = $student;
            $this->$students->add($student);
        }
    }

    public function removeStudent(Student $student):void
    {
        $this->$students->removeElement($student);
    }




    /**
     * Course (many to many, inverse side) Teacher
     * 
     * @ORM\Column(type="ManyToMany")
     */
    private $teachers;

    public function addTeacher(Teacher $teacher)
    {
        $this->$teachers[] = $teacher;
    }
}
