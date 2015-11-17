<?php

namespace UserBundle\Entity\Repository;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface {

    /**
     * Get all Users
     * @return array
     */
    public function getAll() {
        $qb = $this->createQueryBuilder('u')
            ->select('u, r')
            ->innerJoin('u.roles', 'r')
            ->groupBy('u.id');

        return $qb->getQuery()->getResult();
    }

    public function getAllNum($status) {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u)')
            ->where('u.isActive = :status')
            ->setParameter('status', $status);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get user Hosts
     * @return array
     */
    public function getHosts() {
        $qb = $this->createQueryBuilder('u')
            ->select('u, r')
            ->innerJoin('u.roles', 'r')
            ->where('r.role = :role')
            ->setParameter('role', 'ROLE_HOST');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $username
     * @return mixed|UserInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username) {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.roles', 'r')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin GaussUserBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return null|object|UserInterface
     */
    public function refreshUser(UserInterface $user) {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class) {
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }


    public function getByHash($username, $hash) {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.username = :username AND u.hash = :hash')
            ->setParameter('username', $username)
            ->setParameter('hash', $hash)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $qb->getSingleResult();
        } catch (\Exception $e) {
            $user = false;
        }

        return $user;
    }
}