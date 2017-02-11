<?php

namespace Mannion007\BestInvestments\Prospecting\Application\CommandHandler;

use Mannion007\BestInvestments\Command\CommandInterface;
use Mannion007\BestInvestments\Command\CommandHandlerInterface;
use Mannion007\BestInvestments\Prospecting\Application\Command\GiveUpOnProspectCommand;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRepositoryInterface;

class GiveUpOnProspectHandler implements CommandHandlerInterface
{
    private $prospectRepository;

    public function __construct(ProspectRepositoryInterface $prospectRepository)
    {
        $this->prospectRepository = $prospectRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $giveUpCommand = GiveUpOnProspectCommand::fromPayload($command->getPayload());
        $prospect = $this->prospectRepository->getByProspectId(
            ProspectId::fromExisting($giveUpCommand->getProspectId())
        );
        $prospect->giveUp();
        $this->prospectRepository->save($prospect);
    }
}
