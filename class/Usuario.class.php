<?php
class Usuario{
	public $objeto,$retorno;
	
	function setObjeto($valor){$this->objeto=$valor;}
	function setRetorno($valor){$this->retorno=$valor;}
	function setPerfil($valor){$this->perfil=$valor;}
	function Ver(){
		$login = null;
		$senha = null;
		if(isset($_SESSION['dashboard_login']) && isset($_SESSION['dashboard_senha']))
		{
			$login = $_SESSION['dashboard_login'];
			$senha = $_SESSION['dashboard_senha'];
		}
		$connnect = Conexao::getInstance();
		$q = $connnect->prepare("SELECT * FROM user WHERE login = ? AND senha = ?");
		$q->execute(array($login , $senha));
		//$q->execute([':login' => $login, ':senha' => $senha]);
		
		//$sth = $dbh->prepare('SELECT name, colour, calories FROM fruit WHERE calories < ? AND colour = ?');
		//$sth->execute(array(150, 'red'));
		
		$this->retorno = $q->rowCount();
		if($this->retorno == 1){
			$this->objeto=$q->fetch(PDO::FETCH_OBJ);
			$b = $connnect->prepare("SELECT * FROM perfil_acesso WHERE codigo = :perfil_id");
			$b->execute([':perfil_id' => $this->objeto->perfil_codigo]);
			if($b->rowCount() == 1){
				$this->perfil = $b->fetch(PDO::FETCH_OBJ);
			}else
			{
				session_destroy();
				session_start();
			}
			
		}else if(isset($_SESSION))
		{
			session_destroy();
			session_start();
		}
		
	}
	
}


?>