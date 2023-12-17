<?php
if(filter_has_var(INPUT_POST,'altera_perfil_titulo') && filter_has_var(INPUT_POST,'altera_perfil_id') && filter_has_var(INPUT_POST,'altera_item') && filter_has_var(INPUT_POST,'altera_perfil_desc') && $operacao == "UPDATE")
{
	$titulo = filter_input(INPUT_POST,'altera_perfil_titulo',FILTER_SANITIZE_STRING);
	$desc = filter_input(INPUT_POST,'altera_perfil_desc',FILTER_SANITIZE_STRING);
	$perfil_id = (int)filter_input(INPUT_POST,'altera_perfil_id',FILTER_SANITIZE_NUMBER_INT);
	$item_id = (int)filter_input(INPUT_POST,'altera_item',FILTER_SANITIZE_NUMBER_INT);
	
	
	$grup = new Archivo();
	$grup->Content($perfil_id,"codigo","perfil_acesso");
	if($grup->retorno == 1){
		if($usr->perfil->titulo === "Admin")
			$id = $grup->obj->codigo;
		else
			$id = $permitir->obj->perfil_codigo;
		$array["titulo"] = $grup->obj->titulo;
		$array["desc"] = $grup->obj->descricao;
		if($permitir->retorno == 1)
		{
			if($grup->obj->titulo !== "Admin"){
			if(preg_match("/^\w.{3,99}$/", $titulo))
			{
				$sql = $connnect->prepare("UPDATE perfil_acesso SET titulo = ?, descricao = ? WHERE codigo = ?");
				try
				{
					$connnect->beginTransaction();
					$sql->execute(array($titulo, $desc, $id));
					if($sql->rowCount() == 1)
					{
						$array["Status"] = "success";
						$array["MSG"] = "Sucesso!";
						$original_info = new Archivo();
						$original_info->Content($grup->obj->codigo,"codigo","perfil_acesso");
						$array["titulo"] = $original_info->obj->titulo;
						$array["desc"] = $original_info->obj->descricao;
					}else
					{
						$array["MSG"] = SqlErro($sql->errorInfo()[2]);
					}
					$connnect->commit();
				}catch(Exception $e){
					$connnect->rollback();
					$array["MSG"] = $e->getMessage();
				}
			}else
				$array["MSG"] = "Caracteres para titulo 4 - 99";
			}else
				$array["MSG"] = "Acesso Negado!";
		}else
			$array["MSG"] = "Acesso Negado!";
	}else
		$array["MSG"] = "Perfil não encontrado!";
	
	//echo json_encode($return);
}

?>