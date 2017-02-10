@api
Feature:
  As a Research Manager
  I want to add Potential Specialists to the list for the Prospecting Team to Register
  So that we have Specialists to add to Projects

  Scenario: Adding a Potential Specialist to the list
    Given I have a Potential Specialist
    When I add the Specialist to the list
    Then I should have a Potential Specialist
    And The Prospecting Team should be notified that a Potential Specialist has been put on the list