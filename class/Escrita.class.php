<?php
class Escrita
{
	function setObj($valor){$this->obj=$valor;}
	function setRetorno($valor){$this->retorno=$valor;}
	function setArrayy($valor){$this->arrayy=$valor;}
	
	function Gravar($perfil,$item_cod,$operacao)
	{
		$p = new Archivo();
		$p->Content($perfil,"codigo","perfil_acesso");
		if($p->retorno == 1)
		{
			if($p->obj->titulo === "Admin")
			{
				$this->retorno = 1;
			}else if($item_cod > 0 && $perfil > 0){
				$connnect = Conexao::getInstance();
				$query = $connnect->prepare("SELECT ei.codigo as exibe_codigo, e.codigo as escrita_codigo, p.codigo as perfil_codigo FROM escrita e JOIN exibe_item ei ON e.exibe_item_codigo = ei.codigo JOIN perfil_acesso p ON ei.perfil_codigo = p.codigo JOIN item iii ON ei.item_codigo = iii.codigo  WHERE p.codigo = ? AND iii.codigo = ? AND e.titulo = ?");
				$query->execute(array($perfil, $item_cod, $operacao));
			//$sql = "SELECT * FROM escrita e JOIN exibe_item ei ON e.exibe_item_codigo = ei.codigo WHERE ei.perfil_codigo = {$perfil} AND ei.item_codigo = {$item_cod} AND e.titulo = '{$operacao}'";
				$this->retorno = $query->rowCount();
				if($this->retorno == 1)
					$this->obj = $query->fetch(PDO::FETCH_OBJ);
			}else
				$this->retorno = 0;
		}
	}
}


?>