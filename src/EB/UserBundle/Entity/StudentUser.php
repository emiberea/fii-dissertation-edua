<?php

namespace EB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
}
