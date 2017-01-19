<?php

namespace spec\Mannion007\BestInvestments\Domain\Invoicing;

use Mannion007\BestInvestments\Domain\Invoicing\Consultation;
use Mannion007\BestInvestments\Domain\Invoicing\ConsultationId;
use Mannion007\BestInvestments\Domain\Invoicing\ClientId;
use Mannion007\BestInvestments\Domain\Invoicing\TimeIncrement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConsultationSpec extends ObjectBehavior
{
    public function let(ConsultationId $consultationId, ClientId $clientId, TimeIncrement $duration)
    {
        $this::beConstructedThrough('schedule', [$consultationId, $clientId, $duration]);
    }

    public function it_cannot_end_project_when_project_has_already_ended()
    {
        $this->shouldNotThrow(new \DomainException('Cannot end a Project that is not active'))->during('endProject');
        $this->shouldThrow(new \DomainException('Cannot end a Project that is not active'))->during('endProject');
    }
}
