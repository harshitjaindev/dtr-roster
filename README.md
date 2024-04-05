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
	
	
** Note: Following needs to be updated in .env file for sqlite db connection: **

DB_CONNECTION=sqlite  // we need to replace mysql with sqlite

We need to comment following db variables:

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=


Run the database migration command

    php artisan migrate

	
It will say : 

The SQLite database does not exist: [[absolute_path_of_sqlite_db]]

Would you like to create it? (yes/no)

We need to enter: yes. It will create sqlite database on the given path for us.


Start the local development server

    php artisan serve
    
APIs can now be accessed at

    http://localhost:8000/api
  

***Important*** : We can clean the database and migrate again using following command:

    php artisan migrate:refresh


# Testing API

 
** Request headers 
(We need to add first two parameters in all API's request header. Authorization header is needed for accessing the APIs except the login and register APIs): 

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