<?php 

namespace Hcode;

class PageAdmin extends Page {

	public function __construct($opts = array(), $tpl_dir = "/views/admin/")
	{
		//reaproventando o metodo construtor da class Pai Page.php
		parent::__construct($opts, $tpl_dir);
	}


}


 ?>