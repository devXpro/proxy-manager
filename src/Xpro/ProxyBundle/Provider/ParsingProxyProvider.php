<?php

namespace Xpro\ProxyBundle\Provider;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\ManagerRegistry;

use Xpro\ProxyBundle\Model\DTO\ProxyDTO;
use Xpro\ProxyBundle\Parser\ParserInterface;
use Xpro\ProxyBundle\Parser\ParserRegistry;

class ParsingProxyProvider
{
    /** @var  ParserRegistry */
    protected $parserRegistry;

    /** @var  ManagerRegistry */
    protected $registry;

    /**
     * ParsingProxyProvider constructor.
     * @param ParserRegistry $parserRegistry
     * @param ManagerRegistry $registry
     */
    public function __construct(ParserRegistry $parserRegistry, ManagerRegistry $registry)
    {
        $this->parserRegistry = $parserRegistry;
        $this->registry = $registry;
    }

    /**
     * @return ArrayCollection|ProxyDTO[]
     */
    public function getProxies()
    {
        $parsers = $this->parserRegistry->getEnabledParsers();
        /** @var  $proxies */
        $proxies = new ArrayCollection();
        foreach ($parsers as $parser) {
            $this->addUniqueProxies($proxies, $parser);
        }

        return $proxies;
    }

    /**
     * @param ArrayCollection|ProxyDTO[] $proxies
     * @param ParserInterface $parser
     */
    protected function addUniqueProxies(ArrayCollection $proxies, ParserInterface $parser)
    {
        foreach ($parser->getProxies() as $newProxy) {
            $exist = false;
            foreach ($proxies as $existProxy) {
                if ($existProxy->getProxy() == $newProxy) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                $proxies->add(new ProxyDTO($newProxy, $parser->getName()));
            }
        }
    }
}
