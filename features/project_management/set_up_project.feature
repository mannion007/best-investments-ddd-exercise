Feature:
  In order to facilitate Consultations between Clients and Specialists
  As a Research Manager
  I need to be able to Set Up Projects

  Scenario: Setting up a Project for a Client
    Given I have a Client
    When I Set Up a Project for the Client with the name "The Quest for Success" and the deadline "2020-05-15"
    Then I should have a Draft of a Project
    And I should get a Project Reference
    And A Senior Project Manager should be notified that the Project has been drafted
