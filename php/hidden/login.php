<?php
ob_start();
?>
<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Dashboard></a>
            <small>Acesso administrativo</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="">
                    <div class="msg">Entre para iniciar sua sessão</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <!--<div style="visibility:hidden;" class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>-->
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-dashb waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                   <!-- <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="sign-up.html">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="forgot-password.html">Forgot Password?</a>
                        </div>
                    </div>-->
                </form>
				
				<?php
				function MensagemDeErroLegal($msg){
					echo "<div class='alert alert-danger'>";
						echo $msg;
					echo "</div>";
				}
				
				if(filter_has_var(INPUT_POST,'username') && filter_has_var(INPUT_POST,'password'))
				{
					$login = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
					$senha = MD5(filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING));
					
					if(preg_match("/^\w.{3,100}$/", $login))
					{
						$_SESSION["dashboard_login"] = $login;
						$_SESSION["dashboard_senha"] = $senha;
						
						$user = new Usuario();
						
						$user->Ver();
						
						if($user->retorno == 1)
						{
							ob_end_clean();
							header("location: index.php");
						}else{
							MensagemDeErroLegal("Usuário ou senha incorreto!");
							
						}
						
					}else
					{
						MensagemDeErroLegal("<b>Login:&nbsp;</b>Caracteres 4 - 100");
					}
				}
				
				?>
            </div>
			
        </div>
		
    </div>

    <!-- Jquery Core Js -->
    <script src="../../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../../plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../../plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../../js/admin.js"></script>
    <script src="../../js/pages/examples/sign-in.js"></script>
</body>