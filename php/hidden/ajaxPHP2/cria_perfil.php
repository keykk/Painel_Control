<?php
if(filter_has_var(INPUT_POST,'perfil_titulo') && filter_has_var(INPUT_POST,'perfil_desc') && $operacao == "INSERT")
{
		$titulo = filter_input(INPUT_POST,'perfil_titulo',FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST,'perfil_desc',FILTER_SANITIZE_STRING);
		if(preg_match("/^\w.{3,99}$/", $titulo))
		{
			$per = new Archivo();
			$per->Content($titulo,"titulo","perfil_acesso");
			if($per->retorno == 0)
			{
				$sql = $connnect->prepare("INSERT INTO perfil_acesso (titulo,descricao) VALUES (?,?)");
				try
				{
					$connnect->beginTransaction();
					$sql->execute(array($titulo,$desc));
					
					if($sql->rowCount() == 1)
					{
						$array["StatusTXT"] = "Sucesso!";
						$array["Status"] = "success";
						$array["MSG"] = "Novo Perfil de acesso criado!";
					}
					else
						$array["MSG"] = SqlErro($sql->errorInfo()[2]);
					
					$connnect->commit();
				}catch(Exception $e){
					$connnect->rollback();
					$array["MSG"] = $e->getMessage();
				}
			}else
				$array["MSG"] = "Titulo já esta em uso!";
		}else
			$array["MSG"] = "Titulo deve contar de 4 a 99 caracteres!";
		
//echo json_encode($array);
}


?>