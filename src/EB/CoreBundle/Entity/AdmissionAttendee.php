<?php

namespace EB\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EB\UserBundle\Entity\StudentUser;

/**
 * AdmissionAttendee
 *
 * @ORM\Table(name="admission_attendee")
 * @ORM\Entity(repositoryClass="EB\CoreBundle\Repository\AdmissionAttendeeRepository")
 */
class AdmissionAttendee
{
    const RESULT_ATTENDED = 0;
    const RESULT_REJECTED_MANUALLY = 1; // rejected by the Ssu or by the command in case the components for computing the finalGrade are missing
    const RESULT_REJECTED = 2; // rejected by the command which computes the admission results
    const RESULT_ACCEPTED_FEE = 3;
    const RESULT_ACCEPTED_BUDGET = 4;

    /** @var array $resultArr */
    public static $resultArr = [
        self::RESULT_ATTENDED => 'Attended',
        self::RESULT_REJECTED_MANUALLY => 'Rejected Manually',
        self::RESULT_REJECTED => 'Rejected',
        self::RESULT_ACCEPTED_FEE => 'Accepted Fee',
        self::RESULT_ACCEPTED_BUDGET => 'Accepted Budget',
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
     * @var float
     *
     * @ORM\Column(name="admission_exam_grade", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $admissionExamGrade;

    /**
     * @var float
     *
     * @ORM\Column(name="baccalaureate_average_grade", type="decimal", precision=4, scale=2)
     */
    private $baccalaureateAverageGrade;

    /**
     * @var float
     *
     * @ORM\Column(name="baccalaureate_maximum_grade", type="decimal", precision=4, scale=2)
     */
    private $baccalaureateMaximumGrade;

    /**
     * @var float
     *
     * @ORM\Column(name="final_grade", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $finalGrade;

    /**
     * @var bool
     *
     * @ORM\Column(name="verified", type="boolean")
     */
    private $verified = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="result", type="integer", nullable=true)
     */
    private $result;

    /**
     * @var Admission
     *
     * @ORM\ManyToOne(targetEntity="EB\CoreBundle\Entity\Admission", inversedBy="admissionAttendees", cascade={"persist"})
     */
    private $admission;

    /**
     * @var StudentUser
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\StudentUser", inversedBy="admissionAttendees", cascade={"persist"})
     */
    private $studentUser;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->result = self::RESULT_ATTENDED;
    }

    public function __toString()
    {
        return $this->getResultAsString();
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
     * @return float
     */
    public function getAdmissionExamGrade()
    {
        return $this->admissionExamGrade;
    }

    /**
     * @param float $admissionExamGrade
     * @return $this
     */
    public function setAdmissionExamGrade($admissionExamGrade)
    {
        $this->admissionExamGrade = $admissionExamGrade;

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
     * @return float
     */
    public function getFinalGrade()
    {
        return $this->finalGrade;
    }

    /**
     * @param float $finalGrade
     * @return $this
     */
    public function setFinalGrade($finalGrade)
    {
        $this->finalGrade = $finalGrade;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     * @return $this
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param int $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultAsString()
    {
        return self::$resultArr[$this->result];
    }

    /**
     * @return Admission
     */
    public function getAdmission()
    {
        return $this->admission;
    }

    /**
     * @param Admission $admission
     * @return $this
     */
    public function setAdmission($admission)
    {
        $this->admission = $admission;

        return $this;
    }

    /**
     * @return StudentUser
     */
    public function getStudentUser()
    {
        return $this->studentUser;
    }

    /**
     * @param StudentUser $studentUser
     * @return $this
     */
    public function setStudentUser($studentUser)
    {
        $this->studentUser = $studentUser;

        return $this;
    }
}
