<?php

namespace Mannion007\BestInvestments\Invoicing\Infrastructure\Storage;

use Mannion007\BestInvestments\Invoicing\Domain\Package;
use Mannion007\BestInvestments\Invoicing\Domain\PackageReference;
use Mannion007\BestInvestments\Invoicing\Domain\PackageRepositoryInterface;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Mannion007\BestInvestments\Event\TransactionSucceededEvent;

class InMemoryPackageRepositoryAdapter implements PackageRepositoryInterface
{
    /** @var EventPublisherInterface */
    private $eventPublisher;

    /** @var Package[] */
    private $packages;

    public function __construct(EventPublisherInterface $eventPublisher)
    {
        $this->packages = [];
        $this->eventPublisher = $eventPublisher;
    }

    public function save(Package $package): void
    {
        $this->packages[(string)$package->getReference()] = $package;
        $this->eventPublisher->publish(new TransactionSucceededEvent());
    }
    public function getByReference(PackageReference $reference): Package
    {
        if (!isset($this->packages[(string)$reference])) {
            throw new \Exception(sprintf('Package with reference %s not found', (string)$reference));
        }
        return $this->packages[(string)$reference];
    }

    public function purge()
    {
        $this->packages = [];
    }
}
