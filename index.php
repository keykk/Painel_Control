<?php
date_default_timezone_set('America/Sao_Paulo');
include_once("./config/config.php");

if(filter_has_var(INPUT_GET,'sair'))
{
	if(isset($usr))
		unset($usr);
	if(isset($connnect))
		$connnect->commit();
	session_destroy();
	session_start();
	header("location: index.php");
}

if(!isset($usr))
	$usr = new Usuario();

$usr->Ver();
if(!isset($connnect))
	$connnect = Conexao::getInstance();
else $connnect->commit();

	if(isset($_SESSION['dashboard_login']) && isset($_SESSION['dashboard_senha']))
	{
		if($usr->retorno == 1 && $usr->perfil->titulo === "Admin")
		{
			if(filter_has_var(INPUT_POST,'inicio_arq') && filter_has_var(INPUT_POST,'arquivo_ajax'))
			{
				$rz = "./php/hidden/ajaxPHP/";
				$pagina = filter_input(INPUT_POST,'inicio_arq',FILTER_SANITIZE_STRING);
				$arquivo = filter_input(INPUT_POST,'arquivo_ajax',FILTER_SANITIZE_STRING);
				if(file_exists($rz.$pagina."_".$arquivo.".php")){
					include_once($rz.$pagina."_".$arquivo.".php");
					return false;
				}
			}
		}
		//Utilizado tanto por usuário quanto por Admin
		if($usr->retorno == 1)
		{	
			if(filter_has_var(INPUT_POST,'ajax_12') && filter_has_var(INPUT_POST,'ajax_22'))
			{
				$array2 = array();
				$array2["StatusTXT"] = "Erro!";
				$array2["Status"] = "error";
				$array2["MSG"] = "";
				
				$rz2 = "./php/hidden/ajaxPHP2/";
				$pagina2 = filter_input(INPUT_POST,'ajax_12',FILTER_SANITIZE_STRING);
				$arquivo2 = filter_input(INPUT_POST,'ajax_22',FILTER_SANITIZE_STRING);
				
				if(file_exists("./php/hidden/ajaxPHP2/update_myuser.php")){
					include_once("./php/hidden/ajaxPHP2/update_myuser.php");
					echo json_encode($array2);
					return false;
				}else{
					$array2["MSG"] = "Acesso Negado!";
					echo json_encode($array2);
					return false;
					
				}
				
			}
			
			if(filter_has_var(INPUT_POST,'ajax_1') && filter_has_var(INPUT_POST,'ajax_2') && filter_has_var(INPUT_POST,'item_id') && filter_has_var(INPUT_POST,'OPp'))
			{
				$array = array();
				$array["StatusTXT"] = "Erro!";
				$array["Status"] = "error";
				$array["MSG"] = "";
				
				$operacao = filter_input(INPUT_POST,'OPp',FILTER_SANITIZE_STRING);
				$item = (INT)filter_input(INPUT_POST,'item_id',FILTER_SANITIZE_NUMBER_INT);
				
				$a = array('INSERT','UPDATE','DELETE');
				
				IF(in_array($operacao,$a)){
					
				$permitir = new Escrita();
				$permitir->Gravar($usr->perfil->codigo,$item,$operacao);
				
				if($permitir->retorno == 1)
				{
				
				$rz = "./php/hidden/ajaxPHP2/";
				$pagina = filter_input(INPUT_POST,'ajax_1',FILTER_SANITIZE_STRING);
				$arquivo = filter_input(INPUT_POST,'ajax_2',FILTER_SANITIZE_STRING);
				
				if(file_exists($rz.$pagina."_".$arquivo.".php")){
					include_once($rz.$pagina."_".$arquivo.".php");
					echo json_encode($array);
					return false;
				}else{
					$array["MSG"] = "Acesso Negado!";
					echo json_encode($array);
					return false;
					
				}
				
				}else{
					$array["MSG"] = "Acesso Negado!";
					echo json_encode($array);
					return false;
				}
				}else{
					$array["MSG"] = "Acesso Negado!";
					echo json_encode($array);
					return false;
				}
				
			}
		}
	}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>DASHBOARD</title>
    <!-- Favicon-->
    <link rel="icon" href="dash.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
 

	
	  <!-- Colorpicker Css -->
    <link href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Multi Select Css -->
    <link href="plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="plugins/nouislider/nouislider.min.css" rel="stylesheet" />
	
    <!-- Morris Chart Css-->
    <link href="plugins/morrisjs/morris.css" rel="stylesheet" />
	
	 <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
	<style>
		.Focar_Selecionado
		{
			font-weight: bold;
			text-shadow: 1px 1px 0px #EEE;
			color: #111;   
		}
	</style>
</head>
<?php
if(!isset($_SESSION['dashboard_login']) && !isset($_SESSION['dashboard_senha']))
{
	include_once("./php/hidden/login.php");
}else{
	include_once("./php/config/pagination_config.php");
	include_once("./php/index.php");
}

?>


</html>