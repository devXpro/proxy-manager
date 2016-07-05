<?php

namespace Xpro\ProxyBundle\Provider;

use Symfony\Bridge\Doctrine\ManagerRegistry;

class ActualProxyProvider
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param integer $limit
     * @return array
     */
    public function getActualProxies($limit)
    {
        $proxies = $this->registry->getRepository('XproProxyBundle:Proxy')
            ->getActualProxies($limit);
        $result = [];
        foreach ($proxies as $proxy) {
            $result[] = $proxy->getIp();
        }

        return $result;
    }

    /**
     * @return string|false
     */
    public function getActualProxy()
    {
        $proxies = $this->getActualProxies(1);

        return isset($proxies[0]) ? $proxies[0] : false;
    }
}
