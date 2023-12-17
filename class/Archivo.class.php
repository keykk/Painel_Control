<?php
class Archivo {
	function setObj($valor){$this->obj=$valor;}
	function setRetorno($valor){$this->retorno=$valor;}
	function setArrayy($valor){$this->arrayy=$valor;}
	function setError($valor){$this->error=$valor;}
	
	function Content($id,$column,$tabela)
	{
		$connnect = Conexao::getInstance();
		$codigo = filter_var($id,FILTER_SANITIZE_STRING);
		$coluna = filter_var($column,FILTER_SANITIZE_STRING);
		$tabela = filter_var($tabela,FILTER_SANITIZE_STRING);
		$query = $connnect->prepare("SELECT * FROM {$tabela} WHERE {$coluna} = :codigo");
		$query->execute([':codigo' => $codigo]);
		$this->error = $query->errorInfo()[2];
		$this->arrayy = $query->fetchAll();
		$this->retorno = $query->rowCount();
		if($this->retorno == 1){
			$query->execute([':codigo' => $codigo]);
			$this->obj = $query->fetch(PDO::FETCH_OBJ);
		}
			
	}
}

/*
EXEMPLO

 $produtos = new Archivo();
 $produtos->Content(1,1,"produto");
 
 foreach($produtos->arrayy as $produto)
 {
	 echo $produto["codigo"] . " - " . $produto["nome"]."<br/>";
 }


*/
?>