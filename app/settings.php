<?php
use Mannion007\BestInvestments\Infrastructure\Storage\RedisProjectRepositoryAdapter;
use Mannion007\BestInvestments\Infrastructure\Storage\RedisPotentialSpecialistRepositoryAdapter;
use Mannion007\BestInvestments\Infrastructure\Storage\RedisSpecialistRepositoryAdapter;
use Mannion007\BestInvestments\Application\ProjectService;
use Mannion007\BestInvestments\Application\SpecialistService;
use Mannion007\BestInvestments\Event\InMemoryHandler;
use Mannion007\BestInvestments\Event\RedisHandler;

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
    'redis_event_handler_host' => '127.0.0.1',
    'redis_event_handler_port' => 6379
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
    'project_service' => function ($container) {
        return new ProjectService($container['redis_project_repository']);
    },
    'specialist_service' => function ($container) {
        return new SpecialistService(
            $container['redis_potential_specialist_repository'],
            $container['redis_specialist_repository']
        );
    },
    'in_memory_handler' => function () {
        return new InMemoryHandler();
    },
    'redis_handler' => function ($container) {
        return new RedisHandler(
            $container['redis_event_handler_host'],
            $container['redis_event_handler_port']
        );
    }
];

return array_merge($parameters, $services);
