<?php
if(filter_has_var(INPUT_POST,'r_raiz') && filter_has_var(INPUT_POST,'r_titulo'))
{
	$titulo = filter_input(INPUT_POST,'r_titulo',FILTER_SANITIZE_STRING);
	$raiz = filter_input(INPUT_POST,'r_raiz',FILTER_SANITIZE_STRING);
	$array = array();
	$array["StatusTXT"] = "Erro!";
	$array["Status"] = "error";
	$array["MSG"] = "";
	if(preg_match("/^\w.{3,150}$/", $titulo))
	{
		if($usr->perfil->titulo === "Admin")
		{
			$it = new Archivo();
			$it->Content($raiz,"raiz","item");
			
			if($it->retorno == 0)
			{
				$it->Content($titulo,"titulo","item");
				if($it->retorno == 0)
				{
					try
					{
					$sql = $connnect->prepare("INSERT INTO item (titulo,raiz) VALUES (?,?)");
					$sql->execute(array($titulo,$raiz));
					
					if($sql->rowCount() == 1)
					{
						$array["StatusTXT"] = "Sucesso!";
						$array["Status"] = "success";
						$array["MSG"] = "Card cadastrado, atualize a pagina.";
					}else
						$array["MSG"] = SqlErro($sql->errorInfo()[2]);
					
					}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
				}else
					$array["MSG"] = "Titulo ja esta em uso!";
			}else
				$array["MSG"] = "Item ja cadastrado!";
		}else
			$array["MSG"] = "Acesso negado!";
	}else
		$array["MSG"] = "Caracteres para titulo 4 - 150!";
	//$array = array("StatusTXT"=>"Sucesso!","Status"=>"success","MSG"=>$raiz);
	echo json_encode($array);
}
?>