<?php

namespace Mannion007\BestInvestments\Prospecting\CommandHandler;

use Mannion007\Interfaces\Command\CommandInterface;
use Mannion007\Interfaces\CommandHandler\CommandHandlerInterface;
use Mannion007\BestInvestments\Prospecting\Command\ReceiveProspectCommand;
use Mannion007\BestInvestments\Prospecting\Domain\Prospect;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRepositoryInterface;

class ReceiveProspectHandler implements CommandHandlerInterface
{
    private $prospectRepository;

    public function __construct(ProspectRepositoryInterface $prospectRepository)
    {
        $this->prospectRepository = $prospectRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $receiveCommand = ReceiveProspectCommand::fromPayload($command->getPayload());
        $prospect = Prospect::receive(
            ProspectId::fromExisting($receiveCommand->getProspectId()),
            $receiveCommand->getName(),
            $receiveCommand->getNotes()
        );
        $this->prospectRepository->save($prospect);
    }
}
