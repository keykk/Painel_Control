<?php
function liberar_acesso($item){
	$connnect = Conexao::getInstance();
	$permicao2 = false;
	if(!isset($usr))
		$usr = new Usuario();
	$usr->Ver();
	if($usr->perfil->titulo === "Admin")
	{
		$permicao2 = true;
	}else
	{
		$exib = $connnect->prepare("SELECT * FROM perfil_acesso p INNER JOIN exibe_item e ON p.codigo = e.perfil_codigo INNER JOIN item i ON e.item_codigo = i.codigo WHERE p.codigo = :user_p_id AND i.raiz = :item_raiz");
		
		$exib->execute([':user_p_id' => $usr->perfil->codigo, ':item_raiz' => $item]);
		
		if($exib->rowCount() == 1)
			$permicao2 = true;
	}
	
	return $permicao2;
	
}

function Paginacao($menu){
	if(!isset($usr))
		$usr = new Usuario();
	$usr->Ver();
	$pagina = "home";
$dir_pagina = "./php/paginas/{$pagina}";
$file_ext = "php";
	if(filter_has_var(INPUT_GET,'pagina'))
{
	$pagina = str_replace(" ","",filter_input(INPUT_GET,'pagina',FILTER_SANITIZE_STRING));
	$explode = explode(".",$pagina);
	$pagina = $explode[0];
	$dir_pagina = "./php/paginas/{$pagina}";
}
	if($menu === "Paginas"){
	
	

	if(filter_has_var(INPUT_GET,'sub'))
	{
		$sub = str_replace(" ","",filter_input(INPUT_GET,'sub',FILTER_SANITIZE_STRING));
		$sub_explode = explode(".",$sub);
		$sub_pagina = $sub_explode[0];
		$dir_pagina = "./php/paginas/{$pagina}/{$sub_pagina}";
	}
	
	

//inclui paginas
$files = ScanDir::scan($dir_pagina, $file_ext);
			$h = "n";
			if(count($files) > 0)
			{
				if(is_dir($dir_pagina)){
					
	if($usr->perfil->titulo === "Admin")
	{
?>
<script>
function showPromptMessage(item) {
    swal({
        title: "Titulo da tela",
        text: "Informe o titulo para este card.",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Titulo",
		showLoaderOnConfirm: true
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("Informe o titulo do card!"); return false
        }
       
		$.post("index.php",{inicio_arq:"regCard", arquivo_ajax:"regTela", r_raiz:item, r_titulo:inputValue},function(retorno){
			 //swal("Sucesso!", ".. " + retorno, "success");
			var obj = JSON.parse(retorno);
			//alert(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
		});
    });
}

function showPromptMessage2(cod) {
    swal({
        title: "Titulo da tela",
        text: "Informe o titulo para este card.",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Titulo",
		inputValue: $('#t'+cod).text(),
		showLoaderOnConfirm: true
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("Informe o titulo do card!"); return false
        }
       
		$.post("index.php",{inicio_arq:"update", arquivo_ajax:"CardItem", update_cod:cod, update_titulo:inputValue},function(retorno){
			 //swal("Sucesso!", ".. " + retorno, "success");
			var obj = JSON.parse(retorno);
			//alert(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			if(obj.Status === "success")
			{
				$('#t'+cod).text(inputValue);
			}
		});
    });
}

function showConfirmMessage(cod) {
    swal({
        title: "Deseja continuar ?",
        text: "Apagar o registro, o card ficara inacessível para criação de regras de acesso.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
		cancelButtomText: "Cancelar",
        confirmButtonText: "Excluir",
        closeOnConfirm: false,
		showLoaderOnConfirm: true
    }, function () {
		
		$.post("index.php",{inicio_arq:"delete", arquivo_ajax:"CardItem", delete_card_cod:cod},function(retorno){
		//alert(retorno);
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			if(obj.Status === "success")
			{
				$('#t'+cod).text("Item não registrado");
			}
		});
       // swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
}

function showConfirmMessage2(cod) {
    swal({
        title: "Remover as permissão de acesso ao card ?",
        text: "Somente membros do grupo Admin tera acesso.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
		cancelButtomText: "Cancelar",
        confirmButtonText: "Remover",
        closeOnConfirm: false
    }, function () {
		
		$.post("index.php",{inicio_arq:"Remover", arquivo_ajax:"AcessoItem", restringe_item_cod:cod},function(retorno){
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			//alert(retorno);
			
		});
       // swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
}
</script>					
					<?php
	}
	$i2 = 0;
			foreach($files as $pagin)
			{
				$item = basename($pagin);
				$dirname = dirname($pagin).'/'.$item;
				
				$acs = liberar_acesso($dirname);
				
				
				if(file_exists($pagin) && $acs === true)
				{
					$i2++;
					?>
					 <div class="card">
						<div class="header">
						
							<h2>
								<?php
								//Verificacao item registrado
								if(!isset($bItem))
									$bItem = new Archivo();
								$bItem->Content($dirname,"raiz","item");
								if($bItem->retorno == 1)
								{
									echo "<span id='t{$bItem->obj->codigo}'>".ucfirst($bItem->obj->titulo)."</span>";
									$idItem = $bItem->obj->codigo;
								}
								else
								{
									echo "Item não registrado";
									$idItem = 0;
								}
								//echo $bItem->obj->codigo;
								?>
								
							</h2>
							<?php
							if($usr->perfil->titulo === "Admin")
							{
							?>
							<ul class="header-dropdown m-r--5">
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons">more_vert</i>
									</a>
									
									<ul class="dropdown-menu pull-right">
									<?php
									if($bItem->retorno !== 1){
									?>
										<li><a onclick="showPromptMessage('<?php echo $dirname; ?>');" href="javascript:void(0);">Registrar</a></li>
										<!--<li><a href="javascript:void(0);">Excluir Registro</a></li>
										<li><a href="javascript:void(0);">Alterar Titulo</a></li>-->
										<?php
									}else
									{
										?>
										<li><a onclick="showPromptMessage2(<?php echo $bItem->obj->codigo; ?>);" href="javascript:void(0);">Alterar Titulo</a></li>
										<li><a onclick="showConfirmMessage(<?php echo $bItem->obj->codigo; ?>);" href="javascript:void(0);">Excluir Registro</a></li>
										<li><a onclick="showConfirmMessage2(<?php echo $bItem->obj->codigo; ?>);" href="javascript:void(0);">Remover Utilizadores</a></li>
										<?php
									}
									?>
									</ul>          
								</li>
							</ul>
							<?php
							}
							?>
						</div>
						<div class="body">
					<?php
					include_once($pagin);
					?>
					</div>
					</div>
					<?php
				
				}
			}
			if($i2 <= 0)
			{
				errorPagina();
			}
				}
			}
				//errorPagina();

	}else if($menu === "Menu")
	{
		//$files = ScanDir::scan($dir_pagina, $file_ext);
		$dir_pagina2 = "./php/paginas/*";
		$pages = array_filter(glob($dir_pagina2), 'is_dir');
		//print_r( $dirs);
		if($pagina == "home"){
			$cls2 = "active";
		}
		else
			$cls2 = "";
		?>
		<div class="menu">
			<ul class="list">
				 <li class="header">MAIN NAVIGATION</li>
				 <li class="active"></li>
				 <li class="<?php echo $cls2; ?>">
				 <?php
						$hme = "./php/paginas/home";
						$hme_sub = array_filter(glob($hme.'/*'), 'is_dir');
						$hme_count = count($hme_sub);
						$hme_array = array();
						$hme_php = ScanDir::scan($hme.'/', $file_ext);
						if($hme_count > 0)
						{
							$h_cls = "menu-toggle";
							$h_lnk = "javascript:void(0);";
						}else
						{
							$h_cls = "";
							$h_lnk = "index.php";
						}
						
						
				 ?>
				 
                        <a href="<?php echo $h_lnk; ?>" class="<?php echo $h_cls; ?>">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
						<?php
						if($hme_count > 0)
						{
							echo  "<ul class='ml-menu'>";
							
							
							
							if(count($hme_php) > 0)
							{
								$count_acess = 0;
								foreach($hme_php as $home_files)
								{
									$nomeRaiz = basename($home_files);
									$dirname = dirname($home_files).'/'.$nomeRaiz;
									//echo $dirname."<br/>";
									
									$hme_permitir = liberar_acesso($dirname);
									if($hme_permitir == true)
										$count_acess++;
								}
								if($count_acess > 0){
									echo "<li>";
									echo "<a href='index.php'>";
									echo "<span>→</span>";
									echo "</a>";
									echo "</li>";
								}
							}
							
							foreach($hme_sub as $home_sub)
							{
								$arqPHP = ScanDir::scan($home_sub, $file_ext);
								if(count($arqPHP) > 0)
								{
									$count_sub_php = 0;
									foreach($arqPHP as $sub_php)
									{
										$nomeRaiz = basename($sub_php);
										$dirname = dirname($sub_php).'/'.$nomeRaiz;
										
										$sub_php_acess = liberar_acesso($dirname);
										if($sub_php_acess == true)
											$count_sub_php++;
									}
									if($count_sub_php > 0)
									{
										$sscls = "";
										$home_n = basename($home_sub);
										
										if(filter_has_var(INPUT_GET,'sub'))
										{
											$sub7 = str_replace(" ","",filter_input(INPUT_GET,'sub',FILTER_SANITIZE_STRING));
											$sub_explode7 = explode(".",$sub7);
											$sub_pagina7 = $sub_explode7[0];
											if($sub_pagina7 == $home_n)
												$sscls = "active";
										}
										
										echo "<li class='{$sscls}'>";
										echo "<a href='index.php?pagina=home&sub={$home_n}'>";
									
										echo "<spam>".ucfirst(str_replace("_"," ",$home_n))."</spam>";
										echo "</a>";
										echo "</li>";
									}
								}
							}
							echo "</ul>";
						}
						
						?>
						
                 </li>
				 
				
				 <?php
				 foreach($pages as $item_menu)
				 {
					
					$item = basename($item_menu);
				
					if(file_exists($item_menu))
					{
						if($item == $pagina)
							$classe = "active";
						else
							$classe = "";
						if($item !== "home"){
						$sub_pages = array_filter(glob($item_menu.'/*'), 'is_dir');
						$count = count($sub_pages);
						
						$files3 = ScanDir::scan($item_menu, $file_ext);
			
						$e = array();
						$z =array($item_menu);
						foreach($sub_pages as $dirSub)
						{
							$e[] = $dirSub.'/';
							$z[] = $dirSub.'/';
						}
						$files4 = ScanDir::scan($z, $file_ext);
						$f = ScanDir::scan($e, $file_ext);
						//echo "<pre>".var_dump($files4)."</pre>";
						
						if(count($files4) > 0){
							
							$i = 0;
							foreach($files4 as $acesso_php)
							{
								$nomeRaiz = basename($acesso_php);
								$dirname = dirname($acesso_php).'/'.$nomeRaiz;
								
								$a = liberar_acesso($dirname);
								if($a == true)
									$i++;
							}
						if($i > 0){
						echo "<li class='$classe'>";
						if($count > 0 && count($f) > 0){
							$classeMan = "menu-toggle";
							$lnk = "javascript:void(0);";
								
						}
						else{
							$classeMan = "";
							$lnk = "index.php?pagina={$item}";
							
						}
						echo "<a href='$lnk' class='{$classeMan}'>";
						
						echo "<span>".ucfirst(str_replace("_"," ",$item))."</span>";
						echo "</a>";
						
						
						//echo var_dump($sub_pages);
						//echo $item_menu;
							if($count > 0 && count($f) > 0)
							{
							echo  "<ul class='ml-menu'>";
							
							if(count($files3) > 0)
							{
								$cnt = 0;
								foreach($files3 as $raizPHP)
								{
									$nomeRaiz = basename($raizPHP);
									$dirname = dirname($raizPHP).'/'.$nomeRaiz;
									
									$v = liberar_acesso($dirname);
									if($v == true)
										$cnt++;
								}
								if($cnt > 0)
								{
								echo "<li>";
								echo "<a href='index.php?pagina=$item'>";
								echo "<span>→</span>";
								echo "</a>";
								echo "</li>";
								}
							}
							foreach($sub_pages as $sub_item)
							{
								$arqPHP = ScanDir::scan($sub_item, $file_ext);
								if(count($arqPHP) > 0)
								{
									$c2 = 0;
									foreach($arqPHP as $arquivoPHP)
									{
										$subitem2 = basename($arquivoPHP);
										
										$b2 = liberar_acesso($sub_item.'/'.$subitem2);
										if($b2 == true)
											$c2++;
									}
									
									if($c2 > 0){
									
										$sscls = "";
										$sub_i = basename($sub_item);
										if(filter_has_var(INPUT_GET,'sub'))
										{
											$sub7 = str_replace(" ","",filter_input(INPUT_GET,'sub',FILTER_SANITIZE_STRING));
											$sub_explode7 = explode(".",$sub7);
											$sub_pagina7 = $sub_explode7[0];
											if($sub_pagina7 == $sub_i)
												$sscls = "active";
										}
										
										echo "<li class='{$sscls}'>";
										echo "<a href='index.php?pagina={$item}&sub={$sub_i}'>";
										
										echo "<spam>".ucfirst(str_replace("_"," ",$sub_i))."</spam>";
										echo "</a>";
										echo "</li>";
									}
								}
							}
							echo "</ul>";
							echo "</li>";
							}
						}
						}
						}
					}
				 }
				 ?>
				
			</ul>
		</div>
		<?php
		
	}
}



?>