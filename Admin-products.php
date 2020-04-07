<?php 

use  \Hcode\PageAdmin;
use  \Hcode\Model\User;
use  \Hcode\Model\Product;

//Rota para listar os produtos

$app->get("/admin/products", function(){

	User:: verifyLogin();

	$products = Product::listAll();

	$page = new PageAdmin();

	$page->setTpl("products",[
		"products"=>$products
	]);

});

//Rota para criar os produtos

$app->get("/admin/products/create", function(){

	User:: verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");

});


//Rota para salvar os produtos

$app->post("/admin/products/create", function(){

	User:: verifyLogin();

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