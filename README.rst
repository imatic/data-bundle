ImaticDataBundle
================

Vytvoření command handleru
--------------------------

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler;

    use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
    use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
    use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
    use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
    use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery;

    class UserDeactivateHandler implements HandlerInterface
    {
        /**
         * @var QueryExecutorInterface
         */
        private $queryExecutor;

        /**
         * @var ObjectManagerInterface
         */
        private $objectManager;

        /**
         * @param QueryExecutorInterface $queryExecutor
         * @param ObjectManagerInterface $objectManager
         */
        public function __construct(QueryExecutorInterface $queryExecutor, ObjectManagerInterface $objectManager)
        {
            $this->queryExecutor = $queryExecutor;
            $this->objectManager = $objectManager;
        }

        /**
         * @param CommandInterface $pathCommand
         */
        public function handle(CommandInterface $pathCommand)
        {
            $user = $this->queryExecutor->findOne(new UserQuery($pathCommand->getParameter('id')));
            $user->deactivate();

            $this->objectManager->flush();
        }
    }

Registrace command handleru
---------------------------

.. sourcecode:: yaml

    services:
        app_imatic_data.handler.user_deactivate_handler:
            class: Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler
            arguments:
                - @imatic_data.query_executor
                - @imatic_data.object_manager
            tags:
                - { name: imatic_data.handler, alias: user.deactivate }

Provedení commandu
------------------

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\ImaticBundle\Controller;

    class UserController
    {
        public function deactivateUserAction($id)
        {
            $command = new Command('user.deactivate', ['id' => $id]);
            $result = $this->get('imatic_data.command_executor')->execute($command);

            return new JsonResponse([
                'success' => $result->isSuccessful(),
            ]);
        }
    }

Todo
----
- DisplayCriteria param converter (+ reseni pro komponenty)
