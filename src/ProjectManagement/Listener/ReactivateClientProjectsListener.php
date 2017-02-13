<?php

namespace Mannion007\BestInvestments\ProjectManagement\Listener;

use Mannion007\BestInvestments\Event\EventInterface;
use Mannion007\BestInvestments\Event\EventListenerInterface;
use Mannion007\BestInvestments\ProjectManagement\Domain\Project;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectRepositoryInterface;

class ReactivateClientProjectsListener implements EventListenerInterface
{
    private $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function handle(EventInterface $event): void
    {
        $payload = $event->getPayload();
        /** @var Project[] $projects */
        $projects = $this->projectRepository->getBelongingTo($payload['client_id']);
        foreach ($projects as $project) {
            $project->reactivate();
            $this->projectRepository->save($project);
        }
    }
}
