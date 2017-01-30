<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EB\CoreBundle\Entity\School;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * SchoolStaffUser
 *
 * @ORM\Table(name="school_staff_user")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Repository\SchoolStaffUserRepository")
 *
 * @UniqueEntity(fields="username", targetClass="EB\UserBundle\Entity\AbstractUser", message="fos_user.username.already_used")
 * @UniqueEntity(fields="email", targetClass="EB\UserBundle\Entity\AbstractUser", message="fos_user.email.already_used")
 */
class SchoolStaffUser extends AbstractUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="job_title", type="string", length=255)
     */
    private $jobTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="academic_degree", type="string", length=255, nullable=true)
     */
    private $academicDegree;

    /**
     * @var School
     *
     * @ORM\ManyToOne(targetEntity="EB\CoreBundle\Entity\School", inversedBy="schoolStaffUsers")
     */
    private $school;

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     * @return $this
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcademicDegree()
    {
        return $this->academicDegree;
    }

    /**
     * @param string $academicDegree
     * @return $this
     */
    public function setAcademicDegree($academicDegree)
    {
        $this->academicDegree = $academicDegree;

        return $this;
    }

    /**
     * @return School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * @param School $school
     * @return $this
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }
}
