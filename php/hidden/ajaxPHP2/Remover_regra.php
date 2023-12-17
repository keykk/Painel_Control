<?php
if(filter_has_var(INPUT_POST,'remove_regras_itens') && filter_has_var(INPUT_POST,'remove_regras_perfil'))
{
    
    $regras = filter_input(INPUT_POST,'remove_regras_itens',FILTER_SANITIZE_STRING);
    $perfil = (INT)filter_input(INPUT_POST,'remove_regras_perfil',FILTER_SANITIZE_NUMBER_INT);

    if($usr->perfil->titulo === "Admin")
    {
        $regra = explode(",",$regras);
        if($perfil > 0)
        {
            $perfil_obj = new Archivo();
            $perfil_obj->Content($perfil,"codigo","perfil_acesso");

            if($perfil_obj->retorno == 1)
            {
                $regra_obj = new Archivo();

                foreach($regra as $it)
                {
                    $it = (INT)$it;
                    if($it > 0)
                    {
                        $regra_obj->Content($it,"codigo","item");
                        if($regra_obj->retorno == 1)
                        {
                           $v = $connnect->prepare("SELECT * FROM exibe_item e JOIN perfil_acesso p ON e.perfil_codigo = p.codigo WHERE e.item_codigo = ? AND p.codigo = ?");
                           $v->execute(array($regra_obj->obj->codigo, $perfil_obj->obj->codigo));

                           if($v->rowCount() == 1)
                           {
                               try
                               {
                                   $sql = $connnect->prepare("DELETE FROM exibe_item WHERE item_codigo = ? AND perfil_codigo = ?");
                                   $sql->execute(array($regra_obj->obj->codigo, $perfil_obj->obj->codigo));

                                   if($sql->rowCount() == 1)
                                   {
                                    $array["StatusTXT"] = "Sucesso!";
                                    $array["Status"] = "success";
                                    
                                    $array["MSG"] = "Regra removida!";

                                   }else
                                   {
                                        $array["MSG"] = SqlErro($sql->errorInfo()[2]);
                                        $array["errorItens"] .= ",{$it}";

                                   }

                               }catch(PDOException $e){}
                           }else
                           {
                                //$array["MSG"] = "Item não encontrado, na lista de regras!";
                               // $array["errorItens"] .= ",{$it}";
                           }

                        }else
                        {
                            $array["MSG"] = "Item não existe!";
							//$array["errorItens"] .= ",{$it}";
                        }
                      
                    }else
                    {
                        $array["MSG"] = "Item inválido!";
					//	$array["errorItens"] .= ",{$it}";
                    }

                }

            }else
            {
                //$array["errorItens"] = ",".$itens;
				$array["MSG"] = "Perfil não existe!";
            }

        }else
        {
            //$array["errorItens"] = ",".$itens;
			$array["MSG"] = "Perfil não existe!";
        }
    }else
    {
        $array["MSG"] = "Acesso Negado!";
		//$array["errorItens"] = ",".$regras;
    }

   // echo json_encode($array);
}


?>