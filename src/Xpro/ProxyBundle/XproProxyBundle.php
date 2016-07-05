<?php

namespace Xpro\ProxyBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Xpro\ProxyBundle\DependencyInjection\Compiler\ProxyParserCompilerPass;
use Xpro\ProxyBundle\DependencyInjection\XproProxyExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class XproProxyBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new XproProxyExtension();
    }
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ProxyParserCompilerPass());
    }
}
