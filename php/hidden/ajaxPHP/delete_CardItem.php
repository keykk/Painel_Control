<?php
if(filter_has_var(INPUT_POST,'delete_card_cod'))
{
	$codigo = (int)filter_input(INPUT_POST,'delete_card_cod',FILTER_SANITIZE_NUMBER_INT);
	$array = array();
	$array["StatusTXT"] = "Erro!";
	$array["Status"] = "error";
	$array["MSG"] = "";
	
	if($codigo > 0)
	{
		$cdr = new Archivo();
		$cdr->Content($codigo,"codigo","item");
		if($cdr->retorno == 1)
		{
			$perfil = new Archivo();
			$perfil->Content($cdr->obj->codigo,"item_codigo","exibe_item");
			if($perfil->retorno == 0)
			{
				try
				{
					$sql = $connnect->prepare("DELETE FROM item WHERE codigo = ?");
					$sql->execute(array($cdr->obj->codigo));
					
					if($sql->rowCount() == 1)
					{
						$array["StatusTXT"] = "Sucesso!";
						$array["Status"] = "success";
						$array["MSG"] = "Registro excluido, Atualize a pagina.";
					}else
						$array["MSG"] = SqlErro($sql->errorInfo()[2]);
				}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
			}else
				$array["MSG"] = "Card não pode ser excluido pois esta em uso!";
		}else
			$array["MSG"] = "Card não encontrado!";
	}else
		$array["MSG"] = "Card não encontrado!";
	
	
	echo json_encode($array);
}

?>