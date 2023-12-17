<?php
if(filter_has_var(INPUT_POST,'update_user_nome') && filter_has_var(INPUT_POST,'update_user_newsenha') && filter_has_var(INPUT_POST,'update_user_senha'))
{
    if($usr->retorno == 1)
    {
        
        $nome = filter_input(INPUT_POST,'update_user_nome',FILTER_SANITIZE_STRING);
        $npass = filter_input(INPUT_POST,'update_user_newsenha',FILTER_SANITIZE_STRING);
        $pass = MD5(filter_input(INPUT_POST,'update_user_senha',FILTER_SANITIZE_STRING));
        if($pass === $usr->objeto->senha)
        {
        if(preg_match("/^\w.{3,99}$/", $nome))
		{
            try
            {
                if($npass !== "")
                {
                  $sql = $connnect->prepare("UPDATE user SET nome = ?, senha = ? WHERE codigo = ?");

                   $sql->execute(array($nome,MD5($npass),$usr->objeto->codigo));
                }else
                {
                    $sql = $connnect->prepare("UPDATE user SET nome = ? WHERE codigo = ?");

                   $sql->execute(array($nome,$usr->objeto->codigo));
                }
                if($sql->rowCount() == 1)
                {
                    $array2["StatusTXT"] = "Sucesso!";
                    $array2["Status"] = "success";
                    $array2["MSG"] = "Dados atualizados!";
                    if($npass !== "")
                        $_SESSION["dashboard_senha"] = MD5($npass);
                }
                else
                {
                    $array2["MSG"] = SqlErro($sql->errorInfo()[2]);
                }


            }catch(PDOException $e)
            {
                $array2["MSG"] = $e->getMessage();

            }
        }else
        $array2["MSG"] = "Caracteres para nome incorreto, 4-99!";
    }else
    {
        $array2["MSG"] = "Senha Incorreta!";
       session_destroy();
    }
    }
    //echo json_encode($array);
}


?>