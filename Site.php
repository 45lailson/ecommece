<?php 

use  \Hcode\Page;


$app->get('/', function() {

	//Instancia a classe page

	$page = new Page();

	$page->setTpl("index");
   
});






 ?>