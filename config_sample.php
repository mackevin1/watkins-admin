<?php
/*base url to the installation folder.. ensure trailing slash */
define ( 'KSCONFIG_URL', '//localhost/kspanel/');

/*system name, change as required */
define ( 'KSCONFIG_SYSTEM_NAME', 'Web Application');

/*database type */
define ( 'KSCONFIG_DB_TYPE', 'mysql');

/*database server hostname, domain or IP */
define ( 'KSCONFIG_DB_HOST', 'localhost');

/*database name / schema */
define ( 'KSCONFIG_DB_NAME', 'kspanel');

/*database user name  */
define ( 'KSCONFIG_DB_USER', 'root');

/*database password for above user name */
define ( 'KSCONFIG_DB_PASSWORD', 'password');

/*port number to connect to the database..3306 is default port for mysql */
define ( 'KSCONFIG_DB_PORT', 3306);

/*base path to the installation folder */
define ( 'KSCONFIG_ABSPATH', dirname (__FILE__) . DIRECTORY_SEPARATOR);

/*path to control panel.. if the folder is renamed, change it here */
define ( 'KSCONFIG_CONTROLPANEL_PATH', KSCONFIG_ABSPATH . 'ks_admin/' );

/*class path where all CUSTOM classes and Zend Framework are located*/
define ( 'KSCONFIG_CLASS_PATH', KSCONFIG_ABSPATH . 'ks_library/');

/*log all ks errors into this file */
define ( 'KSCONFIG_ERROR_LOG', KSCONFIG_ABSPATH. 'error.txt');

/*ksp version */
define ( 'KSCONFIG_VERSION', '1.0.0');

