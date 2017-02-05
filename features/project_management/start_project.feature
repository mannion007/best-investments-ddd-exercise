Feature:
  In order to be able to recommend Specialists to Clients
  As a Senior Project Manager
  I need to be able to start a Project

  Scenario: Starting a Project
    Given I have a drafted Project
    When I assign a Project Manager to the Project
    Then The Project should be marked as active
    And Specialists can be added to the Project