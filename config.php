<?php
//-----------------------------------------------------------------------------
// Site configuration
//-----------------------------------------------------------------------------

function PHPDefaults()
{
    //Show PHP errors and warnings
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    //Timezone for converting timestamps
    date_default_timezone_set("Europe/London");
}

//This is where the site is hosted. Allows the service to move, should include protocol
define('DOMAIN_NAME', 'http://birk.it/');
//This is used in regex, should be the bare domain, no protocol, subdomain or slashes
define('BASIC_DOMAIN_NAME', 'birk.it');

//Database connection
define('DATABASE_FILENAME', 'sql/birkit.db');
