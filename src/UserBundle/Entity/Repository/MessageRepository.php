<?php

namespace UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository {

    /**
     * Get conversation by userTo
     * count new messages -> newNum
     * @param $userId
     * @return array
     */
    public function getConversations($userId) {
        $qb = $this->createQueryBuilder('m')
            ->select('m as messages, u, sum(m.new) as newNum')
            ->innerJoin('m.userFrom', 'u')
            ->where('m.userTo = :userTo')
            ->orderBy('newNum', 'DESC')
            ->setParameter('userTo', $userId)
            ->groupBy('m.userFrom');

        return $qb->getQuery()->getResult();
    }

    /**
     * Get conversation
     * @param $userTo
     * @param $userFrom
     * @return array
     */
    public function getConversation($userTo, $userFrom) {
        $qb = $this->createQueryBuilder('m')
        ->select('m, u')
        ->innerJoin('m.userFrom', 'u')
        ->where('m.userTo = :userTo AND m.userFrom = :userFrom')
        ->orWhere('m.userTo = :userFrom AND m.userFrom = :userTo')
        ->orderBy('m.created', 'DESC')
        ->setParameter('userTo', $userTo)
        ->setParameter('userFrom', $userFrom);

        return $qb->getQuery()->getResult();
    }

    /**
     * Set all messages in conversation as NOT new and return them
     * @param $userFrom
     * @param $userTo
     * @return mixed
     */
    public function updateConversationRead($userFrom, $userTo) {
        $this->createQueryBuilder('m')
            ->update('UserBundle:Message m')
            ->set('m.new', 0)
            ->where('m.userFrom = :userFrom')
            ->andWhere('m.userTo = :userTo')
            ->setParameter('userFrom', $userFrom)
            ->setParameter('userTo', $userTo)
            ->getQuery()
            ->execute();

        $qb = $this->createQueryBuilder('m')
            ->select('m, u')
            ->innerJoin('m.userFrom', 'u')
            ->where('m.userTo = :userTo AND m.userFrom = :userFrom')
            ->orWhere('m.userTo = :userFrom AND m.userFrom = :userTo')
            ->orderBy('m.created', 'DESC')
            ->setParameter('userTo', $userTo)
            ->setParameter('userFrom', $userFrom);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get new messages numeric
     * @param $user
     * @param int $limit
     * @return mixed
     */
    public function getNewMessagesNum($user) {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->innerJoin('m.userTo', 'u')
            ->where('u = :user')
            ->andWhere('m.new = :new')
            ->orderBy('m.created', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('new', 1);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get last 5 messages
     * @param $user
     * @param int $limit
     * @return array
     */
    public function getNewMessages($user, $limit = 5) {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->innerJoin('m.userTo', 'u')
            ->innerJoin('m.userFrom', 'userFrom')
            ->where('u = :user')
            ->addOrderBy('m.new', 'DESC')
            ->addOrderBy('m.created', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

}
