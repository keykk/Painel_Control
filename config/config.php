<?php
session_start();
function my_autoload($nomeClasse){
    //Verifica se existe a classe no diretório classes
    if(file_exists("class/".$nomeClasse.".class.php")){
        //Se existe carrega
        include_once("class/".$nomeClasse.".class.php");
    }
}

spl_autoload_register("my_autoload");

function SqlErro($valor){
	$base = explode(" ",$valor);
	$array = array("a foreign key constraint fails "=>"restrição de chave estrangeira ","Cannot delete or update a parent row: "=>"Não é possível excluir ou atualizar uma linha pai: ","check the manual that corresponds to your MariaDB server version for the right syntax to use near"=>"verifique o manual que corresponde à sua versão do servidor MariaDB para a sintaxe correta a ser usada","You have an error in your SQL syntax"=>"Você tem um erro na sua sintaxe SQL","doesn't exist"=>"não existe","field list"=>"Lista de campos","Unknown column"=>"Coluna desconhecida","field list"=>"lista de campos","in "=>"na ","for "=>"para ","key "=>"chave ","column "=>"coluna ","Unknown "=>"Desconhecido ","entry "=>"entrada ","Duplicate "=>"Duplicado ","Table "=>"Tabela ","line "=>"linha ","at "=>"na ");

	/*foreach($array as $retorna => $value){
		$valor = str_replace($retorna,$value,$valor);
	}*/
	$valor = strtr($valor, $array);
	$pattern = array("/\(.*\)/");
	//$replacement = '${1}1,$3';
	$valor = preg_replace($pattern, "", $valor);
	return $valor.".";
}



function Brdata($data){
	$newDate = date("d-m-Y H:i:s", strtotime($data));
	return $newDate;
}

function Converter_real($valor){
	return number_format($valor,2,',','.');
}
?>