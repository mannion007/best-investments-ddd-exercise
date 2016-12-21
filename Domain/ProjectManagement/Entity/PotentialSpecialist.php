<?php

class PotentialSpecialist
{
    private $id;
    //Add research manager id
    private $name;
    private $notes;

    private function __construct(
        string $name,
        string $notes
    ) {
        $this->id = new SpecialistId();
        $this->name = $name;
        $this->notes = $notes;
        /** Raise a 'potential_specialist_put_on_list' event */
    }

    public static function putOnList(
        string $notes,
        string $name
    ) {
        return new self($name, $notes);
    }

    public function register()
    {
        return Specialist::register($this->id, $this->name);
    }
}
