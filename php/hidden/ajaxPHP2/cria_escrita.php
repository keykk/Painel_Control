<?php
if(filter_has_var(INPUT_POST,'cria_escrita_val') && filter_has_var(INPUT_POST,'cria_escrita_exibe_item'))
{
    $val = filter_input(INPUT_POST,'cria_escrita_val',FILTER_SANITIZE_STRING);
    $regra_id = (INT)filter_input(INPUT_POST,'cria_escrita_exibe_item',FILTER_SANITIZE_NUMBER_INT);

    $permitidos = array("INSERT","UPDATE","DELETE");

    if($usr->perfil->titulo === "Admin")
    {
        $regra = new Archivo();
        $regra->Content($regra_id,"codigo","exibe_item");
        if($regra->retorno == 1)
        {
            $escrita = explode(",",$val);
            $query = $connnect->prepare("SELECT e.codigo as codigo FROM escrita e JOIN exibe_item ei ON e.exibe_item_codigo = ei.codigo WHERE e.titulo = ? AND ei.codigo = ?");

            foreach($permitidos as $es)
            {
                if(in_array($es,$escrita))
                {
                    $query->execute(array($es,$regra->obj->codigo));
                    if($query->rowCount() == 0)
                    {
                        $sql = $connnect->prepare("INSERT INTO escrita (titulo,exibe_item_codigo,item_codigo) VALUES (?,?,?)");
                        $sql->execute(array($es,$regra->obj->codigo,$regra->obj->item_codigo));

                        if($sql->rowCount() == 1)
                        {
                            $array["MSG"] = "Sucesso!!";
							$array["StatusTXT"] = "Sucesso!";
							$array["Status"] = "success";
                        }else
                            $array["MSG"] = SqlErro($sql->errorInfo()[2]);
                    }
                }else
                {
                    $query->execute(array($es,$regra->obj->codigo));

                    if($query->rowCount() == 1)
                    {
                        $obj = $query->fetch(PDO::FETCH_OBJ);
                        
                    $sql = $connnect->prepare("DELETE FROM escrita WHERE codigo = ?");
                    $sql->execute(array($obj->codigo));

                    if($sql->rowCount() == 1)
                    {
                       $array["MSG"] = "Sucesso!";
					   $array["StatusTXT"] = "Sucesso!";
						$array["Status"] = "success";
                    }else
                        $array["MSG"] = SqlErro($sql->errorInfo()[2]);

                    }
                }

            }
        }else
            $array["MSG"] = "regra não encontrada!";
    }else
       $array["MSG"] = "Acesso Negado!";
}


?>