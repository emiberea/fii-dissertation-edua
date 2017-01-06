<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
}
