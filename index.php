<?php 

session_start();

require_once("vendor/autoload.php");

//Diretorios namespace

use\Slim\Slim;
use\Hcode\Page;
use\Hcode\PageAdmin;
use\Hcode\Model\User;
use\Hcode\Model\Category;

//crinado uma nova aplicação
$app = new Slim();

//rota do templeta index

$app->config('debug', true);

$app->get('/', function() {

	//Instancia a classe page

	$page = new Page();

	$page->setTpl("index");
   
});

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

//Listar todos os usuarios
$app->get("/admin/users", function() {

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();
	
	$page->setTpl("users", array(

		"users"=>$users

	));

});

	$app->get("/admin/users/create", function() {

	User::verifyLogin();

	$page = new PageAdmin();
	
	$page->setTpl("users-create");

});

	//Rota para excluir usuarios

	$app->get("/admin/users/:iduser/delete", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");

	exit;

});

	//Rota para atualizar os usuario

	$app->get("/admin/users/:iduser", function($iduser) {

	/*User::verifyLogin();

	$page = new PageAdmin();
	
	$page->setTpl("users-update");
	*/

   User::verifyLogin();
 
   $user = new User();
 
   $user->get((int)$iduser);
 
   $page = new PageAdmin();
 
   $page ->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));
 
});


$app->post("/admin/users/create", function () {

 	User::verifyLogin();

	$user = new User();

 	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

 	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12

 	]);

 	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
 	exit;

});


$app->post("/admin/users/:iduser", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))? 1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");

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

//Rota Para Categoria

$app->get("/admin/categories", function(){

    User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTpl("categories",[
		'categories'=>$categories
	]);

}); 

//Criação da Categorias

$app->get("/admin/categories/create", function(){

     User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");

});

//Criação da Categorias Novo

$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;

});


// Rota para excluir uma Categoria

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header('Location: /admin/categories');
	exit;


});

// Rota para atualizar uma Categoria

$app->post("/admin/categories/:idcategory", function($idcategory){
 
 User::verifyLogin();

$category = new Category();

$category->get((int)$idcategory);

$category->setData($_POST);

$category->save();

header('Location: /admin/categories');
exit;

});

// Rota para Salvar uma Categoria

$app->get("/admin/categories/:idcategory", function($idcategory){

User::verifyLogin();

$category = new Category();

$category->get((int)$idcategory);

$page = new PageAdmin();

$page->setTpl("categories-update",[
	'category'=>$category->getValues()

 ]);

});



//execulta a função
$app->run();

 ?>