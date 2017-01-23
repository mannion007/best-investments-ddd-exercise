<?php

namespace spec\Mannion007\BestInvestments\Domain\Invoicing;

use Mannion007\BestInvestments\Domain\Invoicing\Consultation;
use Mannion007\BestInvestments\Domain\Invoicing\ConsultationId;
use Mannion007\BestInvestments\Domain\Invoicing\ClientId;
use Mannion007\BestInvestments\Domain\Invoicing\ProjectStatus;
use Mannion007\BestInvestments\Domain\Invoicing\TimeIncrement;
use PhpSpec\ObjectBehavior;

/**
 * Class ConsultationSpec
 * @package spec\Mannion007\BestInvestments\Domain\Invoicing
 * @mixin Consultation
 */
class ConsultationSpec extends ObjectBehavior
{
    function let(ConsultationId $consultationId, ClientId $clientId, TimeIncrement $duration)
    {
        $this::beConstructedThrough('schedule', [$consultationId, $clientId, $duration]);
    }

    function it_does_not_end_project_when_project_has_previously_ended()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'projectStatus');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::ended());
        $this->shouldThrow(new \Exception('Cannot end a Project that is not active'))->during('endProject');
    }

    function it_ends_project_when_project_has_not_ended()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'projectStatus');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::active());
        $this->shouldNotThrow(new \Exception('Cannot end a Project that is not active'))->during('endProject');
    }

    function it_is_not_billable_when_the_project_has_not_ended()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'projectStatus');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::active());
        $this->shouldNotBeBillable();
    }

    function it_is_billable_when_the_project_has_ended()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'projectStatus');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::ended());
        $this->shouldBeBillable();
    }
}
