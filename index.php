<?php
define('ALLOW_ACCESS', 1); //Define the ALLOW_ACCESS constant so that other pages in the system can only be accessed by first passing through this page

require_once (__DIR__ . '/config.php'); //Include the MySQL configuration file

//Set up the class auto loader so we don't have to include each class file manually
function classAutoloader($class) { require_once (__DIR__ . '/classes/class.' . $class . '.php'); }
spl_autoload_register('classAutoloader');

$database = new Database($config['mysql']['host'], $config['mysql']['port'], $config['mysql']['database'], $config['mysql']['username'], $config['mysql']['password']); //Create a new connection to the database
$core = new Core($database); //Create a new Core object to handle page routing
$core->route(); //Route the user's request