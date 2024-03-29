<?php

namespace EB\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AdminUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdminUserRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function countAll()
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('count(a.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
