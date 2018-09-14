<?php

/*============================================================================================================
  DATABASE CONNECTION PARAMETERS
  ==============================

  Enter your database connection parameters. If you are not sure of this, please contact
  your web host. If you get a message along the lines of 'Access denied for user..', then
  your connection information is not correct.

  Important: The table prefix is for people with only a single database. If you aren`t bothered
  about the prefix, do NOT comment it out. Leave it blank if no prefix is required.

  NOTE: Edit values on right, DO NOT change values in capitals

==============================================================================================================*/

define('DB_HOST', 'Host name goes here..');
define('DB_USER', 'Database user goes here..');
define('DB_PASS', 'Database password goes here..');
define('DB_NAME', 'Database name goes here..');
define('DB_PREFIX', 'mm_');
define('DB_CHAR_SET', 'utf8'); // Character encoding set

/*============================================================================================================
  SECRET KEY OR SALT
  ==================

  Specify secret key (also known as salt). This is for security and is encrypted during script execution.
  DO NOT change this value at a later date. Change ONLY before a clean install.

  This should ideally be a mix of random numbers, letters and characters. You can use sha1 and md5 for added
  security. You should not use something that changes with each page load.

  GOOD examples:
  define('SECRET_KEY', 'jh7sghe[]]0gjhfger');
  define('SECRET_KEY', md5('jh7sghe[]]0gjhfger'));
  define('SECRET_KEY', sha1('jh7sghe[]]0gjhfger'));

  BAD examples:
  define('SECRET_KEY', rand(1111,9999));
  define('SECRET_KEY', sha1(rand(1111,9999)));

  If you are using this system on multiple domains, set a different key for each

============================================================================================================
*/

define('SECRET_KEY', 'music-store2015');

?>