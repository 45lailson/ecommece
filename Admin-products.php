<?php 

use  \Hcode\PageAdmin;
use  \Hcode\Model\User;
use  \Hcode\Model\Product;

//Rota para listar os produtos

$app->get("/admin/products", function(){

	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : "";

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if ($search != '') {

		$pagination = Product::getPagePes($search, $page);

	} else  {

		$pagination = Product::getPagge($page);

	}

	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++)
	{

		array_push($pages, [
			'href'=>'/admin/products?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1

		]);
	}


	$products = Product::listAll(); //Metodo listAll

	$page = new PageAdmin();

	$page->setTpl("products",[
		"products"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages
	]);

});

//Rota para criar os produtos

$app->get("/admin/products/create", function(){

	User::verifyLogin(); //Verifica o Login

	$page = new PageAdmin();

	$page->setTpl("products-create");

});

//Rota para salvar os produtos

$app->post("/admin/products/create", function(){

	User::verifyLogin();

	$product = new Product();

	$product->setData($_POST);

	$product->save();

	header("Location: /admin/products");
	exit;

});
//Rota para editar os produtos

$app->get("/admin/products/:idproduct", function($idproduct){

	User:: verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update",[
		'product'=>$product->getValues()
	]);

});

// Rota Para Inserir uma Foto 

$app->post("/admin/products/:idproduct", function($idproduct){

	User:: verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();

	$product->setPhoto($_FILES["file"]);

	header('Location: /admin/products');
	exit;
	
});

//Rota para excluir os produtos

$app->get("/admin/products/:idproduct/delete", function($idproduct){

	User:: verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$product->delete();

	header("Location: /admin/products");
	exit;

});

 ?>