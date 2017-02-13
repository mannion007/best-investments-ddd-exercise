<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use Mannion007\BestInvestments\Event\EventPublisher;
    use Mannion007\BestInvestments\Command\Command;

    require __DIR__ . '/../vendor/autoload.php';

    /** Bootstrap */
    $settings = require __DIR__ . '/../app/settings.php';
    $app = new \Slim\App($settings);
    EventPublisher::registerPublisher($app->getContainer()->get('project_management_redis_publisher'));

    /** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
    $dispatcher = $app->getContainer()->get('command_dispatcher');
    $dispatcher->addListener('register_prospect', [$app->getContainer()->get('register_prospect_handler'), 'handle']);
    $dispatcher->addListener('receive_prospect', [$app->getContainer()->get('receive_prospect_handler'), 'handle']);
    $dispatcher->addListener('chase_up_prospect', [$app->getContainer()->get('chase_up_prospect_handler'), 'handle']);
    $dispatcher->addListener(
        'declare_prospect_not_interested',
        [$app->getContainer()->get('declare_prospect_not_interested_handler'), 'handle']
    );
    $dispatcher->addListener(
        'give_up_on_prospect',
        [$app->getContainer()->get('give_up_on_prospect_handler'), 'handle']
    );

    /** Prospecting routes */
    $app->put(
        '/prospect/receive/{prospect_id}',
        function (Request $request, Response $response, $args) {
            $command = new Command(
                'receive_prospect',
                [
                    'prospect_id' => $args['prospect_id'],
                    'name' => $request->getParsedBody()['name'],
                    'notes' => $request->getParsedBody()['notes']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/prospect/chase_up',
        function (Request $request, Response $response) {
            $command = new Command(
                'chase_up_prospect',
                [
                    'prospect_id' => $request->getParsedBody()['prospect_id']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/prospect/register',
        function (Request $request, Response $response) {
            $command = new Command(
                'register_prospect',
                [
                    'prospect_id' => $request->getParsedBody()['prospect_id'],
                    'hourly_rate' => $request->getParsedBody()['hourly_rate']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/prospect/declare_not_interested',
        function (Request $request, Response $response) {
            $command = new Command(
                'declare_prospect_not_interested',
                [
                    'prospect_id' => $request->getParsedBody()['prospect_id']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/prospect/give_up',
        function (Request $request, Response $response) {
            $command = new Command(
                'give_up_on_prospect',
                [
                    'prospect_id' => $request->getParsedBody()['prospect_id']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->get(
        '/prospect/{prospect_id}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_prospect_view_host'),
                $this->get('redis_prospect_view_port')
            );
            $prospectView = $redis->get(sprintf('%s-view', $args['prospect_id']));
            if (!$prospectView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($prospectView));
        }
    );

    $app->run();
