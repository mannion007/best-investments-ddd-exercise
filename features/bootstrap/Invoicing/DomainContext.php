<?php

namespace Mannion007\BestInvestmentsBehat\Invoicing;

use Behat\Behat\Context\Context;
use Mannion007\BestInvestments\Invoicing\Domain\OutstandingConsultation;
use Mannion007\BestInvestments\Invoicing\Domain\PackageStatus;
use Mannion007\BestInvestments\Invoicing\Domain\Service\TimeTransferService;
use Mannion007\BestInvestments\Invoicing\Domain\TransferTime;
use Mannion007\BestInvestments\Invoicing\Infrastructure\Storage\InMemoryPackageRepositoryAdapter;
use Mannion007\BestInvestments\Invoicing\Domain\ClientId;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationId;
use Mannion007\BestInvestments\Invoicing\Domain\Package;
use Mannion007\BestInvestments\Invoicing\Domain\PackageReference;
use Mannion007\BestInvestments\Invoicing\Domain\TimeIncrement;
use Mannion007\BestInvestments\ProjectManagement\Infrastructure\EventPublisher\InMemoryEventPublisher;
use Mannion007\BestInvestments\EventPublisher\EventPublisher;

/**
 * Defines application features from the specific context.
 */
class DomainContext implements Context
{
    /** @var InMemoryEventPublisher */
    private $eventPublisher;

    /** @var ClientId */
    private $clientId;

    /** @var InMemoryPackageRepositoryAdapter */
    private $packageRepository;

    /** @var TransferTime */
    private $transferTime;

    /** @var TimeTransferService */
    private $timeTransferService;

    /** @var OutstandingConsultation[] */
    private $outStandingConsultations;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->eventPublisher = new InMemoryEventPublisher();
        EventPublisher::registerPublisher($this->eventPublisher);
        $this->packageRepository = new InMemoryPackageRepositoryAdapter($this->eventPublisher);
        $this->timeTransferService = new TimeTransferService($this->packageRepository);
    }

    /**
     * @Given I have Client :clientId
     */
    public function iHaveClient(string $clientId)
    {
        $this->clientId = ClientId::fromExisting($clientId);
    }

    /**
     * @Given Client :clientId has Package :reference which is :status with :hours remaining hours
     */
    public function clientHasAPackageWhichIsWithRemainingHours($reference, $status, $clientId, $hours)
    {
        $packageReference = PackageReference::fromExisting($reference);
        $package = new Package($packageReference, ClientId::fromExisting($clientId), $hours);

        $packageStatus = new \ReflectionProperty($package, 'status');
        $packageStatus->setAccessible(true);

        switch ($status) {
            case 'Expired':
                $packageStatus->setValue($package, PackageStatus::expired());
                break;
            case 'Not Yet Started':
                $packageStatus->setValue($package, PackageStatus::notYetStarted());
                break;
            case 'Active':
                $packageStatus->setValue($package, PackageStatus::active());
                break;
            default:
                throw new \Exception(sprintf('Invalid Package Status "%s"', $status));
                break;
        }

        $availableHours = new \ReflectionProperty($package, 'availableHours');
        $availableHours->setAccessible(true);
        $availableHours->setValue($package, new TimeIncrement($hours * 60));

        $this->packageRepository->save($package);
    }

    /**
     * @Given Client :clientId has an Outstanding Consultation :consultationId which lasted :hours minutes
     */
    public function clientHasAnOutstandingConsultationWhichLastedMinutes($clientId, $consultationId, $minutes)
    {
        $consultationId = ConsultationId::fromExisting($consultationId);
        $this->outStandingConsultations[(string)$consultationId] = new OutstandingConsultation(
            ConsultationId::fromExisting($consultationId),
            ClientId::fromExisting($clientId),
            $minutes
        );
    }

    /**
     * @When I attach Consultation :consultationId to Package :reference
     */
    public function iAttachConsultationToPackage($consultationId, $reference)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        $package->attach($this->outStandingConsultations[$consultationId]);
    }

    /**
     * @When I try to attach Consultation :consultationId to Package :reference
     */
    public function iTryToAttachThatConsultationToThatPackage($consultationId, $reference)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        try {
            $package->attach($this->outStandingConsultations[$consultationId]);
        } catch (\Exception $e) {
        }
    }

    /**
     * @Then Package :packageReference should have Consultation :consultationId attached
     */
    public function packageShouldHaveConsultationAttached($reference, $consultationId)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        $reflected = new \ReflectionProperty($package, 'attachedConsultations');
        $reflected->setAccessible(true);
        foreach (array_keys($reflected->getValue($package)) as $attachedId) {
            if ($attachedId === $consultationId) {
                return;
            }
        }
        throw new \Exception('The Package does not have the consultation attached');
    }

    /**
     * @Then Package :packageReference should not have Consultation :consultationId attached
     */
    public function packageShouldNotHaveConsultationAttached($reference, $consultationId)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        $reflected = new \ReflectionProperty($package, 'attachedConsultations');
        $reflected->setAccessible(true);
        foreach (array_keys($reflected->getValue($package)) as $attachedId) {
            if ($attachedId === $consultationId) {
                throw new \Exception('The Package does have the consultation attached');
            }
        }
    }

    /**
     * @Then Package :reference should have :hours hours and :minutes minutes remaining
     */
    public function packageShouldHaveHoursAndMinutesRemaining($reference, $hours, $minutes)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        $reflected = new \ReflectionMethod($package, 'getRemainingHours');
        $reflected->setAccessible(true);
        ///** @var TimeIncrement $remainingHours */
        //$remainingTime = $reflected->getValue($package);
        $remainingTime = $reflected->invoke($package);
        $remainingHours = floor($remainingTime->inMinutes() / 60);
        $remainingMinutes = $remainingTime->inMinutes() - ($remainingHours * 60);

        if ((int)$hours != (int)$remainingHours || (int)$minutes != (int)$remainingMinutes) {
            throw new \Exception(
                sprintf(
                    'Package does not have %s hours and %s minutes remaining. Actually has %s hours and %s minutes',
                    $hours,
                    $minutes,
                    $remainingHours,
                    $remainingMinutes
                )
            );
        }
    }

    /**
     * @Then Package :reference should have :hours hours remaining
     */
    public function packageShouldHaveHoursRemaining($reference, $hours)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));

        $reflected = new \ReflectionMethod($package, 'getRemainingHours');
        $reflected->setAccessible(true);
        $remainingTime = $reflected->invoke($package);

        $remainingHours = floor($remainingTime->inMinutes() / 60);
        $remainingMinutes = $remainingTime->inMinutes() - ($remainingHours * 60);

        if ((int)$hours != (int)$remainingHours || 0 != (int)$remainingMinutes) {
            throw new \Exception(
                sprintf(
                    'Package does not have %s hours remaining. Actually has %s hours and %s minutes',
                    $hours,
                    $remainingHours,
                    $remainingMinutes
                )
            );
        }
    }

//    private function eventShouldHaveBeenPublishedNamed(string $eventName)
//    {
//        if ($this->eventPublisher->hasNotPublished($eventName)) {
//            throw new \Exception(
//                'The event has not been published'
//            );
//        }
//    }

    /**
     * @When I transfer time from Package :fromReference to :toReference
     */
    public function iTransferTimeFromPackageTo($fromReference, $toReference)
    {
        $this->timeTransferService->transferAvailableTime(
            PackageReference::fromExisting($fromReference),
            PackageReference::fromExisting($toReference)
        );
    }

    /**
     * @When I try to transfer time from Package :fromReference to :toReference
     */
    public function iTryToTransferTimeFromPackageTo($fromReference, $toReference)
    {
        try {
            $this->timeTransferService->transferAvailableTime(
                PackageReference::fromExisting($fromReference),
                PackageReference::fromExisting($toReference)
            );
        } catch (\Exception $e) {
        }
    }

    /**
     * @Given I have :hours hours transferred out of an expired Package which belongs to Client :clientId
     */
    public function iHaveHoursTransferredOutOfAnExpiredPackageWhichBelongsToClient($hours, $clientId)
    {
        $this->transferTime = new TransferTime($hours * 60, ClientId::fromExisting($clientId));
    }

    /**
     * @When I transfer those hours into Package :reference
     */
    public function iTransferThoseHoursIntoPackage($reference)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        $package->transferInExtraHours($this->transferTime);
    }

    /**
     * @When I try to transfer those hours into Package :reference
     */
    public function iTryToTransferThoseHoursIntoPackage($reference)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        try {
            $package->transferInExtraHours($this->transferTime);
        } catch (\Exception $e) {
        }
    }

    /**
     * @When I try to transfer the remaining hours out of Package :reference
     */
    public function iTryToTransferTheRemainingHoursOutOfPackage($reference)
    {
        $package = $this->packageRepository->getByReference(PackageReference::fromExisting($reference));
        try {
            $package->transferOutRemainingHours();
        } catch (\Exception $e) {
        }
    }
}
