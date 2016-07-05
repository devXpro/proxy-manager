<?php

namespace Xpro\ProxyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Xpro\ProxyBundle\Entity\Proxy;

class ProxyRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getProxiesOnEnabledParsers()
    {
        $qb = $this->createQueryBuilder('proxy');

        return $qb->innerJoin('proxy.parser', 'parser')
            ->where($qb->expr()->eq('parser.enabled', $qb->expr()->literal(true)))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \DateTime $deadLine
     * @return array
     */
    public function getInactiveProxies(\DateTime $deadLine)
    {
        $qb = $this->createQueryBuilder('proxy');

        return $qb->where(
            $qb->expr()->orX(
                'proxy.updateAt IS NOT NULL AND proxy.lastActivity IS NULL AND proxy.addedAt < :deadLineAddedAd',
                'proxy.lastActivity < :deadline'
            )
        )
            ->setParameter('deadline', $deadLine)
            ->setParameter('deadLineAddedAd', $deadLine)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer $limit
     * @return Proxy[]
     */
    public function getActualProxies($limit)
    {
        $qb = $this->createQueryBuilder('proxy');
        return $qb->where($qb->expr()->eq('proxy.active', $qb->expr()->literal(true)))
            ->orderBy('proxy.lastActivity', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
