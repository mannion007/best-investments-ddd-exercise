<?php

namespace spec\Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Domain\ProjectManagement\Consultation;
use Mannion007\BestInvestments\Domain\ProjectManagement\ConsultationId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ConsultationStatus;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectReference;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TimeIncrementSpec
 * @package spec\Mannion007\BestInvestments\Domain\ProjectManagement
 * @mixin Consultation
 */
class ConsultationSpec extends ObjectBehavior
{
    function let(
        ConsultationId $consultationId,
        ProjectReference $projectReference,
        SpecialistId $specialistId,
        \DateTime $time
    ) {
        $this->beConstructedWith($consultationId, $projectReference, $specialistId, $time);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Consultation::class);
    }

    function it_does_not_report_when_it_is_not_open()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ConsultationStatus::confirmed());

        $this->shouldThrow(new \Exception('Cannot report on a consultation that is not open'))
            ->during('report', [60]);
    }

    function it_reports_when_it_is_open()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ConsultationStatus::open());

        $this->shouldNotThrow(new \Exception('Cannot report on a consultation that is not open'))
            ->during('report', [60]);
    }

    function it_does_not_discard_when_it_is_not_open()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ConsultationStatus::confirmed());

        $this->shouldThrow(new \Exception('Cannot discard a report on a consultation that is not open'))
            ->during('discard');
    }

    function it_discards_when_it_is_open()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ConsultationStatus::open());

        $this->shouldNotThrow(new \Exception('Cannot discard a report on a consultation that is not open'))
            ->during('discard');
    }
}
