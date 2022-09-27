<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Command;

use Doctrine\Common\Util\Debug;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueryObjectQueryCommand extends Command
{
    private const OPTION_ARGS = 'args';

    private QueryExecutorInterface $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->queryExecutor = $queryExecutor;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('imatic:data:query-object-query')
            ->setDescription('Execute query defined in query object')
            ->addArgument('class', InputArgument::REQUIRED, 'Query object class')
            ->addOption(self::OPTION_ARGS, null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'List of arguments to pass into query object');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $class = $input->getArgument('class');
        $args = $input->getOption(self::OPTION_ARGS);

        $classRef = new \ReflectionClass($class);
        $constructorRef = $classRef->getConstructor();
        $numRequiredArgs = $constructorRef ? $constructorRef->getNumberOfRequiredParameters() : 0;

        if ($numRequiredArgs > \count($args)) {
            throw new \InvalidArgumentException(\sprintf(
                'Not enough arguments - %d given, %d required',
                \count($args),
                $numRequiredArgs
            ));
        }

        $queryObject = $classRef->newInstanceArgs($args);

        $result = $this->queryExecutor->execute($queryObject);

        $output->writeln(Debug::dump($result, 2, false, false));

        return Command::SUCCESS;
    }
}
