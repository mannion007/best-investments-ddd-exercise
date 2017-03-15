Feature:
  As an Invoicing Manager
  I want to attach Consultations to Packages
  So that I can be sure that Clients have paid for Consultations

  Scenario: Attaching a Consultation to a Package
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Active' with 40 remaining hours
    And Client 'ABC' has an Outstanding Consultation 'project1-1' which lasted 185 minutes
    When I attach Consultation 'project1-1' to Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should have Consultation 'project1-1' attached
    And Package 'gold-2015-06-6' should have 36 hours and 45 minutes remaining

  Scenario: Attaching a Consultation to a Package with insufficient remaining hours
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Active' with 2 remaining hours
    And Client 'ABC' has an Outstanding Consultation 'project1-1' which lasted 200 minutes
    When I try to attach Consultation 'project1-1' to Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should not have Consultation 'project1-1' attached
    And Package 'gold-2015-06-6' should have 2 hours remaining

  Scenario: Attaching a Consultation to a Package that belongs to a different Client
    Given I have Client 'ABC'
    And I have Client 'XYZ'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Active' with 40 remaining hours
    And Client 'XYZ' has an Outstanding Consultation 'project1-1' which lasted 60 minutes
    When I try to attach Consultation 'project1-1' to Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should not have Consultation 'project1-1' attached
    And Package 'gold-2015-06-6' should have 40 hours remaining

  Scenario: Attaching a Consultation to a Package that has not started
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Not Yet Started' with 40 remaining hours
    And Client 'ABC' has an Outstanding Consultation 'project1-1' which lasted 60 minutes
    When I try to attach Consultation 'project1-1' to Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should not have Consultation 'project1-1' attached
    And Package 'gold-2015-06-6' should have 40 hours remaining

  Scenario: Attaching a Consultation to a Package that has expired
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Expired' with 40 remaining hours
    And Client 'ABC' has an Outstanding Consultation 'project1-1' which lasted 60 minutes
    When I try to attach Consultation 'project1-1' to Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should not have Consultation 'project1-1' attached
    And Package 'gold-2015-06-6' should have 40 hours remaining