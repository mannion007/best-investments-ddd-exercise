<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use Mannion007\BestInvestments\EventPublisher\EventPublisher;

    require __DIR__ . '/../vendor/autoload.php';

    /** Bootstrap */
    $settings = require __DIR__ . '/../app/settings.php';
    $app = new \Slim\App($settings);
    EventPublisher::registerPublisher($app->getContainer()->get('buffer_publisher'));

    /** Project Management Routes */
    $app->post(
        '/project/set_up',
        function (Request $request, Response $response) {
            $projectReference = $this->get('project_service')->setUpProject(
                $request->getParsedBody()['client_id'],
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
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['project_manager_id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/add_specialist',
        function (Request $request, Response $response) {
            $this->get('project_service')->addSpecialistToProject(
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['specialist_id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/approve_specialist',
        function (Request $request, Response $response) {
            $this->get('project_service')->approveSpecialistForProject(
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['specialist_id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/discard_specialist',
        function (Request $request, Response $response) {
            $this->get('project_service')->discardSpecialistFromProject(
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['specialist_id']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson([]);
        }
    );

    $app->post(
        '/project/schedule_consultation',
        function (Request $request, Response $response) {
            $consultationId = $this->get('project_service')->scheduleConsultationForProject(
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['specialist_id'],
                $request->getParsedBody()['time']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(['consultation_id' => $consultationId]);
        }
    );

    $app->post(
        '/project/report_consultation',
        function (Request $request, Response $response) {
            $this->get('project_service')->reportConsultationOnProject(
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['consultation_id'],
                $request->getParsedBody()['duration']
            );
            $response->withJson([]);
            return $response;
        }
    );

    $app->post(
        '/project/discard_consultation',
        function (Request $request, Response $response) {
            $this->get('project_service')->discardConsultationFromProject(
                $request->getParsedBody()['project_reference'],
                $request->getParsedBody()['consultation_id']
            );
            $response->withJson([]);
            return $response;
        }
    );

    $app->post(
        '/project/close',
        function (Request $request, Response $response) {
            $this->get('project_service')->closeProject(
                $request->getParsedBody()['project_reference']
            );
            $response->withJson([]);
            return $response;
        }
    );

    $app->get(
        '/project/{project_reference}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_project_view_host'),
                $this->get('redis_project_view_port')
            );
            $projectView = $redis->get(sprintf('%s-view', $args['project_reference']));
            if (!$projectView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($projectView));
        }
    );

    $app->post(
        '/potential_specialist/put_on_list',
        function (Request $request, Response $response) {
            $specialistId = $this->get('specialist_service')->putPotentialSpecialistOnList(
                $request->getParsedBody()['project_manager_id'],
                $request->getParsedBody()['name'],
                $request->getParsedBody()['notes']
            );
            $response = $response->withStatus(201);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(['specialist_id' => $specialistId]);
        }
    );

    $app->get(
        '/potential_specialist/{id}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_potential_specialist_view_host'),
                $this->get('redis_potential_specialist_view_port')
            );
            $projectView = $redis->get(sprintf('potential-specialist-%s-view', $args['id']));
            if (!$projectView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($projectView));
        }
    );

    $app->get(
        '/specialist/{id}',
        function (Request $request, Response $response, $args) {
            $redis = new \Redis();
            $redis->connect(
                $this->get('redis_specialist_view_host'),
                $this->get('redis_specialist_view_port')
            );
            $specialistView = $redis->get(sprintf('%s-view', $args['id']));
            if (!$specialistView) {
                return $response->withStatus(404);
            }
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->withJson(json_decode($specialistView));
        }
    );

    $app->run();
