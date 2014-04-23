<?php

namespace Imatic\Bundle\DataBundle\Command;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueryObjectQueryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('imatic:data:query-object-query')
            ->setDescription('Execute query defined in query object')
            ->addArgument('class', InputArgument::REQUIRED, 'Query object class');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queryExecutor = $this->getContainer()->get('imatic_data.query_executor');
        $class = $input->getArgument('class');
        $queryObject = new $class;
        $result = $queryExecutor->execute($queryObject);

        Debug::dump($result);
    }
}