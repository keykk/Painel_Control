<script>
$(document).ready(function(){
	$('#f').submit(function(){
		var titulo = $('#txttitulo').val();
		var desc = $('#txtdesc').val();
		$.post("index.php",{ajax_1:"cria",ajax_2:"perfil",perfil_titulo:titulo,perfil_desc:desc,item_id:"<?php echo $idItem; ?>",OPp:"INSERT"},function(retorno){
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			//alert(retorno);
		});
		//swal('Sucesso!', 'O perfil foi criado!', 'success');
	});
});

</script>

<form method="post" id="f" action="javascript:;">
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="form-group">
            <div class="form-line">
                <input maxlength="99" id="txttitulo" type="text" name="titulo" class="form-control" placeholder="Titulo do perfil de acesso" required />
            </div>
        </div>

    </div>
								
	<div class="col-sm-12">
        <div class="form-group">
            <div class="form-line">
                <textarea maxlength="199" rows="2" id="txtdesc" name="desc" class="form-control no-resize" placeholder="Descrição do perfil de acesso"></textarea>
            </div>
        </div>
    </div>
	<div class="col-sm-12">
		<button type="submit" class="btn btn-primary m-t-15 waves-effect">Salvar</button>
	</div>
</div>
</form>
						