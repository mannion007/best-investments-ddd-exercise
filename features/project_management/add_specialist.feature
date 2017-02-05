Feature:
  In order to obtain approval to recommend a Specialist to a Client's Analyst
  As a Research Manager
  I need to be able to add Specialists to Projects

  Scenario: Adding a Specialist to a Project
    Given I have an active Project
    And I have a Specialist
    When I add the Specialist to the Project
    Then The specialist should be added and marked as un-vetted