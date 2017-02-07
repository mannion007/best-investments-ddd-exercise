<?php
use Mannion007\BestInvestments\Infrastructure\Storage\InMemoryProjectRepositoryAdapter;
use Mannion007\BestInvestments\Infrastructure\Storage\RedisProjectRepositoryAdapter;
use Mannion007\BestInvestments\Application\ProjectService;
use Mannion007\BestInvestments\Event\InMemoryHandler;
use Mannion007\BestInvestments\Event\RedisHandler;

$parameters = [
    'base_uri' => 'http://127.0.0.1:8888',
    'redis_project_repository_host' => '127.0.0.1',
    'redis_project_repository_port' => 6379,
    'redis_project_view_host' => '127.0.0.1',
    'redis_project_view_port' => 6379,
    'redis_event_handler_host' => '127.0.0.1',
    'redis_event_handler_port' => 6379
];

$services = [
    'in_memory_project_repository' => function () {
        return new InMemoryProjectRepositoryAdapter();
    },
    'redis_project_repository' => function ($container) {
        return new RedisProjectRepositoryAdapter(
            $container['redis_project_repository_host'],
            $container['redis_project_repository_port']
        );
    },
    'project_service' => function ($container) {
        return new ProjectService($container['redis_project_repository']);
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
