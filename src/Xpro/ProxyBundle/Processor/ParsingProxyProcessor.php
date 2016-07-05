<?php

namespace Xpro\ProxyBundle\Processor;

use Symfony\Bridge\Doctrine\ManagerRegistry;

use Doctrine\ORM\EntityNotFoundException;

use Xpro\ProxyBundle\Entity\Parser;
use Xpro\ProxyBundle\Entity\Proxy;
use Xpro\ProxyBundle\Model\DTO\ProxyDTO;
use Xpro\ProxyBundle\Provider\ParsingProxyProvider;

class ParsingProxyProcessor
{
    /** @var  ParsingProxyProvider */
    protected $parsingProxyProvider;

    /** @var  ManagerRegistry */
    protected $registry;

    /**
     * @param ParsingProxyProvider $parsingProxyProvider
     * @param ManagerRegistry $registry
     */
    public function __construct(ParsingProxyProvider $parsingProxyProvider, ManagerRegistry $registry)
    {
        $this->parsingProxyProvider = $parsingProxyProvider;
        $this->registry = $registry;
    }

    /**
     * @return integer counter of new proxies
     */
    public function process()
    {
        $proxies = $this->parsingProxyProvider->getProxies();

        return $this->addUniqueProxies($proxies);
    }

    /**
     * @param ProxyDTO[] $proxies
     * @return integer
     */
    protected function addUniqueProxies($proxies)
    {
        $existProxies = $this->registry->getRepository('XproProxyBundle:Proxy')->findAll();
        $anyNew = false;
        $parsers = $this->registry->getRepository('XproProxyBundle:Parser')->getEnabledParsers();
        $em = $this->registry->getManager();
        $newProxyCounter = 0;
        foreach ($proxies as $proxy) {
            if (!$this->exist($proxy, $existProxies)) {
                $anyNew = true;
                $proxyEntity = new Proxy();
                $proxyEntity->setActive(false);
                $proxyEntity->setIp($proxy->getProxy());
                $proxyEntity->setAddedAt(new \DateTime('now'));
                $proxyEntity->setParser($this->getParserByName($proxy->getParserName(), $parsers));
                $em->persist($proxyEntity);
                $newProxyCounter++;
            }
        }

        if ($anyNew) {
            $em->flush();
        }

        return $newProxyCounter;
    }

    /**
     * @param ProxyDTO $proxy
     * @param Proxy[] $proxies
     * @return bool
     */
    protected function exist($proxy, $proxies)
    {
        foreach ($proxies as $proxyEntity) {
            if ($proxyEntity->getIp() === $proxy->getProxy()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param Parser[] $parsers
     * @return Parser
     * @throws EntityNotFoundException
     */
    protected function getParserByName($name, $parsers)
    {
        foreach ($parsers as $parser) {
            if ($parser->getName() === $name) {
                return $parser;
            }
        }
        throw  new EntityNotFoundException('Proxy Entity fas not found');
    }
}
