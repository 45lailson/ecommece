<?php 

namespace Hcode;

class Model {

	// tem todos os dados dos campos do objeto

	private $values = [];

	// metodo inteligente, ele pega o set que a gente usou e fez quebrar em duas partes

	public function __call($name, $args)
	{
		//verifica se e metodo Get ou Set

		//substr -> Quantidade

		$method = substr($name, 0, 3);
		
		//$fieldName -> qual nome do campo foi chamado
		//strlen -> Conta quantas palavras tem
		$fieldName = substr($name, 3, strlen($name));

		switch ($method)
		{

			case "get":
				return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL;
			break;

			case "set":
				$this->values[$fieldName] = $args[0];
			break;

		}

	}

	public function setData($data = array())
	{

		foreach ($data as $key => $value) {
			
			$this->{"set".$key}($value);

		}

	}

	// Metodo da um retorno ao atributo
	
	public function getValues() {

		return $this->values;
	}
	

}

 ?>