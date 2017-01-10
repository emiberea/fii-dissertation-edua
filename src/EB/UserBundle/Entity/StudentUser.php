<?php

namespace EB\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EB\CoreBundle\Entity\AdmissionAttendee;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * StudentUser
 *
 * @ORM\Table(name="student_user")
 * @ORM\Entity(repositoryClass="EB\UserBundle\Repository\StudentUserRepository")
 *
 * @UniqueEntity(fields="username", targetClass="EB\UserBundle\Entity\AbstractUser", message="fos_user.username.already_used")
 * @UniqueEntity(fields="email", targetClass="EB\UserBundle\Entity\AbstractUser", message="fos_user.email.already_used")
 */
class StudentUser extends AbstractUser
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
     * @ORM\Column(name="father_initial", type="string", length=255, nullable=true)
     */
    private $fatherInitial;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=255, nullable=true)
     */
    private $pin;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="high_school", type="string", length=255, nullable=true)
     */
    private $highSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="specialization", type="string", length=255, nullable=true)
     */
    private $specialization;

    /**
     * @var float
     *
     * @ORM\Column(name="baccalaureate_average_grade", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $baccalaureateAverageGrade;

    /**
     * @var float
     *
     * @ORM\Column(name="baccalaureate_maximum_grade", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $baccalaureateMaximumGrade;

    /**
     * @var ArrayCollection|AdmissionAttendee[]
     *
     * @ORM\OneToMany(targetEntity="EB\CoreBundle\Entity\AdmissionAttendee", mappedBy="studentUser")
     */
    private $admissionAttendees;

    /**
     * @return string
     */
    public function getFatherInitial()
    {
        return $this->fatherInitial;
    }

    /**
     * @param string $fatherInitial
     * @return $this
     */
    public function setFatherInitial($fatherInitial)
    {
        $this->fatherInitial = $fatherInitial;

        return $this;
    }

    /**
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param string $pin
     * @return $this
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getHighSchool()
    {
        return $this->highSchool;
    }

    /**
     * @param string $highSchool
     * @return $this
     */
    public function setHighSchool($highSchool)
    {
        $this->highSchool = $highSchool;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpecialization()
    {
        return $this->specialization;
    }

    /**
     * @param string $specialization
     * @return $this
     */
    public function setSpecialization($specialization)
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * @return float
     */
    public function getBaccalaureateAverageGrade()
    {
        return $this->baccalaureateAverageGrade;
    }

    /**
     * @param float $baccalaureateAverageGrade
     * @return $this
     */
    public function setBaccalaureateAverageGrade($baccalaureateAverageGrade)
    {
        $this->baccalaureateAverageGrade = $baccalaureateAverageGrade;

        return $this;
    }

    /**
     * @return float
     */
    public function getBaccalaureateMaximumGrade()
    {
        return $this->baccalaureateMaximumGrade;
    }

    /**
     * @param float $baccalaureateMaximumGrade
     * @return $this
     */
    public function setBaccalaureateMaximumGrade($baccalaureateMaximumGrade)
    {
        $this->baccalaureateMaximumGrade = $baccalaureateMaximumGrade;

        return $this;
    }

    /**
     * @return ArrayCollection|AdmissionAttendee[]
     */
    public function getAdmissionAttendees()
    {
        return $this->admissionAttendees;
    }

    /**
     * @param AdmissionAttendee $admissionAttendee
     * @return $this
     */
    public function addAdmissionAttendee($admissionAttendee)
    {
        $this->admissionAttendees->add($admissionAttendee);

        return $this;
    }

    /**
     * @param AdmissionAttendee $admissionAttendee
     * @return $this
     */
    public function removeAdmissionAttendee($admissionAttendee)
    {
        $this->admissionAttendees->removeElement($admissionAttendee);

        return $this;
    }
}
