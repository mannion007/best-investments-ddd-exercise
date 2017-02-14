@api
Feature:
  As a Research Manager
  I want to be able to manage Specialists to Projects
  So that Consultations can be arranged with the Client

  Scenario: Adding a Specialist to a Project
    Given I have an active Project
    And I have a Specialist
    And The Specialist has not been added to the Project
    When I add the Specialist to the Project
    Then The Specialist should be added and marked as un-vetted

  Scenario: Approving a Specialist for a Project
    Given I have an active Project
    And The project has an un-vetted Specialist
    When I approve the Specialist
    Then The Specialist should be marked as approved

  Scenario: Discarding a Specialist from a Project
    Given I have an active Project
    And The project has an un-vetted Specialist
    When I discard the Specialist
    Then The Specialist should be marked as discarded