<?php
use Mannion007\BestInvestments\ProjectManagement\Application\ProjectService;
use Mannion007\BestInvestments\ProjectManagement\Application\SpecialistService;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage\RedisProjectRepositoryAdapter;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage\RedisPotentialSpecialistRepositoryAdapter;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage\RedisSpecialistRepositoryAdapter;
use Mannion007\BestInvestments\Prospecting\Application\CommandHandler\RegisterProspectHandler;
use Mannion007\BestInvestments\Prospecting\Application\CommandHandler\ReceiveProspectHandler;
use Mannion007\BestInvestments\Prospecting\Application\CommandHandler\ChaseUpProspectHandler;
use Mannion007\BestInvestments\Prospecting\Application\CommandHandler\DeclareProspectNotInterestedHandler;
use Mannion007\BestInvestments\Prospecting\Application\CommandHandler\GiveUpOnProspectHandler;
use Mannion007\BestInvestments\Prospecting\Infrastructure\Storage\RedisProspectRepositoryAdapter;
use Mannion007\BestInvestments\Event\RedisEventPublisher;

$parameters = [
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
            $container['redis_project_repository_port']
        );
    },
    'redis_potential_specialist_repository' => function ($container) {
        return new RedisPotentialSpecialistRepositoryAdapter(
            $container['redis_potential_specialist_repository_host'],
            $container['redis_potential_specialist_repository_port']
        );
    },
    'redis_specialist_repository' => function ($container) {
        return new RedisSpecialistRepositoryAdapter(
            $container['redis_specialist_repository_host'],
            $container['redis_specialist_repository_port']
        );
    },
    'redis_prospect_repository' => function ($container) {
        return new RedisProspectRepositoryAdapter(
            $container['redis_prospect_repository_host'],
            $container['redis_prospect_repository_port']
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
    'redis_publisher' => function ($container) {
        return new RedisEventPublisher(
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
    'command_dispatcher' => function ($container) {
        return new \Symfony\Component\EventDispatcher\EventDispatcher();
    }
];

return array_merge($parameters, $services);
