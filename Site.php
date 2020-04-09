<?php 

use  \Hcode\Page;
use  \Hcode\Model\Product;


$app->get('/', function() {

	$products = Product::listAll();

	//Instancia a classe page

	$page = new Page();

	$page->setTpl("index",[
		'products'=>Product::checkList($products)
	]);
   
});






 ?>