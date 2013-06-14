<?php


/* CONFIG FOR DATABASE ACCESS */


// Username for this application access to database, the user needs to bascially have all privileges
define ('USERNAME','PUT_DATABASE_USER_HERE');

// Password for the above user
define ('PASSWORD', 'PUT_DATABASE_PASSWORD_HERE');



/* CONFIG FOR FILE_MANAGER */


// Location, relative to the backend.php file, where all apps will be installed in their own subfolders, do NOT end with a slash
define ("INSTALL_FOLDER",'./test-apps');

// Location of the temporary directory to which files being downloaded are put, do NOT end with a slash
define ("TEMP_FOLDER",'./temp');

/* CONFIG FOR INSTALLED APPS' DATABASES */


// The template for the passwords for each of the installed applications, (DATABASE_NAME) is replaced with the name of the database
define ('PASSWORD_STRING','testout(DATABASE_NAME)!');

// The prefix for all databases created for installed apps
define ('DATABASE_PREFIX','test-');




?>
