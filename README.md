# restful-api in php7

This is a simple REST Api implementation with some of the new php7 features

## Instructions

 1. run the php built in server php -S localhost:8080
 2. start sql server/mysql or others
 3. source the sql migration: <br>
    ```
 	mysql < source /Users/username/projects/rest-api-php7/migrations/mysql/create_posts_table.sql
 	``` <br>
 	```
 	psql > \i /Users/username/projects/rest-api-php7/migrations/posgresql/create_posts_table.sql
 	```
 4. add DB_DSN, DB_USER and DB_PASS in config/db.php

## Usage

###Try From Postman:

**ADD a new post**
 * method: 	PUT 
 * url:    	http://localhost:8080/api/v1/posts/
 * body type: 	raw JSON(application/json)
 * body:
 			```
			{
				"title": "Blog post title",
				"body": "this is my first blog post ..."
			}
			```

**GET post by id**
 * method: 	GET 
 * url:    	http://localhost:8080/api/v1/posts/1

**GET all posts**
 *	method: 	GET 
 *	url:    	http://localhost:8080/api/v1/posts/
	
**UPDATE post by id**
 * method: 	POST 
 *	url:    	http://localhost:8080/api/v1/posts/1
 *	body type: 	raw JSON(application/json)
 *	body:       
 				```
				{
					"title": "Blog post title modified",
					"body": "this is my first blog post ... fixed typo."
				}
				```

**DELETE post by id**
 *	method: 	DELETE 
 *	url:    	http://localhost:8080/api/v1/posts/1
	
## PHP7 features
* scalar type declarations
* return type declarations
* coalescing operator
* anonymous classes
* catch fatal errors with Throwable

## Tested with Databases
* Postgres 9.6
* MySql 14.14

## Tested with OS
* Mac OSX El Capitan

