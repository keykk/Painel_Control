<?php
if(filter_has_var(INPUT_POST,'update_cod') && filter_has_var(INPUT_POST,'update_titulo'))
{
	$titulo = filter_input(INPUT_POST,'update_titulo',FILTER_SANITIZE_STRING);
	$codigo = (int)filter_input(INPUT_POST,'update_cod',FILTER_SANITIZE_NUMBER_INT);
	$array = array();
	$array["StatusTXT"] = "Erro!";
	$array["Status"] = "error";
	$array["MSG"] = "";
	if($codigo > 0)
	{
		$busca = new Archivo();
		$busca->Content($codigo,"codigo","item");
		if($busca->retorno == 1)
		{
			if(preg_match("/^\w.{3,150}$/", $titulo))
			{
				$z8 = new Archivo();
				$z8->Content($titulo,"titulo","item");
				
				if($z8->retorno == 0)
				{
					try
					{
						$sql = $connnect->prepare("UPDATE item SET titulo = ? WHERE codigo = ?");
						$sql->execute(array($titulo, $busca->obj->codigo));
						if($sql->rowCount() == 1)
						{
							$array["StatusTXT"] = "Sucesso!";
							$array["Status"] = "success";
							$array["MSG"] = "Card Atualizado";
						}else
							$array["MSG"] = SqlErro($sql->errorInfo()[2]);
						
					}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
				}else
					$array["MSG"] = "Titulo rquisitado esta em uso!";
				
			}else
				$array["MSG"] = "Caracteres para Titulo 4-150!";
		}else
			$array["MSG"] = "Item não encontrado!";
	}else
		$array["MSG"] = "Item não encontrado!";
//$array = array("StatusTXT"=>"Sucesso!","Status"=>"success","MSG"=>"Teste");
	echo json_encode($array);
}
?>