<?php
//-----------------------------------------------------------------------------
// Site configuration 
//-----------------------------------------------------------------------------

//Show PHP errors and warnings
error_reporting(E_ALL);
ini_set("display_errors", 1);

//Timezone for converting timestamps
date_default_timezone_set("Europe/London");

//This is where the site is hosted. Allows the service to move
define('DOMAIN_NAME', 'http://birk.it/');

//Database connection 
define('DATABASE_HOSTNAME', 'host');
define('DATABASE_USERNAME', 'user');
define('DATABASE_PASSWORD', 'password');
define('DATABASE_PORT', 3306);
define('DATABASE_NAME', 'database');
?>