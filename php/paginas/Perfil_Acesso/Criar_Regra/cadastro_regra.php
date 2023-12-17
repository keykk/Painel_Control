<?php
$connnect = Conexao::getInstance();
?>
<script>
$(function () {
	$('.dataTable').DataTable({
        responsive: true
    });
	
	
});

</script>
<?php
if(filter_has_var(INPUT_GET,'perfil_regra'))
{
	$pre = "Conteúdo não encontrado!";
	$perfil_id = (INT)filter_input(INPUT_GET,'perfil_regra',FILTER_SANITIZE_NUMBER_INT);
	if($perfil_id > 0)
	{
		
		$profile = new Archivo();
		$profile->Content($perfil_id,"codigo","perfil_acesso");
		if($profile->retorno == 1)
		{
			$pre = ucfirst($profile->obj->titulo);
			
			//echo basename(dirname("./php/paginas/home/indicadores/listagemCard.php"));
			?>
			<script>
			$(function (){
				$('#optgroup').multiSelect({
					selectableOptgroup: true,
					
					selectableHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Procurar por itens'>",
					selectionHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Procurar por itens'>",
					afterInit: function(ms){
						var that = this,
							$selectableSearch = that.$selectableUl.prev(),
							$selectionSearch = that.$selectionUl.prev(),
							selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
							selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

						that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
						.on('keydown', function(e){
						if (e.which === 40){
							that.$selectableUl.focus();
							return false;
						}
						});

						that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
						.on('keydown', function(e){
						if (e.which == 40){
							that.$selectionUl.focus();
							return false;
						}
						});
					},

					afterSelect: function(values){
						//alert("Select value: "+values);
						
						
						$.post("index.php",{ajax_1:"criar",ajax_2:"regra",cria_regras_itens:""+values,cria_regras_perfil:<?php echo $profile->obj->codigo; ?>,item_id:"<?php echo $idItem; ?>",OPp:"INSERT"},function(retorno){
							var obj = JSON.parse(retorno);
							var res = obj.errorItens.split(",");
							var color = "";
							if(obj.Status === "error")
								color = "bg-red";
							else
								color = "bg-black";

							if(obj.MSG !== "")
							showNotification(color, obj.MSG, "top", "right", "", "");
							
							for(var i=1; i<res.length; i++) {
								$('#optgroup').multiSelect('deselect',res[i]);
							}
						});
						this.qs1.cache();
   						 this.qs2.cache();
					},
					afterDeselect: function(values){
						
						$.post("index.php",{ajax_1:"Remover",ajax_2:"regra",remove_regras_itens:""+values,remove_regras_perfil:<?php echo $profile->obj->codigo; ?>,item_id:"<?php echo $idItem; ?>",OPp:"DELETE"},function(retorno){
							//alert(retorno);
							var obj = JSON.parse(retorno);
							var res = obj.errorItens.split(",");
							var color = "";
							if(obj.Status === "error")
								color = "bg-red";
							else
								color = "bg-black";
							
							if(obj.MSG !== "")
								showNotification(color, obj.MSG, "top", "right", "", "");
							

							for(var i=1; i<res.length; i++) {
								$('#optgroup').multiSelect('select',res[i]);
								//$('#optgroup').multiSelect('refresh');
							}

						});
						this.qs1.cache();
    					this.qs2.cache();
					}
				
				});

			});
			
			
			</script>
			
			<pre><a href="index.php?pagina=<?php echo $pagina."&sub=".$sub_pagina; ?>">Inicio</a> → <?php echo $pre; ?></pre>
			
			<div id="SraTabela" class="table-responsive">
				<table class="table table-bordered table-striped table-hover dataTable">
					<thead>
						<tr>
							<th>Login</th>
							<th>Nome</th>
							<th>Data cadastro</th>
							
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Login</th>
							<th>Nome</th>
							<th>Data cadastro</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
						$busca_usuarios = new Archivo();
						$busca_usuarios->Content($profile->obj->codigo,"perfil_codigo","user");
						if($busca_usuarios->retorno > 0)
						{
							foreach($busca_usuarios->arrayy as $users)
							{
								echo "<tr>";
								echo "<td>{$users["login"]}</td>";
								echo "<td>".ucfirst($users["nome"])."</td>";
								echo "<td>".ucfirst($users["data_cadastro"])."</td>";
								echo "</tr>";
							}
						}
						?>
					</tbody>
				</table>
				
			</div>
			
			<div class="header"><h2>Regras de exibição</h2></div>
			
			
			 <!-- Multi Select -->
            <div class="row clearfix">
				
				<div class="body">
				<?php
					if($usr->perfil->titulo !== "Admin")
						$h = "disabled";
					else
						$h = "";
				?>
                            <select <?php echo $h; ?> id="optgroup" class="ms" multiple="multiple">
							<?php
							$array_p = array();
							$busca = $connnect->prepare("SELECT * FROM item order by codigo desc");
							$busca->execute();
							if($busca->rowCount() > 0)
							{
								while($objj = $busca->fetch(PDO::FETCH_OBJ))
								{
									$dirnome = dirname($objj->raiz);
									//$array_p[$dirnome] = array();
									$array_p[$dirnome][$objj->codigo] = $objj->titulo;
									//$array_p[$dirnome][$objj->codigo + 2] = "teste";
								
								}
								if($usr->perfil->titulo !== "Admin")
									$statuss = "disabled";
								else
									$statuss = "";
								foreach($array_p as $key => $value)
								{
									echo "<optgroup label='".ucfirst($key)."'>";
									
									foreach($array_p[$key] as $key2 => $value2)
									{
										$buscar_exibe = $connnect->prepare("SELECT * FROM exibe_item WHERE perfil_codigo = ? AND item_codigo = ?");
										
										$buscar_exibe->execute(array($profile->obj->codigo, $key2));
										
										if($buscar_exibe->rowCount() == 1)
										{
											//$busca_obj_2 = $buscar_exibe->fetch(PDO::FETCH_OBJ);
											echo  "<option id='i{$key2}' selected='selected' value='{$key2}' {$statuss}>".ucfirst($value2)."</option>";
										}
										else
											echo  "<option value='{$key2}' {$statuss}>".ucfirst($value2)."</option>";
									}
									echo "</optgroup>";
								}
							}
							?>
                               <!-- 
								<optgroup label="Alaskan/Hawaiian Time Zone">
                                    <option value="AK">Alaska</option>
                                    <option value="HI">Hawaii</option>
                                </optgroup>
                                <optgroup label="Pacific Time Zone">
                                    <option value="CA">California</option>
                                    <option value="NV">Nevada</option>
                                    <option value="OR">Oregon</option>
                                    <option value="WA">Washington</option>
                                </optgroup>
                                <optgroup label="Mountain Time Zone">
                                    <option value="AZ">Arizona</option>
                                    <option value="CO">Colorado</option>
                                    <option value="ID">Idaho</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="UT">Utah</option>
                                    <option value="WY">Wyoming</option>
                                </optgroup>
                                <optgroup label="Central Time Zone">
                                    <option value="AL">Alabama</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TX">Texas</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="WI">Wisconsin</option>
                                </optgroup>
                                <optgroup label="Eastern Time Zone">
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="IN">Indiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="OH">Ohio</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WV">West Virginia</option>
                                </optgroup>
								
								-->
                            </select>
				</div>
                       
            </div>
               
            <!-- #END# Multi Select -->
<script>
function changeFunc(id)
{
	var values = $('#sl'+id).val();
	$.post("index.php",{ajax_1:"cria",ajax_2:"escrita",cria_escrita_val:''+values,cria_escrita_exibe_item:id,item_id:"<?php echo $idItem; ?>",OPp:"INSERT"},function(retorno){
		if(retorno !== ""){
			var obj = JSON.parse(retorno);
			showNotification("bg-black", obj.MSG, "top", "right", "", "");
		}
	});
}
</script>
			<div class='header'><h2>Escrita</h2></div>

				<div id="SraTabela2" class="table-responsive">
				<table class="table table-bordered table-striped table-hover dataTable">
					<thead>
						<tr>
							<th>Titulo</th>
							<th>Raiz</th>
							<th>Escrita</th>
							
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Titulo</th>
							<th>Raiz</th>
							<th>Escrita</th>
						</tr>
					</tfoot>
					<tbody>
					<?php
						$ite = new Archivo();
						$ite->Content($profile->obj->codigo,"perfil_codigo","exibe_item");
						if($ite->retorno > 0)
						{
							foreach($ite->arrayy as $itens_permitidos)
							{
								$item = new Archivo();
								$item->Content($itens_permitidos["item_codigo"],"codigo","item");
								if($item->retorno == 1)
								{
								echo "<tr>";
								echo "<td>".ucfirst($item->obj->titulo)."</td>";
								echo "<td>{$item->obj->raiz}</td>";
								echo "<td>";
								
								if($usr->perfil->titulo !== "Admin")
									$enab = "disabled";
								else
									$enab = "";
				
									echo "<select {$enab} id='sl{$itens_permitidos["codigo"]}' onchange='changeFunc({$itens_permitidos["codigo"]});' class='form-control show-tick' multiple data-live-search='false'>";
									$options = array("INSERT","UPDATE","DELETE");

										foreach($options as $o)
										{
											$sql = $connnect->prepare("SELECT ei.codigo as codigo FROM escrita e JOIN exibe_item ei ON e.exibe_item_codigo = ei.codigo JOIN item i ON ei.item_codigo = i.codigo JOIN perfil_acesso p ON ei.perfil_codigo = p.codigo WHERE e.titulo = ? AND ei.codigo = ? AND p.codigo = ? AND i.codigo = ?");

											$sql->execute(array($o,$itens_permitidos["codigo"],$profile->obj->codigo,$item->obj->codigo));
											if($sql->rowCount() == 1)
											{
												$obj = $sql->fetch(PDO::FETCH_OBJ);
												echo "<option selected>{$o}</option>";
											}
											else
												echo "<option>{$o}</option>";
										}
									echo "</select>";
								echo "</td>";
								echo "</tr>";
								}
							}
						}
					?>

					</tbody>

				</table>
			</div>

			<?php
			
			
			//echo "<pre>".var_dump(array_keys($array_p))."</pre>";
			
			
		}
	}
	if($pre === "Conteúdo não encontrado!")
	{
	?>
	<pre><a href="index.php?pagina=<?php echo $pagina."&sub=".$sub_pagina; ?>">Inicio</a> → <?php echo $pre; ?></pre>
	<?php
	}
}else{
?>
<pre>Inicio → </pre>
<div id="SraTabela" class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable">
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Regras</th>
                <th>Usuários</th>
                <th>Data cadastro</th>
                
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Titulo</th>
                <th>Regras</th>
                <th>Usuários</th>
                <th>Data cadastro</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
		
			$query = $connnect->prepare("SELECT * FROM perfil_acesso");
			$query->execute();
			if($query->rowCount() > 0)
			{
				while($objj = $query->fetch(PDO::FETCH_OBJ))
				{
					echo "<tr>";
					
					echo "<td><a href='index.php?pagina={$pagina}&sub={$sub_pagina}&perfil_regra={$objj->codigo}'>".ucfirst($objj->titulo)."</a></td>";
					$regras = new Archivo();
					$regras->Content($objj->codigo,"perfil_codigo","exibe_item");
					echo "<td>{$regras->retorno}</td>";
					$usuarios = new Archivo();
					$usuarios->Content($objj->codigo,"perfil_codigo","user");
					echo "<td>{$usuarios->retorno}</td>";
					echo "<td>{$objj->data_cadastro}</td>";
					
					echo "</tr>";
				}
			}
			?>
        </tbody>
    </table>
	
</div>
<?php
}