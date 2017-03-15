Feature:
  As an Invoicing Manager
  I want to transfer time from expired packages to active packages
  So that Clients are inclined to purchase another package when a package expires with time remaining

  Scenario: Transferring remaining hours from an Expired Package to a Package that has hasn't started yet
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2010-05-06' which is 'Expired' with 6 remaining hours
    And Client 'ABC' has Package 'silver-2017-10-12' which is 'Not Yet Started' with 40 remaining hours
    When I transfer time from Package 'gold-2010-05-06' to 'silver-2017-10-12'
    And Package 'gold-2010-05-06' should have 0 hours remaining
    And Package 'silver-2017-10-12' should have 46 hours remaining

  Scenario: Transferring hours into a package that has not started yet
    Given I have Client 'ABC'
    And I have 5 hours transferred out of an expired Package which belongs to Client 'ABC'
    And Client 'ABC' has Package 'silver-2017-10-12' which is 'Not Yet Started' with 40 remaining hours
    When I transfer those hours into Package 'silver-2017-10-12'
    Then Package 'silver-2017-10-12' should have 45 hours remaining

  Scenario: Transferring remaining hours out of a Package that has not yet started
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Not Yet Started' with 30 remaining hours
    When I try to transfer the remaining hours out of Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should have 30 hours remaining

  Scenario: Transferring remaining hours out of a Package that is still active
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Active' with 30 remaining hours
    When I try to transfer the remaining hours out of Package 'gold-2015-06-6'
    Then Package 'gold-2015-06-6' should have 30 hours remaining

  Scenario: Transferring extra hours into a Package that has already started
    Given I have Client 'ABC'
    And I have 8 hours transferred out of an expired Package which belongs to Client 'ABC'
    And Client 'ABC' has Package 'silver-2017-10-12' which is 'Active' with 40 remaining hours
    When I try to transfer those hours into Package 'silver-2017-10-12'
    And Package 'silver-2017-10-12' should have 40 hours remaining

  Scenario: Transferring extra hours into a package which belongs to a different Client
    Given I have Client 'ABC'
    And I have Client 'DEF'
    And I have 3 hours transferred out of an expired Package which belongs to Client 'ABC'
    And Client 'DEF' has Package 'silver-2017-10-12' which is 'Not Yet Started' with 42 remaining hours
    When I try to transfer those hours into Package 'silver-2017-10-12'
    Then Package 'silver-2017-10-12' should have 42 hours remaining