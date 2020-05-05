<?php 

use  \Hcode\PageAdmin;
use  \Hcode\Model\User;
use  \Hcode\Model\Category;
use  \Hcode\Model\Product;

//Rota Para lista Categoria

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

// Rota para Atualizar uma Categoria

$app->get("/admin/categories/:idcategory", function($idcategory){

User::verifyLogin();

$category = new Category();

$category->get((int)$idcategory);

$page = new PageAdmin();

$page->setTpl("categories-update",[
	'category'=>$category->getValues()

 ]);

});

//Rota Para Mostrar Produtos Relacionados Por sua Categotia
//Rota Para Mostrar Produtos Que não esta Relacionados 

$app->get("/admin/categories/:idcategory/products", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products",[
		'category'=>$category->getValues(),
		'productsRelated'=>$category->getProducts(),
		'productsNotRelated'=>$category->getProducts(false)

	]);

});

//Rota para adicionar uma categoria

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory,$idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/" .$idcategory."/products");
	exit;

});

//Rota para deletar uma categoria

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory,$idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/" .$idcategory."/products");
	exit;

});
















 ?>