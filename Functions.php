<?php 

//Formata os valores em BR $

function formatPrice(float $vlprice)
{
	return number_format($vlprice, 2, "," , ".");
}


 ?>