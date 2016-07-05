<?php

namespace Xpro\ProxyBundle\Processor;

use Guzzle\Http\Client;

use Symfony\Bridge\Doctrine\ManagerRegistry;

use Xpro\ProxyBundle\Entity\Proxy;

class CheckProxyProcessor
{
    /** @var  ManagerRegistry */
    protected $registry;

    /**
     * CheckProxyProcessor constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param Proxy $proxy
     * @param string $testedUrl
     * @param int $timeout
     * @return bool
     */
    public function process(Proxy $proxy, $testedUrl, $timeout = 3)
    {
        $client = new Client();
        $client->setDefaultOption('proxy', $proxy->getIp());
        $client->getConfig()->set('curl.options', [CURLOPT_TIMEOUT => $timeout]);
        $now = new \DateTime('now');
        $connectionResult = true;
        try {
            $client->createRequest('GET', $testedUrl)->send();
            $proxy->setLastActivity($now);
            $proxy->setActive(true);
        } catch (\Exception $e) {
            $connectionResult = false;
            $proxy->setActive(false);
        }
        $proxy->setUpdateAt($now);
        $em = $this->registry->getManager();
        $em->flush();

        return $connectionResult;
    }
}
