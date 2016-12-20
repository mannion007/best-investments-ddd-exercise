<?php
class Project
{
    private $reference;
    private $clientId;
    private $projectManagerId;

    private $name;
    private $status;
    private $endDate;

    /** @var SpecialistId[] */
    private $specialistIds = [];

    private function __construct(ClientId $clientId, string $name, \DateTime $endDate)
    {
        $this->reference = ProjectReference::create();
        $this->status = ProjectStatus::Draft();

        $this->clientId = $clientId;
        $this->name = $name;
        $this->endDate = $endDate;
        /** @todo Raise an event (to notify the Senior project manager) */
    }

    public static function setUp(ClientId $clientId, string $name, \DateTime $endDate)
    {
        return new self($clientId, $name, $endDate);
    }

    public function start(ProjectManagerId $projectManagerId)
    {
        if ($this->hasStarted()) {
            throw new \Exception('The project has already started, it cannot be started again');
        }
        $this->projectManagerId = $projectManagerId;
        $this->status = ProjectStatus::active();
        /** @todo Raise an event */
    }

    public function addPotentialSpecialist(SpecialistId $specialistId)
    {
        if (!$this->hasStarted()) {
            throw new \Exception('A specialist can only be added to a project after it has started');
        }
        $this->specialistIds = $specialistId;
    }

    /** Does this belong to a project, or the specialist itself? */
    public function discardSpecialist(SpecialistId $specialistId)
    {
    }

    /** Does this belong to a project, or the specialist itself? */
    public function approveSpecialist(SpecialistId $specialistId)
    {
    }

    private function hasStarted()
    {
        return ProjectStatus::ACTIVE === $this->status;
    }
}
