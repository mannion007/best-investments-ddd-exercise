<?php

namespace spec\Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\BestInvestments\EventPublisher\EventPublisher;
use Mannion007\BestInvestments\ProjectManagement\Domain\Consultation;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationDiscardedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationReportedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationStatus;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectReference;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistId;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\EventPublisher\InMemoryEventPublisher;
use PhpSpec\ObjectBehavior;

/**
 * Class TimeIncrementSpec
 * @package spec\Mannion007\BestInvestment\ProjectManagement\Domain
 * @mixin Consultation
 */
class ConsultationSpec extends ObjectBehavior
{
    /** @var InMemoryEventPublisher */
    private $publisher;

    function let(
        SpecialistId $specialistId,
        \DateTime $time
    ) {
        $this->publisher = new InMemoryEventPublisher();
        EventPublisher::registerPublisher($this->publisher);

        $this->beConstructedWith(
            ConsultationId::fromExisting(123),
            ProjectReference::fromExisting('test-reference'),
            $specialistId,
            $time
        );
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
        $this->report(60);
        if ($this->publisher->hasNotPublished(ConsultationReportedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Consultation was reported');
        }
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
        $this->discard();
        if ($this->publisher->hasNotPublished(ConsultationDiscardedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Consultation was discarded');
        }
    }
}
