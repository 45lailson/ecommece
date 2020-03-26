<?php 

session_start();

require_once("vendor/autoload.php");

//Diretorios namespace

use\Slim\Slim;
use\Hcode\Page;
use\Hcode\PageAdmin;
use\Hcode\Model\User;

//crinado uma nova aplicação
$app = new Slim();

//executa a função

$app->config('debug', true);

$app->get('/', function() {

	//Instancia a classe page

	$page = new Page();

	$page->setTpl("index");
   
});

$app->get('/admin', function()  {

	User::verifyLogin();
		
	//Instancia a classe page

	$page = new PageAdmin();

	$page->setTpl("index");
   
});

//Rota para acessar o template do login e suas funções

$app->get('/admin/login', function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
});

$app->post('/admin/login' , function() {

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});

$app->get('/admin/logout',function() {

	User::logout();

	header("Location: /admin/login");
	exit;
});



//execulta a função
$app->run();

 ?>