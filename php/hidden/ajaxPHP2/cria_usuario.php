<?php
if(filter_has_var(INPUT_POST,'novo_user') && filter_has_var(INPUT_POST,'novo_user_pw') && filter_has_var(INPUT_POST,'novo_user_nome') && filter_has_var(INPUT_POST,'novo_user_perfil') && $operacao == "INSERT")
{
	
	$login = filter_input(INPUT_POST,'novo_user',FILTER_SANITIZE_STRING);
	$senha = MD5(filter_input(INPUT_POST,'novo_user_pw',FILTER_SANITIZE_STRING));
	$nome = filter_input(INPUT_POST,'novo_user_nome',FILTER_SANITIZE_STRING);
	$perfil = (INT)filter_input(INPUT_POST,'novo_user_perfil',FILTER_SANITIZE_NUMBER_INT);
	
	
		if(preg_match("/^\w.{3,99}$/", $login))
		{
			if(preg_match("/^\w.{3,99}$/", $nome))
			{
				if($perfil > 0)
				{
					$p = new Archivo();
					$p->Content($perfil,"codigo","perfil_acesso");
					if($p->retorno == 1)
					{
						if($p->obj->titulo !== "Admin" || $usr->perfil->titulo === "Admin")
						{
							$u = new Archivo();
							$u->Content($login,"login","user");
							if($u->retorno == 0)
							{
							try
							{
								$sql = $connnect->prepare("INSERT INTO user (login,senha,nome,perfil_codigo) VALUES (:login,:senha,:nome,:perfil_id);");
								
								$sql->execute([':login'=>$login, ':senha'=>$senha, ':nome'=>$nome, ':perfil_id'=>$p->obj->codigo]);
								
								if($sql->rowCount() == 1)
								{
									$array["StatusTXT"] = "Sucesso!";
									$array["Status"] = "success";
									$array["MSG"] = "Usuário criado!";
								}else
									$array["MSG"] = SqlErro($sql->errorInfo()[2]);
							}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
							}else
								$array["MSG"] = "Login ja esta em uso!";
						}else
							$array["MSG"] = "Acesso negado, para criar Admin!";
					}else
						$array["MSG"] = "perfil não encontrado!";
				}else
					$array["MSG"] = "Informe o perfil de acesso!";
			}else
				$array["MSG"] = "Caracteres para nome 4-99";
			
		}else
			$array["MSG"] = "Caracteres em login 4-99";
	
	
	//echo json_encode($array);
}



?>