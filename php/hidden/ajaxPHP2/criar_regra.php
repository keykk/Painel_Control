<?php
if(filter_has_var(INPUT_POST,'cria_regras_itens') && filter_has_var(INPUT_POST,'cria_regras_perfil'))
{
	$perfil_id = (INT)filter_input(INPUT_POST,'cria_regras_perfil',FILTER_SANITIZE_NUMBER_INT);
	$itens = filter_input(INPUT_POST,'cria_regras_itens',FILTER_SANITIZE_STRING);

	if($usr->perfil->titulo === "Admin")
	{
		
		
		$item = explode(",",$itens);
		if($perfil_id > 0)
		{
			
			$p = new Archivo();
			$p->Content($perfil_id,"codigo","perfil_acesso");
			
			if($p->retorno == 1)
			{
				
				$verr = new Archivo();
				foreach($item as $it)
				{
					$it = (int)$it;
					if($it > 0){
						$verr->Content($it,"codigo","item");
						if($verr->retorno == 1)
						{
							$qy = $connnect->prepare("SELECT * FROM exibe_item WHERE perfil_codigo = :perfil_id AND item_codigo = :item_id");
							$qy->execute([':perfil_id' => $p->obj->codigo, ':item_id' => $verr->obj->codigo]);
							if($qy->rowCount() == 0){
								//$array["MSG"] = "Registrado\n";
								try
								{
									$sql = $connnect->prepare("INSERT INTO exibe_item (perfil_codigo,item_codigo) VALUES (:perfil_id,:item_id)");
									
									$sql->execute([':perfil_id' => $p->obj->codigo, ':item_id' => $verr->obj->codigo]);
									
									if($sql->rowCount() == 1)
									{
										$array["StatusTXT"] = "Sucesso!";
										$array["Status"] = "success";
										
										$array["MSG"] = "Regra criada!";
									}else
									{
										$array["MSG"] = SqlErro($sql->errorInfo()[2]);
										$array["errorItens"] .= ",{$it}";
									}
										
								}catch(PDOException $e){}
							}else
							{
								//$array["MSG"] = "Regra ja existe!";
							}
						}else
						{
							$array["MSG"] = "Item não existe!";
							//$array["errorItens"] .= ",{$it}";
						}
					}else
					{
						$array["MSG"] = "Item inválido!";
						$array["errorItens"] .= ",{$it}";
					}
				}
			}else
			{
				$array["errorItens"] = ",".$itens;
				$array["MSG"] = "Perfil não existe!";
			}
		}else
		{
			$array["errorItens"] = ",".$itens;
			$array["MSG"] = "Perfil não existe!";
		}
	}else{
		$array["MSG"] = "Acesso Negado!";
		//$array["errorItens"] = ",".$itens;
	}
	
	//echo json_encode($array);
}


?>