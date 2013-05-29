<?php


/* CONFIG FOR DATABASE ACCESS */


// Username for this application access to database, the user needs to bascially have all privileges
define ('USERNAME','testapps');

// Password for the above user
define ('PASSWORD', 'quicksetup!');



/* CONFIG FOR FILE_MANAGER */


// Location, relative to the backend.php file, where all apps will be installed in their own subfolders
define ("INSTALL_FOLDER",'./test-apps');



/* CONFIG FOR INSTALLED APPS' DATABASES */


// The template for the passwords for each of the installed applications, (DATABASE_NAME) is replaced with the name of the database
define ('PASSWORD_STRING','testout(DATABASE_NAME)!');

// The prefix for all databases created for installed apps
define ('DATABASE_PREFIX','test-');




?>
