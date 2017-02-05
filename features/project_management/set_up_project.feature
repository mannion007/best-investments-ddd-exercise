@api
Feature:
  As a Research Manager
  I want to Set Up Projects
  So that Consultations can be facilitated between Clients and Specialists

  Scenario: Setting up a Project for a Client
    Given I have a Client
    When I Set Up a Project for the Client with the name "What could possibly go wrong?" and the deadline "2020-05-15"
    Then I should have a Draft of a Project
    And I should get a Project Reference
    And A Senior Project Manager should be notified that the Project has been drafted
