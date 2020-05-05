<?php 

use  \Hcode\Page;
use  \Hcode\Model\Product;
use  \Hcode\Model\Category;
use  \Hcode\Model\Cart;


$app->get('/', function() {

	$products = Product::listAll(); //Metodo Estatico

	//Instancia a classe page

	$page = new Page();

	$page->setTpl("index",[
		'products'=>Product::checkList($products)
	]);
   
});

// Rota que define a quantidade de produtos que vai ser comprado


$app->get("/categories/:idcategory", function($idcategory){

	//Isset verifica se foi iniciada ou se existe

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] :1; // Se não for definido a página é 1

	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);

	$pages = [];

 //No for() que está acima, nós estamos criando links de paginação. Cada um deles irá referenciar o número da página, que no caso é a própria variável $i

	for ($i=1; $i <= $pagination['pages'] ; $i++) { 
		array_push($pages, [
			'link'=>'/categories/'.$category->getidcategory(). '?page='.$i, //Caminho que vai ser direcionado o usuário
			'page'=>$i
		]);
	}

	$page = new Page();

	$page->setTpl("category",[
		'category'=>$category->getValues(),
		'products'=>$pagination["data"], //produtos estão na chave data
		'pages'=>$pages

	]);

});

//Rota para ver detalhes do produto

$app->get("/products/:desurl", function($desurl){

	$product = new Product();

	$product->getFromURL($desurl);

	$page = new Page();

	$page->setTpl("product-detail",[
		'product'=>$product->getValues(),
		'categories'=>$product->getCategories()

	]);

});

//Rota para o Carrinho

$app->get("/cart", function(){

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart",[
		'cart'=>$cart->getValues(),
		'products'=>$cart->getProducts(),
		'error'=>Cart::getMsgError()

	]);

});

//Rota para adicionar um produto no carrinho

$app->get("/cart/:idproduct/add", function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

//Isset verifica se foi iniciada ou se existe

	$qtd = (isset($_GET['qtd'])) ? (int)$_GET['qtd'] : 1;

// o laço for tem a função de colocar quantos os produtos serão adicionados no carrinho

	for ($i=0; $i <  $qtd ; $i++) { 
		
		$cart->addProduct($product);

	}

	header("Location: /cart");
	exit();

});

//Rota para Excluir um produto no carrinho

$app->get("/cart/:idproduct/minus", function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");
	exit();
	
});

//Rota para Excluir Todos os produto no carrinho

$app->get("/cart/:idproduct/remove", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");
	exit;

});

//Rota Para Calcular o Frete 

$app->post("/cart/freight", function() {

	$cart = Cart::getFromSession(); //Metodo Estatico

	$cart->setFreight($_POST['zipcode']);

	header("Location: /cart");
	exit;

});







 ?>