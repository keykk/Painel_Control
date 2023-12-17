<?php
if(filter_has_var(INPUT_POST,'excluir_usuario_id') && filter_has_var(INPUT_POST,'item_id') && $operacao == "DELETE")
{
	$usuario_id = (int)filter_input(INPUT_POST,'excluir_usuario_id',FILTER_SANITIZE_NUMBER_INT);
	$item_id = (int)filter_input(INPUT_POST,'item_id',FILTER_SANITIZE_NUMBER_INT);
	
	$permitir = new Escrita();
	$permitir->Gravar($usr->perfil->codigo,$item_id,"DELETE");
	
	if($usuario_id > 0 && $item_id > 0 || $usuario_id > 0 && $usr->perfil->titulo === "Admin")
	{
		if($permitir->retorno == 1)
		{
			$arquivo = new Archivo();
			$arquivo->Content($usuario_id,"codigo","user");
			if($arquivo->retorno == 1)
			{
				$arquivo_perfil = new Archivo();
				$arquivo_perfil->Content($arquivo->obj->perfil_codigo,"codigo","perfil_acesso");
				
				if($arquivo_perfil->retorno == 1)
				{
					if($arquivo_perfil->obj->titulo !== "Admin")
					{
						try
						{
							$sql = $connnect->prepare("DELETE FROM user WHERE codigo = ?");
							$sql->execute(array($arquivo->obj->codigo));
							
							if($sql->rowCount() == 1)
							{
								$array["StatusTXT"] = "Sucesso!";
								$array["Status"] = "success";
								$array["MSG"] = "Usuário excluido! recarregue a pagina.";
							}else
								$array["MSG"] = SqlErro($sql->errorInfo()[2]);
							
						}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
					}else
						$array["MSG"] = "Usuários do tipo Admin não pode ser excluido!";
				}else
				{
					try
					{
						$sql = $connnect->prepare("DELETE FROM user WHERE codigo = ?");
						$sql->execute(array($arquivo->obj->codigo));
						if($sql->rowCount() == 1)
						{
							$array["MSG"] = "Usuário requisitado sem perfil de acesso, foi excluido!";
						}else
							$array["MSG"] = SqlErro($sql->errorInfo()[2]);
					}catch(PDException $e){}
				}
			}else
				$array["MSG"] = "Usuário não encontrado!";
		}else
			$array["MSG"] = "Acesso Negado!";
	}else
		$array["MSG"] = "Dados Invalidos!";
	//echo json_encode($array);
}

?>