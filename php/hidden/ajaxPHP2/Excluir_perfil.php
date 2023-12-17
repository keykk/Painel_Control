<?php
if(filter_has_var(INPUT_POST,'excluir_perfil_id') && filter_has_var(INPUT_POST,'item_id') && $operacao == "DELETE")
{
	$perfil_id = (int)filter_input(INPUT_POST,'excluir_perfil_id',FILTER_SANITIZE_NUMBER_INT);
	$item_id = (int)filter_input(INPUT_POST,'item_id',FILTER_SANITIZE_NUMBER_INT);
	
	if($perfil_id > 0 && $item_id > 0 || $perfil_id > 0 && $usr->perfil->titulo === "Admin")
	{
			$perfill = new Archivo();
			$perfill->Content($perfil_id,"codigo","perfil_acesso");
			
			if($perfill->retorno == 1)
			{
				if($perfill->obj->titulo !== "Admin")
				{
					$usuarios = new Archivo();
					$usuarios->Content($perfill->obj->codigo,"perfil_codigo","user");
					if($usuarios->retorno == 0)
					{
						try
						{
							$sql = $connnect->prepare("DELETE FROM perfil_acesso WHERE codigo = ?");
							$sql->execute(array($perfill->obj->codigo));
							if($sql->rowCount() == 1)
							{
								$array["StatusTXT"] = "Sucesso!";
								$array["Status"] = "success";
								$array["MSG"] = "Perfil foi excluido! Atualize a pagina.";
							}else
								$array["MSG"] = SqlErro($sql->errorInfo()[2]);
							
						}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
					}else
						$array["MSG"] = "Perfil esta em uso, não pode ser excluido!";
				}else
					$array["MSG"] = "Acesso Negado! Admin não pode ser excluido";
			}else
				$array["MSG"] = "Perfil solicitado não encontrado!";
		
	}else
		$array["MSG"] = "Dados não autorizados detectado!!";
	
	//echo json_encode($array);
}

?>