<?php 

use  \Hcode\PageAdmin;
use  \Hcode\Model\User;
use  \Hcode\Model\Category;

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

$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category",[
		'category'=>$category->getValues(),
		'products'=>[]

	]);

});











 ?>