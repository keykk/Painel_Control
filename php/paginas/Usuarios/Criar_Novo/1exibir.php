<script>
$(function () {
    $('#SraTabela').editableTableWidget();
	
	$('.dataTable').DataTable({
        responsive: true
    });
});


function Excluir_Usuario(id)
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
		showLoaderOnConfirm: false
    }, function () {
		
		$.post("index.php",{ajax_1:"Excluir",ajax_2:"usuario",excluir_usuario_id:id,item_id:"<?php echo $idItem; ?>",OPp:"DELETE"},function(retorno){
			var obj = JSON.parse(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			//alert(retorno);
		});
		
    });
}


function ChangePassword(id)
{
	 swal({
        title: "Alterar Senha do usuário",
        text: "Informe a senha",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
		inputType: "password",
        inputPlaceholder: "Nova Senha",
		showLoaderOnConfirm: true
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("Informe a nova Senha"); return false
        }
       
		$.post("index.php",{ajax_1:"altera", ajax_2:"usuario", usuario_update_password:inputValue, usuario_password_id:id},function(retorno){
			 //swal("Sucesso!", ".. " + retorno, "success");
			var obj = JSON.parse(retorno);
			//alert(retorno);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
		});
    });
}


function SetPerfil(cod)
{
	Hide();
	var perfi = $('#p'+cod).val();
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
		$.post("index.php",{ajax_1:"altera",ajax_2:"usuario",update_user_id:cod,update_user_p:perfi,item_id:"<?php echo $idItem; ?>",OPp:"UPDATE"},function(retorno){
			//alert(retorno);
			var obj = JSON.parse(retorno);
			$("#p"+cod).val(obj.dados).change();
			
			$('#s'+cod).text(obj.tituloP);
			swal(obj.StatusTXT, obj.MSG, obj.Status);
			
		});
		
    });
}

function AtualizaInfo(id)
{
	var login,nome = "";
	login = $('#login'+id).text();
	nome = $('#nome'+id).text();
	
	
	$.post("index.php",{ajax_1:"altera",ajax_2:"usuario",update_user_login:login,update_user_nome:nome,update_user_id:id,item_id:"<?php echo $idItem; ?>",OPp:"UPDATE"},function(retorno){
		var obj = JSON.parse(retorno);
		
		if(obj.Status === "success")
			color = "bg-black";
		else if(obj.Status === "error")
			color = "bg-red";
		showNotification(color, obj.MSG, "top", "right", "", "");
		$('#login'+id).text(obj.loginU);
		$('#nome'+id).text(obj.nomeU);
	});
}

function ChangeP(id)
{
	$('#s'+id).hide();
	$('.d'+id).show();
}
function Hide(){
	$('.d').hide();
	$('.s').show();
}
function Hh(id)
{
	$('.qwer'+id).show();
}
function HideH()
{
	$('.qwer').hide();
}
</script>
<div id="SraTabela" class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable">
        <thead>
            <tr>
                <th>Login</th>
                <th>Nome</th>
                <th>Perfil de acesso</th>
                <th>Data cadastro</th>
                
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Login</th>
                <th>Nome</th>
                <th>Perfil de acesso</th>
                <th>Data cadastro</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
			$connnect = Conexao::getInstance();
            $us = $connnect->prepare("SELECT * FROM user") or die (SqlErro($connnect->errorInfo()[2]));
			$us->execute();
			
			if($us->rowCount() > 0)
			{
				while($SrObj = $us->fetch(PDO::FETCH_OBJ))
				{
					if($SrObj->codigo != $usr->objeto->codigo){
					echo "<tr onmouseout='HideH();' onmouseover='Hh({$SrObj->codigo});'>";
					echo "<td onchange='AtualizaInfo({$SrObj->codigo});' id='login{$SrObj->codigo}'>{$SrObj->login}</td>";
					echo "<td onchange='AtualizaInfo({$SrObj->codigo});' id='nome{$SrObj->codigo}'>".ucfirst($SrObj->nome)."</td>";
					$b_p = new Archivo();
					$b_p->Content($SrObj->perfil_codigo,"codigo","perfil_acesso");
					echo "<th ondblclick='ChangeP({$SrObj->codigo});'><span class='s' id='s{$SrObj->codigo}'>".ucfirst($b_p->obj->titulo)."</span><div class='d d{$SrObj->codigo}' style='display:none;'><select id='p{$SrObj->codigo}' onchange='SetPerfil({$SrObj->codigo});' class='form-control show-tick' data-live-search='true'>";
					$perfils = $connnect->query("SELECT * FROM perfil_acesso") or die (SqlErro($connnect->errorInfo()[2]));
					if($perfils->rowCount() > 0)
					{
						while($pfsobj = $perfils->fetch(PDO::FETCH_OBJ))
						{
							if($SrObj->perfil_codigo == $pfsobj->codigo)
								echo "<option value='{$pfsobj->codigo}' selected='selected'>".ucfirst($pfsobj->titulo)."</option>";
							else
								echo "<option value='{$pfsobj->codigo}'>".ucfirst($pfsobj->titulo)."</option>";
						}
					}
					echo "</select></div></th>";
					echo "<th>{$SrObj->data_cadastro}<span class='qwer qwer{$SrObj->codigo}' style='float:right;display:none;'><a onclick='ChangePassword({$SrObj->codigo});' href='javascript:void(0);'>Password</a> - <a onclick='Excluir_Usuario({$SrObj->codigo});' style='color:red;' href='javascript:void(0);'>Excluir</a></span></th>";
					echo "</tr>";
					}
				}
			}
			
			?>			
        </tbody>
    </table>
	<?php
	
	?>
</div>
<br /><br />