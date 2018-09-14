<?php

//-------------------------------------------------------------
// USER DEFINED
// Edit values on right, DO NOT change values in capitals
//-------------------------------------------------------------

/* Admin folder name. If changed, enter new name here */
define('MM_ADMIN_FOLDER', 'admin');

/* Enable admin documentation link on top bar. 1 = On, 0 = Off */
define('ENABLE_DOCS_LINK', 1);

/* Enable version check in admin control panel. 1 = Yes, 0 = No */
define('ENABLE_VER_CHECK', 1);

/* Price thousands separator */
define('PRICE_THOUSANDS_SEPARATORS', ',');

/* Allowed tags. Pipe delimit. */
define('SUPPORTED_MUSIC', 'mp3|mp4');

/* Supported image extensions for covers, Pipe delimit */
define('SUPPORTED_IMAGES', 'jpg|jpeg|gif|png|tiff|bmp');

/* Enable auto reading of MP3 tags. 1 = On, 0 = Off */
define('READ_MP3_TAGS', 1);

/* Minimum version of GetID3 for tag reading. No need to change this */
define('MIN_VERSION_MP3_TAG', '5.0.5');

/* Default track cost on page load */
define('DEFAULT_TRACK_COST', '0.50');

/* Enable jquery auto complete. 1 = On, 0 = Off */
define('AUTO_COMPLETE_ENABLE', 1);

/* Auto complete min data length. */
define('AUTO_COMPLETE_MIN_LENGTH', 4);

/* Max name character display for collection names in frontend interface. 0 for full name */
define('NAME_CHAR_DISPLAY', 28);

/* Minimum search word length for frontend interface */
define('MIN_SEARCH_WORD_LENGTH', 2);

/* Display counts after style names. 1 = On, 0 = Off */
define('DISPLAY_STYLE_COUNTS', 1);
define('DISPLAY_STYLE_COUNTS_LINKED', 1);

/* Redirect time for payment gateways */
define('REDIRECT_TIME', 5);

/* Maximum latest orders to show on account homescreen */
define('ORDER_LIMIT_ACCOUNT_HOMESCREEN', 5);

/* Name for logs folder */
define('GW_LOG_FOLDER_NAME', 'logs');

/* Page refresh interval for payment gateways */
define('RESPONSE_PAGE_REFRESHES', 15);

/* Data to show per page */
define('PER_PAGE', 20);
define('ORDERS_PER_PAGE', 15);
define('COLLECTIONS_PER_PAGE', 9);
define('SEARCH_PER_PAGE', 9);

/* Limits for data on frontend interface */
define('FEATURED_HOME_LIMIT', 9);
define('LATEST_LIMIT', 9);
define('POPULAR_LIMIT', 9);

/* Limits for data shown on admin homescreen */
define('ADMIN_HOME_LATEST_SALES', 10);
define('ADMIN_HOME_LATEST_ACCOUNTS', 10);

/* Activate emails. 1 = On, 0 = Off */
define('MAIL_ACTIVATE', 1);

/* Enable mailer debug. Useful for trouble shooting. Will log entry to logs folder. 1 = On, 0 = Off */
define('MAIL_DEBUG', 0);

/* By default the X-Mailer header shows the PHP Mailer has been used, the version number and link to their github page
   To override this, enter your own custom text if applicable */
define('MAIL_X_MAIL_HEADER', '');

/* Server path to music system. DO NOT change unless you know what you are doing. */
define('MM_BASE_PATH', substr(dirname(__file__),0,strpos(dirname(__file__),'control')-1).'/');

/* Minimum digits for invoice */
define('MIN_INVOICE_DIGITS', 5);

/* Path check limit */
define('PATH_CHECK_LIMIT', 200);

/* Folders for data. Can be changed if preferred. */
define('PREVIEW_FOLDER', 'previews');
define('COVER_ART_FOLDER', 'cover-art');

/* Defines the operation system. DO NOT change */
define('MM_OS', PHP_OS);

?>