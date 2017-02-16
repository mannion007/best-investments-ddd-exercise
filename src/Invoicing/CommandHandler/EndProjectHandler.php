<?php

namespace Mannion007\BestInvestments\Invoicing\CommandHandler;

use Mannion007\Interfaces\Command\CommandInterface;
use Mannion007\Interfaces\CommandHandler\CommandHandlerInterface;
use Mannion007\BestInvestments\Invoicing\Command\EndProjectCommand;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationRepositoryInterface;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationId;

class EndProjectHandler implements CommandHandlerInterface
{
    private $consultationRepository;

    public function __construct(ConsultationRepositoryInterface $consultationRepository)
    {
        $this->consultationRepository = $consultationRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $command = EndProjectCommand::fromPayload($command->getPayload());
        $consultation = $this->consultationRepository->getById(
            ConsultationId::fromExisting($command->getConsultationId())
        );
        $consultation->endProject();
        $this->consultationRepository->save($consultation);
    }
}
