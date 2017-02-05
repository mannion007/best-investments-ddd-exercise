Feature:
  As a Research Manager
  I want to be able to add Specialists to Projects
  So that approval to recommend a Specialist to a Client's Analyst can be obtained

  Scenario: Adding a Specialist to a Project
    Given I have an active Project
    And I have a Specialist
    When I add the Specialist to the Project
    Then The Specialist should be added and marked as un-vetted