<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Mannion007\BestInvestments\Infrastructure\Storage\InMemoryProjectRepositoryAdapter;
use Mannion007\BestInvestments\Application\ProjectService;
use Mannion007\BestInvestments\Event\EventPublisher;
use Mannion007\BestInvestments\Event\InMemoryHandler;

require __DIR__ . '/../../vendor/autoload.php';

/** Bootstrap */
$container = new \Slim\Container;
$container['in_memory_project_repository'] = function () {
    return new InMemoryProjectRepositoryAdapter();
};
$container['project_service'] = function ($container) {
    return new ProjectService($container['in_memory_project_repository']);
};
$container['project_service'] = function ($container) {
    return new ProjectService($container['in_memory_project_repository']);
};
EventPublisher::registerHandler(new InMemoryHandler());
$app = new \Slim\App($container);

/** Routes */
$app->post(
    '/project/setup',
    function (Request $request, Response $response) {
        $projectReference = $this->get('project_service')->setUpProject(
            $request->getParsedBody()['client-id'],
            $request->getParsedBody()['name'],
            $request->getParsedBody()['deadline']
        );
        $response->getBody()->write($projectReference);
        return $response;
    }
);

$app->run();
