<?php
namespace config;
// MySql conncetion example
define('DB_DSN', 'mysql:dbname=test;host=localhost;');
define('DB_USER', 'php7');
define('DB_PASS', 'php7');


// PosgreSql connection example
// install pgsql extesion
// brew install php70-pdo-pgsql
// for other php version, search with:
// brew search pgsql
/*define('DB_DSN', 'pgsql:host=localhost dbname=test port=5432');
define('DB_USER', 'username');
define('DB_PASS', '');*/
