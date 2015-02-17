<?php
/**
 * Site configuration
 *
 * PHP Version 5.4
 *
 * @category  Config
 * @package   Birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      http://birk.it
 */

namespace ABirkett;

// Where the site is hosted, should include protocol.
define('DOMAIN_NAME', 'http://birk.it/');
// Should be the bare domain, no protocol, subdomain or slashes. Used in regex.
define('BASIC_DOMAIN_NAME', 'birk.it');
// Database connection.
define('DATABASE_FILENAME', 'sql/birkit.db');
