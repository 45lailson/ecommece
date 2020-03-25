<?php 

require_once("vendor/autoload.php");

//Diretorios namespace

use\Slim\Slim;
use\Hcode\Page;

//crinado uma nova aplicação
$app = new Slim();

//executa a função

$app->config('debug', true);

$app->get('/', function() {

	//Instancia a classe page

	$page = new Page();

	$page->setTpl("index");
   
});

//

	//execulta a função
	$app->run();

 ?>