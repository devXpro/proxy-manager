<?php

namespace Xpro\ProxyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseProxiesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xpro:proxy:parse')
            ->setDescription('Save new proxies from all enabled parsers');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $proxyProcessor = $container->get('xpro_proxy.processor.parsing_proxy');
        $this->addLine($output);
        $output->writeln('<info>       Start Processing...</info>');
        $this->addLine($output);
        $count = $proxyProcessor->process();
        if (!$count) {
            $output->writeln('<info>       Parsers was not found any new proxies</info>');
        } else {
            $output->writeln(sprintf('<info>       Parsers was found <fg=magenta>%s</> new proxies</info>', $count));
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
