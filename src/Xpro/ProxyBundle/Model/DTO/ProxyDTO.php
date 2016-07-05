<?php

namespace Xpro\ProxyBundle\Model\DTO;

class ProxyDTO
{
    /** @var  string */
    private $proxy;
    
    /** @var  string */
    private $parserName;

    /**
     * ProxyDTO constructor.
     * @param string $proxy
     * @param string $parserName
     */
    public function __construct($proxy, $parserName)
    {
        $this->proxy = $proxy;
        $this->parserName = $parserName;
    }

    /**
     * @return string
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @return string
     */
    public function getParserName()
    {
        return $this->parserName;
    }
}
