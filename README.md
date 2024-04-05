# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/11.x#creating-a-laravel-project)

Clone the repository
    
    git clone https://github.com/harshitjaindev/dtr-roster.git

Switch to the repo folder

    cd dtr-roster

Install all the dependencies using composer

    composer install
    
Generate a new application key

    php artisan key:generate

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env
	
Note: Following needs to be updated in .env file:
DB_CONNECTION=sqlite  // it should be sqlite
DB_DATABASE={{DATABASE PATH}} // it should be absolute path of sqlite database


(**Provide the correct database connection in .env before migrating**)

Run the database migrations 

    php artisan migrate

Start the local development server

    php artisan serve
    
APIs can now be accessed at

    http://localhost:8000/api
  

***Important*** : We can clean the database and migrate again using following command:

    php artisan migrate:refresh


# Testing API

 
Request headers (except login, register route): 

| **Key**           | **Value**          
|----------------	|------------------	        |
|Content-Type     	| application/vnd.api+json 	|
|----------------	|------------------	        |
|Accept     	    | application/vnd.api+json 	|
|----------------	|-----------------------	|
|Authorization     	| Bearer 1|OrBUk2zM9BdlVWWDs7T75vvlBzlvjXo1LDT3RbBj44132cc9

### Tesing APIS in Postman ###
For testing the APIs, we have shared the API collections here:
https://github.com/harshitjaindev/dtr-roster.git/Postman- Collections

We need to import the collection and environment variable accordingly.

## Import File Structure (html) by DTR:

https://github.com/harshitjaindev/dtr-roster.git/tests/RosterData/Roster-CrewConnex


### To run unit tests for the import functionality, execute the following commands:

    php artisan test

    We need to update phpunit.xml file for test cases accordingly.