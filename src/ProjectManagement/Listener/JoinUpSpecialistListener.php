<?php

namespace Mannion007\BestInvestments\ProjectManagement\Listener;

use Mannion007\BestInvestments\Event\EventInterface;
use Mannion007\BestInvestments\Event\EventListenerInterface;
use Mannion007\BestInvestments\ProjectManagement\Service\SpecialistService;

class JoinUpSpecialistListener implements EventListenerInterface
{
    private $specialistService;

    public function __construct(SpecialistService $specialistService)
    {
        $this->specialistService = $specialistService;
    }

    public function handle(EventInterface $event): void
    {
        $payload = $event->getPayload();
        $this->specialistService->joinUpSpecialist($payload['prospect_id'], $payload['hourly_rate']);
    }
}
