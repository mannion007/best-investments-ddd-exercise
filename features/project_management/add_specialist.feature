Feature:
  As a Research Manager
  I want to be able to add Specialists to Projects
  So that Specialists can be vetted by a Compliance officers for Projects

  Scenario: Adding a Specialist to a Project
    Given I have an active Project
    And I have a Specialist
    And The Specialist has not been added to the Project
    When I add the Specialist to the Project
    Then The Specialist should be added and marked as un-vetted