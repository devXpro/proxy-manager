<?php

namespace Xpro\ProxyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GarbageProxyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xpro:proxy:garbage:clear')
            ->setDescription('Clear all inactive deprecated proxies')
            ->addArgument('dead_line', InputArgument::OPTIONAL);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $garbageProcessor = $container->get('xpro_proxy.processor.garbage');
        $this->addLine($output);
        $output->writeln('<info>       Start Processing...</info>');
        $this->addLine($output);
        $deadLineString = $input->getArgument('dead_line') ?: '1 hour';
        $deadLine = new \DateTime('now');
        $deadLine->modify('- '.$deadLineString);
        $count = $garbageProcessor->process($deadLine);
        if (!$count) {
            $output->writeln('<info>       All Proxies is good</info>');
        } else {
            $output->writeln(
                sprintf('<info>       Garbage collector was clear <fg=magenta>%s</> bad proxies</info>', $count)
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
