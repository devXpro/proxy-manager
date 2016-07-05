<?php

namespace Xpro\ProxyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckProxiesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xpro:proxy:check')
            ->setDescription('All users processing');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $registry = $container->get('doctrine');
        $proxies = $registry->getRepository('XproProxyBundle:Proxy')->findAll();
        $this->addLine($output);
        $output->writeln('<info>       Start Processing...</info>');
        $this->addLine($output);
        foreach ($proxies as $proxy) {
            $result = $container->get('xpro_proxy.processor.check_proxy')->process($proxy, 'http://olx.ua', 3);
            $output->writeln(
                sprintf(
                    '<info>Proxy <fg=magenta> %s</> from<fg=magenta> %s </>is %s</info>',
                    $proxy->getIp(),
                    $proxy->getParser()->getName(),
                    $result ? 'good!' : 'bad!'
                )
            );
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function addLine(OutputInterface $output)
    {
        $output->writeln('<info>-------------------------------------------------------</info>');
    }
}
