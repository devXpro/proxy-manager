<?php

namespace Xpro\ProxyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Xpro\ProxyBundle\Entity\Parser;

class ParserRepository extends EntityRepository
{
    /**
     * @param array $names
     * @return Parser[]
     */
    public function getParsersByNames(array $names)
    {
        $qb = $this->createQueryBuilder('parser');

        return $qb->where($qb->expr()->in('parser.name', ':names'))
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Parser[]
     */
    public function getEnabledParsers()
    {
        $qb = $this->createQueryBuilder('parser');

        return $qb->where($qb->expr()->eq('parser.enabled', $qb->expr()->literal(true)))
            ->getQuery()
            ->getResult();
    }
}
