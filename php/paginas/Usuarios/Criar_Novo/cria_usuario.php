<script>
$(document).ready(function(){
	$('#form_cadastro_usuario').submit(function(){
		var login,senha,nome,perfil;
		login = $('#login').val();
		senha = $('#senha').val();
		nome = $('#nome').val();
		perfil = $('#p').val();
		
		$.post("index.php",{ajax_1:"cria",ajax_2:"usuario",novo_user:login,novo_user_pw:senha,novo_user_nome:nome,novo_user_perfil:perfil,item_id:"<?php echo $idItem; ?>",OPp:"INSERT"},function(retorno){
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			//alert(retorno);
		});
	});
});
</script>
<form id="form_cadastro_usuario" method="post" action="javascript:void(0);">
<div class="row clearfix">
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-line">
                <input required id="login" type="text" class="form-control" placeholder="Login">
            </div>
        </div>
    </div>
	
	<div class="col-md-12">
        <div class="form-group">
            <div class="form-line">
                <input required id="senha" type="password" class="form-control" placeholder="Senha">
            </div>
        </div>
    </div>
	
	<div class="col-md-12">
        <div class="form-group">
            <div class="form-line">
                <input required id="nome" type="text" class="form-control" placeholder="Nome">
            </div>
        </div>
    </div>
	
	<div class="col-md-12">
		<p>
			<b>Selecione um perfil de acesso</b>
		</p>
		<?php
		$connnect = Conexao::getInstance();
		$busca_perfil2 = $connnect->prepare("SELECT * FROM perfil_acesso") or die($connnect->errorInfo()[2]);
		$busca_perfil2->execute();
		?>
		<select id="p" required class="form-control show-tick" data-live-search="false">
			<option></option>
			<?php
			
			if($busca_perfil2->rowCount() > 0)
			{
				while($objp = $busca_perfil2->fetch(PDO::FETCH_OBJ))
				{
					if($objp->titulo !== "Admin" || $usr->perfil->titulo === "Admin")
						echo "<option value='{$objp->codigo}'>".ucfirst($objp->titulo)."</option>";
				}
			}
			?>

			</select>
	</div>
	<div class="col-md-12">
		<button type="submit" class="btn btn-primary m-t-15 waves-effect">Salvar</button>
	</div>
</div>
</form>