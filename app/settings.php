<?php
use Mannion007\BestInvestments\ProjectManagement\Service\ProjectService;
use Mannion007\BestInvestments\ProjectManagement\Service\SpecialistService;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage\RedisProjectRepositoryAdapter;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage\RedisPotentialSpecialistRepositoryAdapter;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage\RedisSpecialistRepositoryAdapter;
use Mannion007\BestInvestments\Prospecting\CommandHandler\RegisterProspectHandler;
use Mannion007\BestInvestments\Prospecting\CommandHandler\ReceiveProspectHandler;
use Mannion007\BestInvestments\Prospecting\CommandHandler\ChaseUpProspectHandler;
use Mannion007\BestInvestments\Prospecting\CommandHandler\DeclareProspectNotInterestedHandler;
use Mannion007\BestInvestments\Prospecting\CommandHandler\GiveUpOnProspectHandler;
use Mannion007\BestInvestments\Prospecting\Infrastructure\Storage\RedisProspectRepositoryAdapter;
use Mannion007\BestInvestments\ProjectManagement\Listener\JoinUpSpecialistListener;
use Mannion007\BestInvestments\ProjectManagement\Listener\PutClientProjectsOnHoldListener;
use Mannion007\BestInvestments\ProjectManagement\Listener\ReactivateClientProjectsListener;
use Mannion007\BestInvestments\Prospecting\Infrastructure\EventPublisher\RedisEventPublisher
    as ProspectingRedisEventPublisher;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\EventPublisher\RedisEventPublisher
    as ProjectManagementRedisEventPublisher;
use Mannion007\BestInvestments\EventListener\TransactionSucceededListener;
use Mannion007\BestInvestments\Event\TransactionSucceededEvent;
use Mannion007\BestInvestments\EventPublisher\SymfonyDispatcherEventPublisher;
use Mannion007\BestInvestments\EventPublisher\BufferPublisher;

$parameters = [
    'project_management_base_uri' => 'http://127.0.0.1:8888/project_management_endpoint.php',
    'prospecting_base_uri' => 'http://127.0.0.1:8888/prospecting_endpoint.php',
    'base_uri' => 'http://127.0.0.1:8888',
    'redis_project_repository_host' => '127.0.0.1',
    'redis_project_repository_port' => 6379,
    'redis_project_view_host' => '127.0.0.1',
    'redis_project_view_port' => 6379,
    'redis_potential_specialist_repository_host' => '127.0.0.1',
    'redis_potential_specialist_repository_port' => 6379,
    'redis_potential_specialist_view_host' => '127.0.0.1',
    'redis_potential_specialist_view_port' => 6379,
    'redis_specialist_repository_host' => '127.0.0.1',
    'redis_specialist_repository_port' => 6379,
    'redis_specialist_view_host' => '127.0.0.1',
    'redis_specialist_view_port' => 6379,
    'redis_prospect_repository_host' => '127.0.0.1',
    'redis_prospect_repository_port' => 6379,
    'redis_prospect_view_host' => '127.0.0.1',
    'redis_prospect_view_port' => 6379,
    'redis_event_handler_host' => '127.0.0.1',
    'redis_event_handler_port' => 6379,
];

$services = [
    'redis_project_repository' => function ($container) {
        return new RedisProjectRepositoryAdapter(
            $container['redis_project_repository_host'],
            $container['redis_project_repository_port'],
            $container['symfony_dispatcher_event_publisher']
        );
    },
    'redis_potential_specialist_repository' => function ($container) {
        return new RedisPotentialSpecialistRepositoryAdapter(
            $container['redis_potential_specialist_repository_host'],
            $container['redis_potential_specialist_repository_port'],
            $container['symfony_dispatcher_event_publisher']
        );
    },
    'redis_specialist_repository' => function ($container) {
        return new RedisSpecialistRepositoryAdapter(
            $container['redis_specialist_repository_host'],
            $container['redis_specialist_repository_port'],
            $container['symfony_dispatcher_event_publisher']
        );
    },
    'redis_prospect_repository' => function ($container) {
        return new RedisProspectRepositoryAdapter(
            $container['redis_prospect_repository_host'],
            $container['redis_prospect_repository_port'],
            $container['symfony_dispatcher_event_publisher']
        );
    },
    'project_service' => function ($container) {
        return new ProjectService($container['redis_project_repository']);
    },
    'specialist_service' => function ($container) {
        return new SpecialistService(
            $container['redis_potential_specialist_repository'],
            $container['redis_specialist_repository']
        );
    },
    'prospecting_redis_publisher' => function ($container) {
        return new ProspectingRedisEventPublisher(
            $container['redis_event_handler_host'],
            $container['redis_event_handler_port']
        );
    },
    'project_management_redis_publisher' => function ($container) {
        return new ProjectManagementRedisEventPublisher(
            $container['redis_event_handler_host'],
            $container['redis_event_handler_port']
        );
    },
    'register_prospect_handler' => function ($container) {
        return new RegisterProspectHandler(
            $container['redis_prospect_repository']
        );
    },
    'receive_prospect_handler' => function ($container) {
        return new ReceiveProspectHandler(
            $container['redis_prospect_repository']
        );
    },
    'chase_up_prospect_handler' => function ($container) {
        return new ChaseUpProspectHandler(
            $container['redis_prospect_repository']
        );
    },
    'declare_prospect_not_interested_handler' => function ($container) {
        return new DeclareProspectNotInterestedHandler(
            $container['redis_prospect_repository']
        );
    },
    'give_up_on_prospect_handler' => function ($container) {
        return new GiveUpOnProspectHandler(
            $container['redis_prospect_repository']
        );
    },
    'join_up_specialist_listener' => function ($container) {
        return new JoinUpSpecialistListener(
            $container['specialist_service']
        );
    },
    'put_client_projects_on_hold_listener' => function ($container) {
        return new PutClientProjectsOnHoldListener(
            $container['redis_project_repository']
        );
    },
    'reactivate_client_projects_listener' => function ($container) {
        return new ReactivateClientProjectsListener(
            $container['redis_project_repository']
        );
    },
    'transaction_succeeded_listener' => function ($container) {
        return new TransactionSucceededListener(
            $container['buffer_publisher'],
            $container['project_management_redis_publisher']
        );
    },
    'command_dispatcher' => function () {
        return new \Symfony\Component\EventDispatcher\EventDispatcher();
    },
    'symfony_event_dispatcher' => function ($container) {
        $dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $dispatcher->addListener(
            TransactionSucceededEvent::NAME,
            [$container['transaction_succeeded_listener'], 'handle']
        );
        return $dispatcher;
    },
    'symfony_dispatcher_event_publisher' => function($container) {
        return new SymfonyDispatcherEventPublisher(
            $container['symfony_event_dispatcher']
        );
    },
    'buffer_publisher' => function () {
        return new BufferPublisher();
    },
];

return array_merge($parameters, $services);
