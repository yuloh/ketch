Feature: Create a project
  In order to start projects quickly and easily
  As a developer
  I need to be able to simply create projects from templates

  Scenario: Creating a project from a simple template on github
    Given I have ketch installed
    When I create a project named "my-project" using the "yuloh/ketch" template and the answers:
      | question       | answer              |
      | *vendor*       | yuloh               |
      | *package*      | test-package        |
      | *description*  | awesome pkg         |
      | *author name*  | Matty A             |
      | *author email* | matty.a@example.com |
    Then the project "my-project" should be created
    And the composer.json "name" for "my-project" should read "yuloh/test-package"
    And the composer.json "description" for "my-project" should read "awesome pkg"
    And the file ".gitattributes" should exist in "my-project"
    And the file ".travis.yml" should exist in "my-project"
