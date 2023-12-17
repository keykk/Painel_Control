<?php
$array = array();
	$array["StatusTXT"] = "Erro!";
	$array["Status"] = "error";
	$array["MSG"] = "";
	
	if(filter_has_var(INPUT_POST,'restringe_item_cod'))
	{
		$codigo = (int)filter_input(INPUT_POST,'restringe_item_cod',FILTER_SANITIZE_NUMBER_INT);
		
		if($codigo > 0)
		{
			$card = new Archivo();
			$card->Content($codigo,"codigo","item");
			if($card->retorno == 1)
			{
				$exibe = new Archivo();
				$exibe->Content($card->obj->codigo,"item_codigo","exibe_item");
				if($exibe->retorno > 0)
				{
					try
					{
						$escritas = new Archivo();
						$escritas->Content($card->obj->codigo, "item_codigo", "escrita");
						if($escritas->retorno > 0)
						{
							$remove = $connnect->prepare("DELETE FROM escrita WHERE item_codigo = ?");
							$remove->execute(array($card->obj->codigo));

							if($remove->rowCount() <= 0)
							{
								$array["MSG"] = SqlErro($remove->errorInfo()[2]);
								echo json_encode($array);
								return false;


							}
						}

						$sql = $connnect->prepare("DELETE FROM exibe_item WHERE item_codigo = ?");
						$sql->execute(array($card->obj->codigo));
						
						if($sql->rowCount() > 0)
						{
							$array["StatusTXT"] = "Sucesso!";
							$array["Status"] = "success";
							$array["MSG"] = "Regras apagada!";
						}else
							$array["MSG"] = SqlErro($sql->errorInfo()[2]);
						
					}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
				}else
					$array["MSG"] = "Não existe nenhuma regra de exibição a ser apagada!";
			}else
				$array["MSG"] = "Item não encontrado!";
		}else
			$array["MSG"] = "Item inválido!";
	}
	
	echo json_encode($array);

?>