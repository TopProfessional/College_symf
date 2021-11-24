<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $salary;

    public function getId(): ?int
    {
        return $this->id;
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
     * Teacher (many to many, owner side) Course
     * 
     * @ORM\Column(type="ManyToMany")
     */
    private $courses;

    public function addCourse(Course $course)
    {
        $course->addTeacher($this);  // synchronously updating inverse side
        $this->$courses[] = $course;
    }




    /**
     * One Teacher it is one User.
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
}
