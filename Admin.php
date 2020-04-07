<?php 

use  \Hcode\PageAdmin;
use  \Hcode\Model\User;


//Criando A Rota para a PageAdmin Administração

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

// Rota para validar na tela de login

$app->post('/admin/login' , function() {

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});

// Rota que faz o logout do usuario

$app->get('/admin/logout',function() {

	User::logout();

	header("Location: /admin/login");
	exit;
});

// Rota do template de esqueci a senha

$app->get("/admin/forgot", function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");

});

//Rota Para Recuperar a senha por email e que foi enviado

$app->post("/admin/forgot", function(){

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;

});

$app->get("/admin/forgot/sent", function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");

});

//Rota para Redefinir a Senha

$app->get("/admin/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);

	  $page = new Hcode\PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]

	));

});

//Rota pra redefinir a senha no prazo de 1 hora como foi a nossa validação 

$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setFogotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT,[
		"cost"=>10
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("forgot-reset-success");

});



 ?>