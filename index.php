<?php 

session_start();

require_once("vendor/autoload.php");

//Diretorios namespace

use\Slim\Slim;


//crinado uma nova aplicação
$app = new Slim();



$app->config('debug', true);

require_once("Site.php");
require_once("Functions.php");
require_once("Admin.php");
require_once("Admin-users.php");
require_once("Admin-categories.php");
require_once("Admin-products.php");
require_once("Admin-orders.php");


//execulta a função
$app->run();

 ?>