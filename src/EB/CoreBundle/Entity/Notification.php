<?php

namespace EB\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EB\UserBundle\Entity\AbstractUser;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="EB\CoreBundle\Repository\NotificationRepository")
 */
class Notification
{
    const TYPE_STUDENT_REGISTERED = 1;
    const TYPE_STUDENT_ATTEND_ADMISSION = 2;
    const TYPE_SSU_CONFIRM_STUDENT = 3; // SchoolStaffUser instance confirms a Student which attend an admission

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
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var AbstractUser
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\AbstractUser", inversedBy="sentNotifications")
     */
    private $senderUser;

    /**
     * @var AbstractUser
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\AbstractUser", inversedBy="receivedNotifications")
     */
    private $receiverUser;

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
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @param boolean $isRead
     * @return $this
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return AbstractUser
     */
    public function getSenderUser()
    {
        return $this->senderUser;
    }

    /**
     * @param AbstractUser $senderUser
     * @return $this
     */
    public function setSenderUser($senderUser)
    {
        $this->senderUser = $senderUser;

        return $this;
    }

    /**
     * @return AbstractUser
     */
    public function getReceiverUser()
    {
        return $this->receiverUser;
    }

    /**
     * @param AbstractUser $receiverUser
     * @return $this
     */
    public function setReceiverUser($receiverUser)
    {
        $this->receiverUser = $receiverUser;

        return $this;
    }
}
