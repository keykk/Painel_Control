
$(function() {
	var tax_data =
<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
function __autoload($nomeClasse){
    //Verifica se existe a classe no diretório classes
    if(file_exists("../class/".$nomeClasse.".class.php")){
        //Se existe carrega
        include_once("../class/".$nomeClasse.".class.php");
    }
}
	$usuario1 = new Usuario();
	 $usuario1->ver();
	  if($usuario1->retorno == 1){
		  if(filter_has_var(INPUT_GET,'periodo'))
		  {
		  	 $ano = filter_input(INPUT_GET,'periodo',FILTER_SANITIZE_NUMBER_INT);
			  if($ano > 0)
			  {
				  $connnect = Conexao::getInstance();
				  $select = $connnect->prepare("SELECT period,COALESCE(despesa, 0) AS despesa,COALESCE(liquido, 0) AS liquido FROM grafico WHERE ano = ?");
				  $select->execute(array($ano));
				  $result = $select->fetchAll(PDO::FETCH_CLASS);

					echo json_encode($result).";";
				
					
			  }
		  }
?>


<?php
	  }
 
 ?>
 Morris.Line({
    element: 'hero-graph',
    data: tax_data,
xkey: 'period',
xLabels: "month",
    ykeys: ['liquido', 'despesa'],
labels: ['Lucro', 'Gasto']
});
});