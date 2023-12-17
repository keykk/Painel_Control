 <script>
 /*$(document).ready(function(){
	 $('table td').on('change', function(evt, newValue) {
	// do something with the new cell value 
	var out = "";
	 for (var i in evt) {
        out += i + ": " + evt[i] + "\n";
    }

    alert(out);
});
	 
 });*/
function Update(cod){
	var titulo = $('#titulo'+cod).text();
	var desc = $('#desc'+cod).text();
	var color = "";
	$.post("index.php",{ajax_1:"altera",ajax_2:"perfil",altera_perfil_titulo:titulo,altera_perfil_id:cod,altera_perfil_desc:desc,altera_item:"<?php echo $idItem; ?>",OPp:"UPDATE",item_id:"<?php echo $idItem; ?>"},function(retorno){
		//alert(retorno);
		var obj = JSON.parse(retorno);
		if(obj.Status === "error")
			color = "bg-red";
		else if(obj.Status === "success")
			color = "bg-black";
		$('#titulo'+cod).text(obj.titulo);
		$('#desc'+cod).text(obj.desc);
		showNotification(color, obj.MSG, "top", "right", "", "");
		
	});
	//showNotification("bg-black", "Testando", "top", "right", "", "");
	//alert($('#titulo'+cod).text());
}
function Show(id)
{
	$('#ac'+id).show();
}
function Hide()
{
	$('.ac').hide();
}

function Delete_Perfil(id)
{
	swal({
        title: "Deseja continuar ?",
        text: "",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Excluir",
		cancelButtonText: "Cancelar",
        closeOnConfirm: false,
		showLoaderOnConfirm: true
    }, function () {
		
		$.post("index.php",{ajax_1:"Excluir",ajax_2:"perfil",excluir_perfil_id:id,item_id:"<?php echo $idItem; ?>",OPp:"DELETE"},function(retorno){
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
		});
		
			
		
		
    });
}
 </script>
<div id="mainTable" class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Descrição</th>
                <th>Regras exibição</th>
               
                <th>Data</th>
                <th>Usuários</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Titulo</th>
                <th>Descrição</th>
                <th>Regras exibição</th>
               
                <th>Data</th>
                <th>Usuários</th>
            </tr>
        </tfoot>
        <tbody>
            
                <?php
				$connnect = Conexao::getInstance();
				$sql = $connnect->prepare("SELECT * FROM perfil_acesso");
				$sql->execute();
				if($sql->rowCount() > 0)
				{
					while($objj = $sql->fetch(PDO::FETCH_OBJ))
					{
						$data = new Archivo();
						echo "<tr onmouseout='Hide();' onmouseover='Show({$objj->codigo});'>";
						echo "<td onchange='Update({$objj->codigo});' id='titulo{$objj->codigo}'>".ucfirst($objj->titulo)."</td>";
						echo "<td onchange='Update({$objj->codigo});' id='desc{$objj->codigo}'>".ucfirst($objj->descricao)."</td>";
						$data->Content($objj->codigo,"perfil_codigo","exibe_item");
						echo "<th>{$data->retorno}</th>";
						echo "<th>{$objj->data_cadastro}</th>";
						$data->Content($objj->codigo,"perfil_codigo","user");
						echo "<th>{$data->retorno}<span class='ac' id='ac{$objj->codigo}' style='float:right;display:none;'><a href='javascript:void(0);' onclick='Delete_Perfil({$objj->codigo});'>Excluir</a></span></th>";
						echo "</tr>";
					}
				}
				?>                
            
                                       
        </tbody>
    </table>
</div>
