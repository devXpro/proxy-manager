<?php

namespace Xpro\ProxyBundle\Parser;

interface ParserInterface
{
    /**
     * @return string[]
     */
    public function getProxies();
    
    /**
     * @return string
     */
    public function getName();
}