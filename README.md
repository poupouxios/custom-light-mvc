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
* Execute the commands `bundle exec rake db:migrate DB=development` and `bundle exec rake db:migrate DB=testing`. This will run all the migrations to generate the tables in the database.
* After doing all the above, you should be able to access the web appication at `http://192.168.69.95`.

As you will see the project doesn't have too much. Its just a simple web application that add/edit/delete expenses,users and savings. 

The components that the project has are:

* Custom MVC design (**in progress to get validations in place**)
* Custom ORM design (**in progress to get it use more complex queries**)
* [Composer](https://getcomposer.org/) which is a package manager for PHP
* [Standalone migrations](https://github.com/thuss/standalone-migrations) which is migrations based on the ruby structure migrations to create,update or delete columns or tables from database
* Custom Seed data - (**in progress to get it working**)
* Grunt and npm to watch and generate minified CSS files from less files
* [Twitter Bootstrap 3](http://getbootstrap.com/)
* [Behat Tests] (http://docs.behat.org/en/v2.5/quick_intro.html) which is the BDD testing

Some of the above where developed as I was working in [Llama Digital](http://www.llamadigital.co.uk/) and some others where developed and improved on my free time.

## TODO

* explain each part how its structure and how it works
* incorporate an Application Error Reporting
* add more complicate Behat tests

## License

This project is licensed under the MIT open source license.

## About the Author

[Valentinos Papasavvas](http://www.papasavvas.me/) works as a Senior Web Developer and iOS Developer in a company based in Sheffield/UK. You can find more on his [website](http://www.papasavvas.me/).
