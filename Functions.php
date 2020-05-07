<?php 

use  \Hcode\Model\User;

//Formata os valores em BR $

function formatPrice( $vlprice)
{
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


 ?>