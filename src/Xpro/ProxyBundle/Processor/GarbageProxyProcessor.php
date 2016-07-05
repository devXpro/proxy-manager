<?php

namespace Xpro\ProxyBundle\Processor;

use Symfony\Bridge\Doctrine\ManagerRegistry;

class GarbageProxyProcessor
{
    /** @var  ManagerRegistry */
    protected $registry;

    /**
     * GarbageProxyProcessor constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param \DateTime $deadLine
     * @return int
     */
    public function process(\DateTime $deadLine)
    {
        $em = $this->registry->getManager();
        $garbageProxies = $em->getRepository('XproProxyBundle:Proxy')->getInactiveProxies($deadLine);
        foreach ($garbageProxies as $proxy) {
            $em->remove($proxy);
        }
        $em->flush();

        return count($garbageProxies);
    }
}
