# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/11.x#creating-a-laravel-project)

Clone the repository
    
    git clone https://github.com/harshitjaindev/dtr-roster.git

Switch to the repo folder

    cd dtr-roster

Install all the dependencies using composer

    composer install
    

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env
	
Generate a new application key

    php artisan key:generate
	
	
** Note: Following needs to be added in .env file for sqlite db connection: **

DB_CONNECTION=sqlite  // it should be sqlite

DB_DATABASE={{DATABASE PATH}} // it should be absolute path of sqlite database 

(Example:  DB_DATABASE= D:\dtr-roster\storage\app\sqlite\roster_manager.sqlite)

(Also we need to comment other DB_CONNECTION in .env file.)


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

 
** Request headers 
(We need to add first two parameters in all APIs request header. Authorization header is needed for accessing the APIs except the login and register APIs): 

| **Key**           | **Value**          
|----------------	|------------------	        |
|Content-Type     	| application/vnd.api+json 	|
|----------------	|------------------	        |
|Accept     	    | application/vnd.api+json 	|
|----------------	|-----------------------	|
|Authorization     	| Bearer {{access-token}}

### Tesing APIs in Postman ###

For testing the APIs, we have shared the API collections here:

https://github.com/harshitjaindev/dtr-roster/tree/main/Postman-Collections

We need to import the collections and environment variables accordingly. Kindly refer the below document for more information on how to import the collections and what APIs we are using.

https://github.com/harshitjaindev/dtr-roster/tree/main/Roster-API-document.docx


## Import File Structure (html) provide by DTR Airline:

https://github.com/harshitjaindev/dtr-roster/blob/main/tests/RosterData/Roster-CrewConnex-Main.html


### To run the unit tests for the APIs, kindly execute the following commands:

    php artisan test
	

** Note : We need to update phpunit.xml file accordingly for running the test cases. **