<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use Mannion007\BestInvestments\Event\EventPublisher;

    require __DIR__ . '/../vendor/autoload.php';

    /** Bootstrap */
    $settings = require __DIR__ . '/../app/settings.php';
    $app = new \Slim\App($settings);
    EventPublisher::registerHandler($app->getContainer()->get('redis_handler'));

    /** Routes */
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

    $app->run();
