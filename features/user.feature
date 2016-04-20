Feature: User
  I want to be sure the user features functions correctly

  Scenario: View Users Section
    When I go to the Homepage
    Then the Page Should Load Successfully
		And I follow "Users"
		Then I should see "Add user"
		And I should see "firstname"

	Scenario: Create a User
		When I go to "user/view"
		And I follow "Add User"
		And I should see "Add User"

		When I select "Mr" from "title"
		And I fill in "firstname" with "John"
		And I fill in "surname" with "Smith"
		And I fill in "Telephone" with "549859485"
		And I fill in "email" with "john@smith.com"
    And I press "Save"
    Then the Page Should Load Successfully
    Then I should see "Successfully saved"
    
    When I go to "user/view"
    Then I should see "John"
