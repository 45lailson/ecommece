<?php 

use  \Hcode\Model\User;
use  \Hcode\Model\Cart;

//Formata os valores em BR $

function formatPrice( $vlprice)
{
	if(!$vlprice > 0) $vlprice = 0; // se ele não for maior q zero definimos igual a 0

	return number_format($vlprice, 2, "," , ".");
}

//Metodo que nos permite ser usado no template no escopo namespace Global

function checkLogin($inadmin = true) {

	return User::checkLogin($inadmin);
}

//Metodo que pega o nome do usuario logado na seção

function getUserName() {

	$user = User::getFromSession();

	return $user->getdesperson();
}

function getCartNrQtd()
{
	$cart = Cart::getFromSession();

	$totals = $cart->getProductsTotals();

	return $totals['nrqtd'];
}

function getCartvlSubTotal()
{
	$cart = Cart::getFromSession();

	$totals = $cart->getProductsTotals();

	return formatPrice($totals['vlprice']);
}


 ?>