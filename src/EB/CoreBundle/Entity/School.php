<?php

namespace EB\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EB\UserBundle\Entity\SchoolStaffUser;

/**
 * School
 *
 * @ORM\Table(name="school")
 * @ORM\Entity(repositoryClass="EB\CoreBundle\Repository\SchoolRepository")
 */
class School
{
    const TYPE_UNIVERSITY = 0;
    const TYPE_FACULTY = 1;
    const TYPE_HIGHSCHOOL = 2;
    const TYPE_SCHOOL = 3;

    /** @var array $typeArr */
    public static $typeArr = [
        self::TYPE_UNIVERSITY => 'University',
        self::TYPE_FACULTY => 'Faculty',
        self::TYPE_HIGHSCHOOL => 'HighSchool',
        self::TYPE_SCHOOL => 'School',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var ArrayCollection|SchoolStaffUser[]
     *
     * @ORM\OneToMany(targetEntity="EB\UserBundle\Entity\SchoolStaffUser", mappedBy="school")
     */
    private $schoolStaffUsers;

    /**
     * @var ArrayCollection|Admission[]
     *
     * @ORM\OneToMany(targetEntity="EB\CoreBundle\Entity\Admission", mappedBy="school")
     */
    private $admissions;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return ArrayCollection|Admission[]
     */
    public function getAdmissions()
    {
        return $this->admissions;
    }

    /**
     * @param Admission $admission
     * @return $this
     */
    public function addAdmission($admission)
    {
        $this->admissions->add($admission);

        return $this;
    }

    /**
     * @param Admission $admission
     * @return $this
     */
    public function removeAdmission($admission)
    {
        $this->admissions->removeElement($admission);

        return $this;
    }

    /**
     * @return ArrayCollection|SchoolStaffUser[]
     */
    public function getSchoolStaffUsers()
    {
        return $this->schoolStaffUsers;
    }

    /**
     * @param SchoolStaffUser $schoolStaffUser
     * @return $this
     */
    public function addSchoolStaffUser($schoolStaffUser)
    {
        $this->schoolStaffUsers->add($schoolStaffUser);

        return $this;
    }

    /**
     * @param SchoolStaffUser $schoolStaffUser
     * @return $this
     */
    public function removeSchoolStaffUser($schoolStaffUser)
    {
        $this->schoolStaffUsers->removeElement($schoolStaffUser);

        return $this;
    }
}
