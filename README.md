## Custom Light MVC

This is a personal project I started, which I am trying to add all the necessary tools that are needed for a MVC project to deliver quality code along with automating some steps.

## Motivation

On my free time I like to play with Zend Framework and Drupal. However, both technologies are entirely different. Zend Framework is a MVC framework compared to Drupal which is a CMS that has its own structure and guidelines of how to develop. As I prefer to use design patterns to make the code easier to be readable, maintenable and easier to modify things, I wanted to add a small level of MVC in Drupal.

Drupal 7 is lagging of having separation of re-using code or partial views, so I started developing in some modules I published on my Github account a custom light MVC framework which is based on Zend Framework structure. It includes Model,Mapper,Helper and Views. Its not entirely the same structure like Zend but its close enough to offer an abstraction in Drupal.

At some point, my girlfriend wanted to create an excel file to record our expenses and savings and see how much each person has available and what expenses and savings we have. So I thought I should create a small project, which will be based on that custom MVC I created and it will have all the necessary components/tools to create this web application and being able to test it. And thats how this project started.

## Requirements

The module requires some essential components to work:

* [Vagrant](https://www.vagrantup.com/)
* [Ansible](http://www.ansible.com/)
* [Oracle Virtual Box](https://www.virtualbox.org/wiki/Downloads)

## Setup

* Clone the repo into your local machine
* Navigate inside the light-mvc folder
* Assuming you installed all the requirements from above, you can run `vagrant up`
* The `vagrant up` will install all the necessary dependencies in order to have a ready project to play
* After the provision of the VM finishes, run `vagrant ssh` to get inside the VM
* Execute the commands `bundle exec rake db:migrate RAILS_ENV=development` and `bundle exec rake db:migrate RAILS_ENV=testing`. This will run all the migrations to generate the tables in the database.
* After doing all the above, you should be able to access the web appication at `http://192.168.69.95`.

As you will see the project doesn't have too much. Its just a simple web application that add/edit/delete expenses,users and savings. 

The components that the project has are:

* **Custom MVC design**

  This custom MVC has models, mappers, controllers, helpers and views. The model structure is as below:
  ```php
  class ExpenseModel extends BaseModel{
	
		public static $attributes = array(
			"id" => 0,
			"expense_value" => 0,
			"comment" => "",
			"user_id" => null,
			"created_at" => "",
			"updated_at" => "");
		
	}
  ```
  You define what are the properties for the Model and they are accessible as static. As you will see everything is public and accessible to be edited. The reason is that when you fetch the model from the mapper you want to have access to all its properties. However, if you want to protect some attributes you can always declare them protected or private inside the class.
  
  To instantiate a model withs its data all you do is:
  ```php
    $data = []; // this is a hash array containing the data that are needed eg. $data['comment'] = 'test';
    $expenseModel = ExpenseModel::createObject($data);
  ```
  Mappers are responsible to fetch from a data source a model or a set of models. Each model must have its own mapper where inside each mapper the `table_name` property is set to define the table of the model. The BaseMapper has some general methods which are:
  ```php
  public function findOneBy($field,$value,$orderBy=array());
  public function findAll($orderBy=array());
  public function findAllBy($field,$value,$orderBy=array());
  ```
  
  People familiar with Zend will recognize these methods. They offer the ability to the developer to fetch with simple queries the model or set of models they want. For example:
  
  ```php
    $userModel = UserModel::getMapper()->findOneBy("id",3");
  ```
  
  This will return a User model which matches the ID 3.
  
  Now in the case of updating or creating a new model the below process is followed:
  
  ```php
   /* create new model */
   //validation of the $_POST data that are ready to be stored in the database. Assume $data holds the final data to store.
   $expenseModel = ExpenseModel::createObject($data);
   ExpenseModel::getMapper()->setModel($expenseModel)->save();
   
   /* updating a model */
   $userModel = UserModel::getMapper()->findOneBy("id",3");
   $userModel->surname = "Smith";
   UserModel::getMapper()->setModel($userModel)->save();
   
  ```
  
  The first scenario will check if the id is set and if its not it will insert the record in the database.
  The second scenario will see that the id exist in the model and it will update the content of it and change the updated_at field to the current date.
  
  The controllers are executing different actions requested from the user. The requests are parsed as in a MVC framework eg.
  
  `/user/edit/1`
  
  This will find the UserController and find the edit action method which will pass the parameter 1 which is the user id. Then the controller is responsible to fetch the proper model and call the proper view to render the model.
  
  An example is as below:
  
  ```php
  	public function edit($id){
  		$user_id = intval($id);
  		if(count($_POST) > 0){
  			$user = UserModel::getMapper()->findOneBy("id",$user_id);
  			if($user){
  				$user->updateValues($_POST);
  				UserModel::getMapper()->setModel($user)->save();
  				$this->view->message = "Successfully updated";
  			}else{
  				$this->view->warningmessage = "Error updating.";
  			}
  		}
  
  		$this->view->user = UserModel::getMapper()->findOneBy("id",$user_id);
  		return $this->view->getContentView("user/edit.phtml");
		}
  ```
  You specify what view to render, what properties the view will have access and the rest are handle by the view file.
  
  Helpers are mostly going to be used for reusing code. Currently only the BaseHelper exist which has the method `debugMessage($message)` which renders the message in a `pre tag` to be more readable.

* **Custom ORM design**

  The Custom ORM design is abstracting the complexity of the PDO binding variables and offers through method chaining a better understanding to the developer of what happens. Currently it supports simple queries with where clause. 
  
  Below are examples of each database statement and how it works:

  **Select**
  
  ```php
      $select = new Select();
      $result = $select->fromTable($this->table_name)
  									->where(array("email = :email"))
  									->withData(array("email" => $email))
  									->execute();
  
      $user = null;
      while($row = $select->fetchPDOAssoc($result)){
      	$user = UserModel::createObject($row,UserModel::$attributes);
    	}
  ```
  **Insert**
  
  ```php
      $attributes = []; //this can be the values set for the model and called from the mapper to be insert eg. $attributes['surname'] = "Smith";
      $insert = new Insert();
      $insert->intoTable('users')->withValues($attributes)->execute();
  ```
  
  **Update**
  
  ```php
      $attributes = []; //this can be the values set for the model and called from the mapper to be insert eg. $attributes['surname'] = "Smith";
      $update = new Update();
      $update->intoTable('users')->withValues($attributes)->where(array('id' => 1))->execute();
  ```
  
  **Delete**
  
  ```php
      $delete_record = new Delete();
      $delete_record->fromTable('users')->where(["id = :record_id"])->withData(["record_id" => 1])->execute();
  ```

* **[Composer](https://getcomposer.org/)** which is a package manager for PHP
* **[Standalone migrations](https://github.com/thuss/standalone-migrations)** which are migrations based on the ruby structure migrations to create,update or delete fields or tables from a database
  
  Standalone migrations are very useful when you want to deploy to a review or a production server and you don't have to go on each server and execute custom scripts which have sql queries to create/update/delete fields or tables.
  
  An example of a standalone migration is:

  ```ruby
    class CreateUsers < ActiveRecord::Migration
      def self.up
        create_table :users, options: 'DEFAULT CHARSET=utf8' do |t|
          t.string :title
          t.string :firstname
          t.string :surname
          t.string :telephone
          t.string :email
          t.timestamps
        end
      end
    
      def self.down
        drop_table :users
      end
    end
  ```

  The above will create the table users with the proper fields. The self.down purpose is if a migration was unecessary or you mistyped something and you want to rollback to remove that field or table.

* **Custom Seed data**
 
  The idea of the seed data came out at work where some content on a back end system needed to be deployed and set automatically on review and production servers. 

  The structure of the Seed module is:
  * **SeedDataInterface**: An interface which has the create and update method
  * **BaseSeedData**:  A base abstract class which implements the interface and adds 2 extra methods called markMigration to insert in the database when the migration finishes successfully and a getCurrentDateForSql to return the current date
  * **Seed_20160514171050_New_User**: one of the example files that extend the BaseSeedData and implemented the created method by creating a new user and saving in the database
  
  The`seed.php` file is responsible for two type of actions:
  * **create**: create a new migration file by providing the name. It adds the necessary methods that need to be implemented.
  eg.
  ```script
    cd public && APPLICATION_ENV=local php ../db/seed.php create new_user
  ``` 
  * **execute**: It parses the `db/seed-data/` folder and check each class if its being executed in order to not execute it again. There is a table in the database called `seed_migrations`, which stores the `class_name` being executed and the timestamps.
  ```script
    cd public && APPLICATION_ENV=local php ../db/seed.php execute
  ``` 
  
  The APPLICATION_ENV defines in which environment the seed data will be executed and saved. In the above examples they will be stored in the local version. If we wanted to create the seed data in testing environment, we will have to use APPLICATION_ENV=testing.
  
  
* **Grunt and npm to watch and generate minified CSS files from less files**
* **[Twitter Bootstrap 3](http://getbootstrap.com/)**
* **[Behat Tests] (http://docs.behat.org/en/v2.5/quick_intro.html) which is the BDD testing**

  Behat Tests are offering the ability to show to a customer what has been tested on its web application or website compared to the unit testing where it tests behaviour of a class or a method to expect a specific result based on the data passed.
  
  Behat Tests have there own language which is called `Gherkin`. Gherkin is a whitespace-oriented language that uses indentation to define structure - as its mention in [behat website](http://docs.behat.org/en/v3.0/guides/1.gherkin.html).
  
  The client can understand each scenario that is executed to be tested and what steps are being followed. Clients can always add to the scenario and suggest extra steps to test more thorough the web application.
  
  The benefit of the Behat tests are that they mimic a real browser which can execute javascript and ajax calls. 
  
  An example of a behat test is:
  
  ```ruby
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
  ```
  
  Each step represents a regex that will call a specific method in one of the context classes - in our example you expect the UserContext class to have the necessary steps to execute the scenario.At work, we abstracted the logic and moved it down to helpers, which deal with the whole process of creating/updating data. You can always use the helpers to generate a whole scenario as you don't want to copy paste the same scenario every time for eg. creating a user for testing another behaviour of that section.

Some of the above where developed as I was working in [Llama Digital](http://www.llamadigital.co.uk/) and some others where developed and improved on my free time.

## TODO

* Add validations
* Add more complex queries in the Custom ORM
* incorporate an Application Error Reporting
* add more complicate Behat tests

## License

This project is licensed under the MIT open source license.

## About the Author

[Valentinos Papasavvas](http://www.papasavvas.me/) works as a Senior Web Developer and iOS Developer in a company based in Sheffield/UK. You can find more on his [website](http://www.papasavvas.me/).
