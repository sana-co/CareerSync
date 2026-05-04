<?php
#the ones capitalized first are classes
require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
require 'SystemLogger.php';

//loads a class that you can find
spl_autoload_register(function ($className) {
    $filename = "../app/models/" . ucfirst($className) . ".php";
    require $filename;
});
