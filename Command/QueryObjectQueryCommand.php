<?php

namespace Imatic\Bundle\DataBundle\Command;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueryObjectQueryCommand extends ContainerAwareCommand
{
    const OPTION_ARGS = 'args';

    protected function configure()
    {
        $this
            ->setName('imatic:data:query-object-query')
            ->setDescription('Execute query defined in query object')
            ->addArgument('class', InputArgument::REQUIRED, 'Query object class')
            ->addOption(static::OPTION_ARGS, null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'List of arguments to pass into query object')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');
        $args = $input->getOption(static::OPTION_ARGS);

        $classRef = new \ReflectionClass($class);
        $constructorRef = $classRef->getConstructor();
        $numRequiredArgs = $constructorRef ? $constructorRef->getNumberOfRequiredParameters() : 0;

        if ($numRequiredArgs > count($args)) {
            throw new \InvalidArgumentException(sprintf(
                'Not enough arguments - %d given, %d required',
                count($args),
                $numRequiredArgs
            ));
        }

        $queryObject = $classRef->newInstanceArgs($args);

        $queryExecutor = $this->getContainer()->get('imatic_data.query_executor');
        $result = $queryExecutor->execute($queryObject);

        $output->writeln(Debug::dump($result, 2, false, false));
    }
}
