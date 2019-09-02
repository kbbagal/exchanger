Exchanger

This application will show exchange rates for IND and EURO considering USD as base currency.

1. Installation

    a. Git clone https://github.com/kbbagal/exchanger.git exchanger
    
    b. cd exchanger 
    
    c. Follow step given at
	   https://symfony.com/doc/current/setup.html#setting-up-an-existing-symfony-project
	   
    
2. Configurations and migrations
     * Update DB configs in DATABASE_URL property in .env
   
	a.Create a new DB

	  php bin/console doctrine:database:create
	  #This application need DB name to be, exchanger

	b.Create new entity for storing configs

	  php bin/console make:entity
	  #This application needs entity name to be, exchange_rates

	c.Migrate new entity
		  php bin/console make:migration

	d.Execute the migration	
		  php bin/console doctrine:migrations:migrate
	
	e.Create a new controller
		  php bin/console make:controller
		  #This application needs controller name to be, ExchangeController

3. Unit Testing

   Run below command
   
	php bin/phpunit tests/ExchangeControllerTest.php
   
4. To run the application follow instructions given at https://symfony.com/doc/current/setup.html#running-symfony-applications

   [use php bin/console server:start *:port if php bin/console server:start does not work]	
   
5. Navigations (Assuming application is running at port 8080)

   http://127.0.0.1:8080 will be the index page showing up dashboard to view or edit exchange rates manually
   
   http://127.0.0.1:8080/latestrates wil fetch exchage rates from external API and save it locally

Note : Netbeans IDE has been used throughout the development.   
