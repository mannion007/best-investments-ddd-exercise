Feature:
  As a Research Manager
  I want to be able to discard Specialists from Projects
  So that only Specialists that meet the approval of Compliance Officers are recommended to Clients

  Scenario: Discarding a Specialist from a Project
    Given I have an active Project
    And The project has an un-vetted Specialist
    When I discard the Specialist
    Then The Specialist should be marked as discarded
    And The Project Management team should be notified that the Specialist has been discarded