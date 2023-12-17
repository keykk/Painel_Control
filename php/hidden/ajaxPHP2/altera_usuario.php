<?php
if(filter_has_var(INPUT_POST,'item_id')){
	
		if(filter_has_var(INPUT_POST,'update_user_id') && filter_has_var(INPUT_POST,'update_user_p') && $operacao == "UPDATE")
		{
			$userid = (INT)filter_input(INPUT_POST,'update_user_id',FILTER_SANITIZE_NUMBER_INT);
			$perfil_accs = (INT)filter_input(INPUT_POST,'update_user_p',FILTER_SANITIZE_NUMBER_INT);
			
			if($userid > 0 && $perfil_accs > 0)
			{
				$user_q = new Archivo();
				$user_q->Content($userid,"codigo","user");
				
				if($user_q->retorno == 1)
				{
					$pegaGrupo = new Archivo();
					$pegaGrupo->Content($user_q->obj->perfil_codigo,"codigo","perfil_acesso");
					$perfil_q = new Archivo();
					$perfil_q->Content($perfil_accs,"codigo","perfil_acesso");
					
					if($perfil_q->retorno == 1)
					{
						if($perfil_q->obj->titulo !== "Admin" || $usr->perfil->titulo === "Admin")
						{
							if($pegaGrupo->obj->titulo !== "Admin"){
							if($usr->objeto->codigo != $userid)
							{
								try
								{
									$sql = $connnect->prepare("UPDATE user SET perfil_codigo = :perfil_id WHERE codigo = :user_id");
									
									$sql->execute([':perfil_id' => $perfil_q->obj->codigo, ':user_id' => $user_q->obj->codigo]);
									
									if($sql->rowCount() == 1)
									{
										$array["StatusTXT"] = "Sucesso!";
										$array["Status"] = "success";
										$array["MSG"] = "Perfil de acesso alterado!";
									}else
										$array["MSG"] = SqlErro($sql->errorInfo()[2]);
								}catch(PDException $e){$array["MSG"] = $e->getMessage();}
							}else
								$array["MSG"] = "Acesso negado!";
							}else
								$array["MSG"] = "Usuários com perfil Admin, não pode ser alterado!";
						}else
							$array["MSG"] = "Acesso negado!";
					}else
						$array["MSG"] = "Perfil não encontrado!";
				}else
					$array["MSG"] = "Usuário não encontrado!";
			}else
				$array["MSG"] = "Usuário ou perfil inválido!";
		}else if(filter_has_var(INPUT_POST,'update_user_login') && filter_has_var(INPUT_POST,'update_user_nome') && filter_has_var(INPUT_POST,'update_user_id') && $operacao == "UPDATE")
		{
			$uID = (INT)filter_input(INPUT_POST,'update_user_id',FILTER_SANITIZE_NUMBER_INT);
			$uLOGIN = filter_input(INPUT_POST,'update_user_login',FILTER_SANITIZE_STRING);
			$uNOME = filter_input(INPUT_POST,'update_user_nome',FILTER_SANITIZE_STRING);
			
			if($uID > 0)
			{
				$u = new Archivo();
				$u->Content($uID,"codigo","user");
				
				if($u->retorno == 1)
				{
					$acesso_obj = new Archivo();
					$acesso_obj->Content($u->obj->perfil_codigo,"codigo","perfil_acesso");
					if($acesso_obj->obj->titulo !== "Admin")
					{
					if(preg_match("/^\w.{3,99}$/", $uLOGIN))
					{
						if(preg_match("/^\w.{3,99}$/", $uNOME))
						{
							try
							{
								$sql = $connnect->prepare("UPDATE user SET login = :login2, nome = :nome2 WHERE codigo = :user_id2");
								
								$sql->execute([':login2' => $uLOGIN, ':nome2' => $uNOME, ':user_id2' => $uID]);
								
								if($sql->rowCount() == 1)
								{
									$array["StatusTXT"] = "Sucesso!";
									$array["Status"] = "success";
									$array["MSG"] = "Usuário alterado!";
								}else
									$array["MSG"] = SqlErro($sql->errorInfo()[2]);
								
							}catch(PDOException $e){$array["MSG"] = $e->getMessage();}
						}else
							$array["MSG"] = "Caracteres para nome 4-99";
					}else
						$array["MSG"] = "Caracteres para login 4-99";
					}else
						$array["MSG"] = "Usuários com perfil Admin, não pode ser alterado!";
				}else
					$array["MSG"] = "Usuário não encontrado!";
			}else
				$array["MSG"] = "Usuário não encontrado!";
		}else if(filter_has_var(INPUT_POST,'usuario_update_password') && filter_has_var(INPUT_POST,'usuario_password_id') && $operacao == "UPDATE")
		{
			$user_pw = MD5(filter_input(INPUT_POST,'usuario_update_password',FILTER_SANITIZE_STRING));
			$user_id = (INT)filter_input(INPUT_POST,'usuario_password_id',FILTER_SANITIZE_NUMBER_INT);
			
			if($user_id > 0)
			{
				$user_obj = new Archivo();
				$user_obj->Content($user_id,"codigo","user");
				
				if($user_obj->retorno == 1)
				{
					$acesso_obj = new Archivo();
					$acesso_obj->Content($user_obj->obj->perfil_codigo,"codigo","perfil_acesso");
					
					if($acesso_obj->retorno == 1)
					{
						if($acesso_obj->obj->titulo !== "Admin")
						{
							try
							{
								$sql = $connnect->prepare("UPDATE user SET senha = :user_senha WHERE codigo = :user_id");
								$sql->execute([':user_senha' => $user_pw, ':user_id' => $user_obj->obj->codigo]);
								
								if($sql->rowCount() == 1)
								{
									$array["StatusTXT"] = "Sucesso!";
									$array["Status"] = "success";
									
									$array["MSG"] = "Senha alterada!";
								}else
									$array["MSG"] = SqlErro($sql->errorInfo()[2]);
							}catch(PDOException $e){}
						}else
							$array["MSG"] = "Usuários com perfil Admin, não pode ser alterado!";
					}else
						$array["MSG"] = "Usuário sem perfil de acesso!";
				}else
					$array["MSG"] = "Usuário não encontrado!";
			}else
				$array["MSG"] = "Usuário Inválido!";
		}

	
	if(filter_has_var(INPUT_POST,'update_user_id'))
	{
		$id = (INT)filter_input(INPUT_POST,'update_user_id',FILTER_SANITIZE_NUMBER_INT);
		if($id > 0)
		{
			$atualizar = new Archivo();
			$atualizar->Content($id,"codigo","user");
			
			if($atualizar->retorno == 1)
			{
				$array["dados"] = $atualizar->obj->perfil_codigo;
				$array["loginU"] = $atualizar->obj->login;
				$array["nomeU"] = $atualizar->obj->nome;
				$pe = new Archivo();
				$pe->Content($atualizar->obj->perfil_codigo,"codigo","perfil_acesso");
				if($pe->retorno == 1)
				{
					$array["tituloP"] = $pe->obj->titulo;
				}
			}
		}
	}
	
	//echo json_encode($array);
}
?>