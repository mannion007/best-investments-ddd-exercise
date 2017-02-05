Feature:
  In order to ensure that Specialist that do not meet the approval of the Compliance Officer are not recommended to a Client
  As a Research Manager
  I need to be able to discard Specialists from Projects
  Scenario: Discarding a Specialist from a Project
    Given I have an active Project
    And The project has an un-vetted Specialist
    When I discard the Specialist
    Then The Specialist should be marked as discarded
    And The Project Management team should be notified that the Specialist has been discarded