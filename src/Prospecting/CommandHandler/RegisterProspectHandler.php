<?php

namespace Mannion007\BestInvestments\Prospecting\CommandHandler;

use Mannion007\BestInvestments\Command\CommandInterface;
use Mannion007\BestInvestments\Command\CommandHandlerInterface;
use Mannion007\BestInvestments\Prospecting\Command\RegisterProspectCommand;
use Mannion007\BestInvestments\Prospecting\Domain\Money;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRepositoryInterface;

class RegisterProspectHandler implements CommandHandlerInterface
{
    private $prospectRepository;

    public function __construct(ProspectRepositoryInterface $prospectRepository)
    {
        $this->prospectRepository = $prospectRepository;
    }

    public function handle(CommandInterface $command): void
    {
        $registerCommand = RegisterProspectCommand::fromPayload($command->getPayload());
        $prospect = $this->prospectRepository->getByProspectId(
            ProspectId::fromExisting($registerCommand->getProspectId())
        );
        $prospect->register(new Money($registerCommand->getHourlyRate()));
        $this->prospectRepository->save($prospect);
    }
}
