 <script>
	$(function () {
    //Date Time
  $('.datetime').inputmask('d/m/y h:s', { placeholder: '__/__/____ __:__', alias: "datetime", hourFormat: '24' });

	});
	
	function showConfirmMessage258() {
    swal({
        title: "Deseja continuar ?",
        text: "",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#006FBA",
        confirmButtonText: "Salvar",
		cancelButtonText: "Cancelar",
        closeOnConfirm: false,
		showLoaderOnConfirm: true
    }, function () {
		var txtarea = tinyMCE.get('textarea').getContent();
		$.post("index.php?pagina=home&sub=indicadores",{novoCard_titulo:$('#ctitulo').val(),novoCard_data_inicio:$('#datainicio').val(),novoCard_data_fim:$('#datafim').val(),novoCard_body:txtarea,arquivo_ajax:"indicadores_novoCard"},function(retorno){
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
		});
		
    });
	}
	
	</script>
			
			
			
				<div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input id="ctitulo" type="text" class="form-control" placeholder="Titulo do card (opcional)">
                            </div>
                        </div>
                    </div>
                </div>
				
				<div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                <input id="datainicio" type="text" class="form-control datetime" placeholder="Data Inicio">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                <input id="datafim" type="text" class="form-control datetime" placeholder="Data Final">
                            </div>
                        </div>
                    </div>
                </div>
				
				<textarea id="textarea"></textarea>
				<button onclick="showConfirmMessage();" type="button" class="btn btn-primary m-t-15 waves-effect">Salvar</button>
		<script src="js/tinymce.js"></script>