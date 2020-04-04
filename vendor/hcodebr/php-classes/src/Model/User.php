<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class User extends Model {

	const SESSION = "User";
	const SECRET = "HcodePhp7_Secret";
	const SECRET_IV = "HcodePhp7_Secret_IV";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSucesss";

	// metodo que faz uma busca no banco e se e valido

	public static function login($login, $password)
	{
       //instaciando a classe sql
		$sql = new Sql();

		// faz uma consulta no banco de dados na tabela users

		$results = $sql->select ("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		// if verifica se o results e igual a 0 e cria uma expection

		if (count($results) === 0)
		{
			throw new \Exception("Usuario inexistente ou senha inválida");
			
		}

		// Primeiro registro encontrado

		$data = $results[0];

		//Validação da senha

		if (password_verify($password, $data["despassword"]) === true)
		{

			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;
	

		} else {

			throw new \Exception("Usuario inexistente ou senha inválida");
		}

	}

    //Metodo que Verifica que se o usuario esta logado ou não
	public static function verifyLogin($inadmin = true)
	{

		if (
			!isset($_SESSION[User::SESSION]) // se ela foi definido
			||
			!$_SESSION[User::SESSION] // se ela for falsa
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 // verifica se o id do usuario e  maior q 0 e ser for e um usuario
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin // verifica se e um admistrador
			
		) {

			header("Location: /admin/login");
			exit;
		}
	}

	//metodo que excluir a session deslogando o usuario

	public static function logout()
	{

		$_SESSION[User::SESSION] == NULL;

	}


	//metodo que faz uma lista de usuarios salvos do banco


	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

	//Metodo que salva no banco de dados um insert

	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin )",
			array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
			));


			$this->setData($results[0]);
	}


	public function get($iduser)
	{
 
 		$sql = new Sql();
 
 		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser;", array(
 				":iduser"=>$iduser
 			 ));
 
			 $data = $results[0];
 
 			$this->setData($data);
 
	 }

	 public function update()
	 {


		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin )",
			array(
			"iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
			));


			$this->setData($results[0]);

	 }

	 public function delete()
	 {

	 	$sql = new Sql();

	 	$sql->query("CALL sp_users_delete(:iduser)",array(
	 		":iduser"=>$this->getiduser()
	 	));
	 }

	 // Metodo que Recupera a senha do usuario com o email Cadastrado no Banco

	 public static function getForgot($email, $inadmin = true) 
	 {
	 	$sql = new Sql();

	 	$results = $sql->select("
	 		SELECT *
	 		FROM tb_persons a 
	 		INNER JOIN tb_users b USING(idperson)
	 		WHERE a.desemail = :email;
	 	", array(
	 		":email"=>$email
	 	));

	 	// Verifica se o email existe no banco Cadastrado

	 	if(count($results) === 0)
	 	{
	 		throw new \Exception("Não foi possivel recupera a senha");
	 				
	    }
	    else
	    {

	    	$data = $results[0];

	    	$results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser , :desip)",array(
	    		":iduser"=>$data["iduser"],
	    		":desip"=>$_SERVER["REMOTE_ADDR"]
	    	));

	    	// Verifica se ouve algum problema na nossa procedure

	    	if (count($results2) === 0)
	    	{

	    		throw new \Exception("Não foi possivel recuperar a senha");
	    		
	    	}
	    	else
	    	{

	    		$dataRecovery = $results2[0];

				$code = openssl_encrypt($dataRecovery['idrecovery'], 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

				$code = base64_encode($code);

				if ($inadmin === true) {

					$link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";

				} else {

					$link = "http://www.hcodecommerce.com.br/forgot/reset?code=$code";
					
				}				

				$mailer = new Mailer($data['desemail'], $data['desperson'], "Redefinir senha da Loja Virtual LS Store", "forgot", array(
					"name"=>$data['desperson'],
					"link"=>$link
				));				

				$mailer->send();

				return $link;
				//return $data;

	    	}


	    }


	
	}

   //Metodo para desicriptar
	
		public static function validForgotDecrypt($code)
		{

			$code = base64_decode($code);

			$idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

			$sql = new Sql();

			$results = $sql->select("
				SELECT *
				FROM tb_userspasswordsrecoveries a
				INNER JOIN tb_users b USING(iduser)
				INNER JOIN tb_persons c USING(idperson)
				WHERE
					a.idrecovery = :idrecovery
				AND
					a.dtrecovery IS NULL
				AND
					DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
				", array(
				":idrecovery"=>$idrecovery
			));

			if (count($results) === 0)
			{
				throw new \Exception("Não foi possível recuperar a senha.");
			}
			else
			{

				return $results[0];

			}

	
	}

	public static function setFogotUsed($idrecovery)
	{

		$sql = new Sql();

		$sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
			":idrecovery"=>$idrecovery

		));

	}	

	public function setPassword($password)
    {

    	$sql = new Sql();

    	$sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser" , array(
    		":password"=>$password,
    		":iduser"=>$this->getiduser()

    	));

	}
}



?>
