<?php

namespace Xpro\ProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProxyParserCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('xpro_proxy.parser.registry');
        $taggedServices = $container->findTaggedServiceIds('proxy_parser');
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addParser', [new Reference($id)]);
        }
    }
}
