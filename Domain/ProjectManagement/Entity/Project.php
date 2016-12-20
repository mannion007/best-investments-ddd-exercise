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
    private $recommendedSpecialists = [];

    private function __construct(ClientId $clientId, string $name, \DateTime $endDate)
    {
        $this->reference = ProjectReference::create();
        $this->status = ProjectStatus::Draft();

        $this->clientId = $clientId;
        $this->name = $name;
        $this->endDate = $endDate;
        /** Raise a 'project_set_up' event */
    }

    public static function setUp(ClientId $clientId, string $name, \DateTime $endDate)
    {
        return new self($clientId, $name, $endDate);
    }

    public function start(ProjectManagerId $projectManagerId)
    {
        if ($this->status->is(ProjectStatus::ACTIVE)) {
            throw new \Exception('The project has already started, it cannot be started again');
        }
        $this->projectManagerId = $projectManagerId;
        $this->status = ProjectStatus::active();
        /** @todo Raise a project started event */
    }

    public function recommendSpecialist(SpecialistId $specialistId)
    {
        if (!$this->status->is(ProjectStatus::ACTIVE)) {
            throw new \Exception('A specialist can only be added to a project after it has started');
        }
        $this->recommendedSpecialists[$specialistId] = SpecialistRecommendation::UNVETTED;
    }

    public function approveSpecialist(SpecialistId $specialistId)
    {
        if(!$this->recommendedSpecialists[$specialistId]->is(SpecialistRecommendation::UNVETTED)) {
            throw new Exception('Potential specialist is not unvetted');
        }
        $this->recommendedSpecialists[$specialistId] = SpecialistRecommendation::APPROVED;
    }

    public function discardSpecialist(SpecialistId $specialistId)
    {
        if(!$this->recommendedSpecialists[$specialistId]->is(SpecialistRecommendation::UNVETTED)) {
            throw new Exception('Potential specialist is not unvetted');
        }
        $this->recommendedSpecialists[$specialistId] = SpecialistRecommendation::DISCARDED;
    }
}
