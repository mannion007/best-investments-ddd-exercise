<?php

namespace Mannion007\BestInvestments\Invoicing\Listener;

use Mannion007\BestInvestments\Invoicing\Domain\Client;
use Mannion007\BestInvestments\Invoicing\Domain\ClientId;
use Mannion007\BestInvestments\Invoicing\Domain\ClientRepositoryInterface;
use Mannion007\BestInvestments\Invoicing\Domain\HourlyRate;
use Mannion007\Interfaces\Event\EventInterface;
use Mannion007\Interfaces\EventListener\EventListenerInterface;
use Mannion007\ValueObjects\Currency;

class ClientSignedUpListener implements EventListenerInterface
{
    /** @var ClientRepositoryInterface */
    private $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function handle(EventInterface $event): void
    {
        $payload = $event->getPayload();
        $client = Client::signUp(
            ClientId::fromExisting($payload['client_id']),
            new HourlyRate((int)$payload['pay_as_you_go_rate'], Currency::gbp())
        );
        $this->clientRepository->save($client);
    }
}
