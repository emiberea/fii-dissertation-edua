<?php

namespace EB\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EB\CoreBundle\Entity\Notification;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "admin_user" = "EB\UserBundle\Entity\AdminUser",
 *     "school_staff_user" = "EB\UserBundle\Entity\SchoolStaffUser",
 *     "student_user" = "EB\UserBundle\Entity\StudentUser"
 * })
 */
abstract class AbstractUser extends BaseUser
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\CoreBundle\Entity\Notification", mappedBy="senderUser")
     */
    private $sentNotifications;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EB\CoreBundle\Entity\Notification", mappedBy="receiverUser")
     */
    private $receivedNotifications;

    public function __construct()
    {
        parent::__construct();
        $this->sentNotifications = new ArrayCollection();
        $this->receivedNotifications = new ArrayCollection();
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        parent::setUsername($email);
        parent::setEmail($email);

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName. ' ' . $this->lastName;
    }

    /**
     * @return ArrayCollection
     */
    public function getSentNotifications()
    {
        return $this->sentNotifications;
    }

    /**
     * @param Notification $sentNotification
     * @return $this
     */
    public function addSentNotification($sentNotification)
    {
        $this->sentNotifications->add($sentNotification);

        return $this;
    }

    /**
     * @param Notification $sentNotification
     * @return $this
     */
    public function removeSentNotification($sentNotification)
    {
        $this->sentNotifications->removeElement($sentNotification);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReceivedNotifications()
    {
        return $this->receivedNotifications;
    }

    /**
     * @param Notification $receivedNotification
     * @return $this
     */
    public function addReceivedNotification($receivedNotification)
    {
        $this->receivedNotifications->add($receivedNotification);

        return $this;
    }

    /**
     * @param Notification $receivedNotification
     * @return $this
     */
    public function removeReceivedNotification($receivedNotification)
    {
        $this->receivedNotifications->removeElement($receivedNotification);

        return $this;
    }
}
