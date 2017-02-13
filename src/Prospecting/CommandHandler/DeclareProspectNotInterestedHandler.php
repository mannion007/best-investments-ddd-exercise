<?php

namespace Mannion007\BestInvestments\Prospecting\CommandHandler;

use Mannion007\BestInvestments\Command\CommandInterface;
use Mannion007\BestInvestments\Command\CommandHandlerInterface;
use Mannion007\BestInvestments\Prospecting\Command\DeclareProspectNotInterestedCommand;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRepositoryInterface;

class DeclareProspectNotInterestedHandler implements CommandHandlerInterface
{
    private $prospectRepository;

    public function __construct(ProspectRepositoryInterface $prospectRepository)
    {
        $this->prospectRepository = $prospectRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $notInterestedCommand = DeclareProspectNotInterestedCommand::fromPayload($command->getPayload());
        $prospect = $this->prospectRepository->getByProspectId(
            ProspectId::fromExisting($notInterestedCommand->getProspectId())
        );
        $prospect->declareNotInterested();
        $this->prospectRepository->save($prospect);
    }
}
