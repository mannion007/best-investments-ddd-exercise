<?php

namespace Mannion007\BestInvestments\Prospecting\CommandHandler;

use Mannion007\BestInvestments\Command\CommandInterface;
use Mannion007\BestInvestments\Command\CommandHandlerInterface;
use Mannion007\BestInvestments\Prospecting\Command\ChaseUpProspectCommand;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRepositoryInterface;

class ChaseUpProspectHandler implements CommandHandlerInterface
{
    private $prospectRepository;

    public function __construct(ProspectRepositoryInterface $prospectRepository)
    {
        $this->prospectRepository = $prospectRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $chaseUpCommand = ChaseUpProspectCommand::fromPayload($command->getPayload());
        $prospect = $this->prospectRepository->getByProspectId(
            ProspectId::fromExisting($chaseUpCommand->getProspectId())
        );
        $prospect->chaseUp();
        $this->prospectRepository->save($prospect);
    }
}
