@mod @mod_vpl @mod_vpl_access_settings
Feature: Create and change VPL activity access settings
  In order to modify activity behaviour
  As an editing teacher
  I need to change the access setting and check the behavior for teacher and other users

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | teacher2 | Teacher2 | 1 | teacher2@example.com |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | teacher2 | C1 | teacher |
      | student1 | C1 | student |
    And the following "activities" exist:
      | activity   | name               | intro   | password | requirednet | sebrequired | sebkeys | course | section |
      | vpl        | VPL with password  | No desc | key      |             | 0           |         | C1     | 1       |
      | vpl        | VPL with network   | No desc |          | 10.10.10.13 | 0           |         | C1     | 1       |
      | vpl        | VPL with SEB       | No desc |          |             | 1           |         | C1     | 1       |
      | vpl        | VPL with SEB key   | No desc |          |             | 0           | afssdaf | C1     | 1       |

  @javascript
  Scenario: An editing teacher creates a VPL activity that requiere password => teacher access
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "VPL with password" "link" in the "region-main" "region"
    Then I should not see "A password is required"

  @javascript
  Scenario: An editing teacher creates a VPL activity that requiere password => non-editing teacher access
    When I log in as "teacher2"
    And I am on "Course 1" course homepage
    And I click on "VPL with password" "link" in the "region-main" "region"
    Then I should not see "A password is required"

  @javascript
  Scenario: An editing teacher creates a VPL activity that requiere password => student access
    When I log in as "student1"
    And I am on "Course 1" course homepage
    And I click on "VPL with password" "link" in the "region-main" "region"
    Then I should see "A password is required"
    And I set the following fields to these values:
      | id_password | clave |
    And I press "Continue"
    And I should see "Attempt number 1"
    And I set the following fields to these values:
      | id_password | reclave |
    And I press "Continue"
    And I should see "Attempt number 2"
    And I set the following fields to these values:
      | id_password | key |
    And I press "Continue"
    And I should not see "A password is required"

  @javascript
  Scenario: An editing teacher creates a VPL activity that requiere network => student access
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    And I click on "VPL with network" "link" in the "region-main" "region"
    Then I should see "Action not allowed from"

  @javascript
  Scenario: An editing teacher creates a VPL activity that requiere SEB browser => student access
    When I log in as "student1"
    And I am on "Course 1" course homepage
    And I click on "VPL with SEB" "link" in the "region-main" "region"
    Then I should see "Using SEB browser"

  @javascript
  Scenario: An editing teacher creates a VPL activity that requiere SEB key => student access
    When I log in as "student1"
    And I am on "Course 1" course homepage
    And I click on "VPL with SEB key" "link" in the "region-main" "region"
    Then I should see "Using SEB browser"
