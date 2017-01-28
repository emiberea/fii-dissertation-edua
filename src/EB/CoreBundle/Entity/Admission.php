<?php

namespace EB\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Admission
 *
 * @ORM\Table(name="admission")
 * @ORM\Entity(repositoryClass="EB\CoreBundle\Repository\AdmissionRepository")
 */
class Admission
{
    const STATUS_OPEN = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_CLOSED = 2;

    /** @var array $statusArr */
    public static $statusArr = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_CLOSED => 'Closed',
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
     * @var \DateTime
     *
     * @ORM\Column(name="session_date", type="date")
     */
    private $sessionDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="budget_financed_no", type="integer")
     */
    private $budgetFinancedNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="fee_payer_no", type="integer")
     */
    private $feePayerNo;

    /**
     * @var float
     *
     * @ORM\Column(name="budget_fee_threshold", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $budgetFeeThreshold;

    /**
     * @var float
     *
     * @ORM\Column(name="fee_rejected_threshold", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $feeRejectedThreshold;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closed_at", type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var School
     *
     * @ORM\ManyToOne(targetEntity="EB\CoreBundle\Entity\School", inversedBy="admissions")
     */
    private $school;

    /**
     * @var ArrayCollection|AdmissionAttendee[]
     *
     * @ORM\OneToMany(targetEntity="EB\CoreBundle\Entity\AdmissionAttendee", mappedBy="admission")
     */
    private $admissionAttendees;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = self::STATUS_OPEN;
        $this->admissionAttendees = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->sessionDate->format('Y-m-d') . ' (' . $this->budgetFinancedNo . '/' . $this->feePayerNo . ')';
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
     * @return \DateTime
     */
    public function getSessionDate()
    {
        return $this->sessionDate;
    }

    /**
     * @param \DateTime $sessionDate
     * @return $this
     */
    public function setSessionDate($sessionDate)
    {
        $this->sessionDate = $sessionDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getBudgetFinancedNo()
    {
        return $this->budgetFinancedNo;
    }

    /**
     * @param int $budgetFinancedNo
     * @return $this
     */
    public function setBudgetFinancedNo($budgetFinancedNo)
    {
        $this->budgetFinancedNo = $budgetFinancedNo;

        return $this;
    }

    /**
     * @return int
     */
    public function getFeePayerNo()
    {
        return $this->feePayerNo;
    }

    /**
     * @param int $feePayerNo
     * @return $this
     */
    public function setFeePayerNo($feePayerNo)
    {
        $this->feePayerNo = $feePayerNo;

        return $this;
    }

    /**
     * @return float
     */
    public function getBudgetFeeThreshold()
    {
        return $this->budgetFeeThreshold;
    }

    /**
     * @param float $budgetFeeThreshold
     * @return $this
     */
    public function setBudgetFeeThreshold($budgetFeeThreshold)
    {
        $this->budgetFeeThreshold = $budgetFeeThreshold;

        return $this;
    }

    /**
     * @return float
     */
    public function getFeeRejectedThreshold()
    {
        return $this->feeRejectedThreshold;
    }

    /**
     * @param float $feeRejectedThreshold
     * @return $this
     */
    public function setFeeRejectedThreshold($feeRejectedThreshold)
    {
        $this->feeRejectedThreshold = $feeRejectedThreshold;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime $closedAt
     * @return $this
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusAsString()
    {
        return self::$statusArr[$this->status];
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
