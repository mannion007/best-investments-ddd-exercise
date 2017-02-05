Feature:
  As a Senior Project Manager
  I want to be able to start Projects
  So that Specialists can be recommended to Clients

  Scenario: Starting a Project
    Given I have a drafted Project
    When I assign a Project Manager to the Project
    Then The Project should be marked as active
    And Specialists can be added to the Project