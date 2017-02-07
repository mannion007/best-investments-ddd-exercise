@api
Feature:
  As a Research Manager
  I want to Manage Projects
  So that Consultations can be facilitated between Clients and Specialists

  Scenario: Setting up a Project for a Client
    Given I have a Client
    When I Set Up a Project for the Client with the name "What could possibly go wrong?" and the deadline "2020-05-15"
    Then I should have a Draft of a Project
    And A Senior Project Manager should be notified that the Project has been drafted

  Scenario: Starting a Project
    Given I have a drafted Project
    And I have a Project Manager
    When I assign the Project Manager to the Project
    Then The Project should be marked as active
    And Specialists can be added to the Project
    And The Project Management Team should be notified that the Project has started

  Scenario: Closing a Project the Client has finished with
    Given I have an active Project
    And The Project has no open Consultations
    When I close the Project
    Then The Project should be marked as closed
    And The Invoicing Team should be notified that the Project has closed