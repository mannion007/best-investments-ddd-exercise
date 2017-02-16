<?php

namespace Mannion007\BestInvestments\Invoicing\CommandHandler;

use Mannion007\Interfaces\Command\CommandInterface;
use Mannion007\Interfaces\CommandHandler\CommandHandlerInterface;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationRepositoryInterface;
use Mannion007\BestInvestments\Invoicing\Command\ScheduleConsultationCommand;
use Mannion007\BestInvestments\Invoicing\Domain\ClientId;
use Mannion007\BestInvestments\Invoicing\Domain\Consultation;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationId;
use Mannion007\BestInvestments\Invoicing\Domain\TimeIncrement;

class ScheduleConsultationHandler implements CommandHandlerInterface
{
    private $consultationRepository;

    public function __construct(ConsultationRepositoryInterface $consultationRepository)
    {
        $this->consultationRepository = $consultationRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $command = ScheduleConsultationCommand::fromPayload($command->getPayload());
        $consultation = Consultation::schedule(
            ConsultationId::fromExisting($command->getConsultationId()),
            ClientId::fromExisting($command->getClientId()),
            new TimeIncrement($command->getDuration())
        );
        $this->consultationRepository->save($consultation);
    }
}
