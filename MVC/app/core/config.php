<?php
#put <?=ROOT in the beginning when referencing using absolute path
if ($_SERVER['SERVER_NAME'] == 'localhost') {

    #database config
    define('DBNAME', 'CareerSync');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');

    define('ROOT', 'http://localhost/CareerSync/MVC/public/');

} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'];
    $baseUrl  = $protocol . '://' . $host . '/CareerSync/MVC/public/';

    define('DBNAME', 'CareerSync');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('ROOT', $baseUrl);
}
