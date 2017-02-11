<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use Mannion007\BestInvestments\Event\EventPublisher;
    use Mannion007\BestInvestments\Command\Command;

    require __DIR__ . '/../vendor/autoload.php';

    /** Bootstrap */
    $settings = require __DIR__ . '/../app/settings.php';
    $app = new \Slim\App($settings);
    EventPublisher::registerHandler($app->getContainer()->get('redis_handler'));

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

    /** Project Routes */
    $app->post(
        '/project/set-up',
        function (Request $request, Response $response) {
            $projectReference = $this->get('project_service')->setUpProject(
                $request->getParsedBody()['client-id'],
                $request->getParsedBody()['name'],
                $request->getParsedBody()['deadline']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(['project_reference' => $projectReference]);
        }
    );

    $app->post(
        '/project/start',
        function (Request $request, Response $response) {
            $this->get('project_service')->startProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['project-manager-id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/add-specialist',
        function (Request $request, Response $response) {
            $this->get('project_service')->addSpecialistToProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['specialist-id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/approve-specialist',
        function (Request $request, Response $response) {
            $this->get('project_service')->approveSpecialistForProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['specialist-id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/discard-specialist',
        function (Request $request, Response $response) {
            $this->get('project_service')->discardSpecialistFromProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['specialist-id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/schedule-consultation',
        function (Request $request, Response $response) {
            $consultationId = $this->get('project_service')->scheduleConsultationForProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['specialist-id'],
                $request->getParsedBody()['time']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(['consultation_id' => $consultationId]);
        }
    );

    $app->post(
        '/project/report-consultation',
        function (Request $request, Response $response) {
            $this->get('project_service')->reportConsultationOnProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['consultation-id'],
                $request->getParsedBody()['duration']
            );
            $response->getBody()->write('{}');
            return $response;
        }
    );

    $app->post(
        '/project/discard-consultation',
        function (Request $request, Response $response) {
            $this->get('project_service')->discardConsultationFromProject(
                $request->getParsedBody()['project-reference'],
                $request->getParsedBody()['consultation-id']
            );
            $response->getBody()->write('{}');
            return $response;
        }
    );

    $app->post(
        '/project/put-on-hold',
        function (Request $request, Response $response) {
            $this->get('project_service')->putProjectOnHold(
                $request->getParsedBody()['project-reference']
            );
            $response->getBody()->write('{}');
            return $response;
        }
    );

    $app->post(
        '/project/reactivate',
        function (Request $request, Response $response) {
            $this->get('project_service')->reactivateProject(
                $request->getParsedBody()['project-reference']
            );
            $response->getBody()->write('{}');
            return $response;
        }
    );

    $app->post(
        '/project/close',
        function (Request $request, Response $response) {
            $this->get('project_service')->closeProject(
                $request->getParsedBody()['project-reference']
            );
            $response->getBody()->write('{}');
            return $response;
        }
    );

    $app->get(
        '/project/{project-reference}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_project_view_host'),
                $this->get('redis_project_view_port')
            );
            $projectView = $redis->get(sprintf('%s-view', $args['project-reference']));
            if (!$projectView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($projectView));
        }
    );

    /** Specialist Routes */
    $app->post(
        '/potential-specialist/put-on-list',
        function (Request $request, Response $response) {
            $specialistId = $this->get('specialist_service')->putPotentialSpecialistOnList(
                $request->getParsedBody()['project-manager-id'],
                $request->getParsedBody()['name'],
                $request->getParsedBody()['notes']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(['specialist_id' => $specialistId]);
        }
    );

    $app->get(
        '/potential-specialist/{id}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_potential_specialist_view_host'),
                $this->get('redis_potential_specialist_view_port')
            );
            $projectView = $redis->get(sprintf('%s-view', $args['id']));
            if (!$projectView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($projectView));
        }
    );

    $app->put(
        '/prospect/receive/{prospect-id}',
        function (Request $request, Response $response, $args) {
            $command = new Command(
                'receive_prospect',
                [
                    'prospect-id' => $args['prospect-id'],
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
        '/prospect/chase-up',
        function (Request $request, Response $response) {
            $command = new Command(
                'chase_up_prospect',
                [
                    'prospect-id' => $request->getParsedBody()['prospect-id']
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
                    'prospect-id' => $request->getParsedBody()['prospect-id'],
                    'hourly-rate' => $request->getParsedBody()['hourly-rate']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/prospect/declare-not-interested',
        function (Request $request, Response $response) {
            $command = new Command(
                'declare_prospect_not_interested',
                [
                    'prospect-id' => $request->getParsedBody()['prospect-id']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/prospect/give-up',
        function (Request $request, Response $response) {
            $command = new Command(
                'give_up_on_prospect',
                [
                    'prospect-id' => $request->getParsedBody()['prospect-id']
                ]
            );
            $this->get('command_dispatcher')->dispatch($command->getName(), $command);
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->get(
        '/prospect/{prospect-id}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_prospect_view_host'),
                $this->get('redis_prospect_view_port')
            );
            $prospectView = $redis->get(sprintf('%s-view', $args['prospect-id']));
            if (!$prospectView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($prospectView));
        }
    );

    $app->run();
