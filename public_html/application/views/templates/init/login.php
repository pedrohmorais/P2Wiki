
<body class="full-width page-condensed">
<style type="text/css">
body{
	background:#ffffff url('<?=site_url()?>application/third_party/images/interface/background_login_gf.jpg') no-repeat center center;
}
.select2-container{
	width:100% !important;
}
.navbar-inverse {
	background-color: #12A1CC;
	border-bottom: 1px solid #ffffff;
}
.popup-header {
	background: #12A1CC;
}

.btn-warning:focus, .btn-warning:hover {
	background-color: #0162A2;
	border-color: #666666;
}
.btn-warning{
	color: #fff;
	background-color: #ed9c28;
	border-color: #d58512;
}
.navbar-brand{
	margin: 5px 0 0 5px;
	font-size:14px;
}
.navbar-img{
	/*position:absolute;*/
	display:table;
	margin:-30px auto 25px auto;
	z-index:999999;
}	
.select-search{
	width:100% !important;
 }
 h5{
	text-align:left;
	color:#666666;
 }
 .modal-dialog{
	width:400px;
 }
 .login-wrapper{
	top:45%;
	z-index:9;
 }
 .alert-danger{
	margin-bottom:20px !important;
 }

</style>
<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
		<a class="navbar-brand" href="<?=site_url()?>">Sistema de Gestão Vale do Silício</a>
	</div>
</div>
<!-- /navbar -->
<!-- Login wrapper -->
<div class="login-wrapper ">
<? 
	if(!empty($cookie_pw))
	{
		$margin_top_alert = '-15%';
		$margin_bottom_alert = '15%';
		$margin_top = '-10%';
	}else{
		$margin_top_alert = '-20px';
		$margin_bottom_alert = '5px !important';
		$margin_top = '-20%';
	}
?>
  <form method="POST" action="<?=site_url()?>" role="form">
		<?php
		if(!empty($cookie_pw))
		{
			//mostra login com foto
			$user = $this->Login->getLoginUser($cookie_ut);
			$ocupacao = $nivel_acesso = null;
			switch($user->nivel_acesso)
			{
				case 1:$nivel_acesso = 1;$ocupacao = 'Franqueado';break;
				case 2:$nivel_acesso = 2;$ocupacao = 'Administrativo';break;
				case 3:$nivel_acesso = 3;$ocupacao = 'Web Design';break;
				case 4:$nivel_acesso = 4;$ocupacao = 'Web Master';break;
			}
		?>
		<div class="well" style="margin-top:<?=$margin_top?>;">
			<input type="hidden" name="username" value="<?=$user->username?>">
			<div class="thumbnail">
				<div class="thumb"><img alt="" src="<?=site_url()?>application/third_party/upload/users/thumb/<?=(!empty($user->url_media) ? $user->url_media : 'icon-default.png')?>">
				</div>
				<div class="caption text-center">
				  <h6><?=$user->nome?> <small><?=$ocupacao?></small></h6>
				</div>
				<div class="caption text-center">
				  <h6><button type="button" onclick="change_user()">Trocar usuário</button></h6>
				</div>
			</div>
	  <?php
	  //mostra login sem foto
	  }else{
	  ?>
	  <img class="navbar-img" src="<?=site_url()?>application/third_party/images/interface/logo.png" width="145" alt="Vale do Silício" />
		<?php
		if(isset($msg_error) OR $this->session->flashdata('session_expire'))
		{
			$msg_error = (!empty($msg_error)) ? $msg_error : '';
			echo '<div class="alert alert-danger fade in block-inner" style="margin-top:'.$margin_top_alert.';margin-bottom:'.$margin_bottom_alert.';">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<i class="icon-cancel-circle"></i> 
				'.$this->session->flashdata('session_expire').'
				'.$msg_error.'
			</div>';
		}elseif($this->session->flashdata('notify')){
			echo '<div class="alert '.$this->session->flashdata('window').' fade in block-inner">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<i class="'.$this->session->flashdata('icon').'"></i> 
				'.$this->session->flashdata('message').'
			</div>';
		}
		?>
		
		<div class="popup-header"><span class="pull-left"><i class="icon-user3"></i></span><span class="text-semibold">Entre com seus dados</span>
		  <div class="btn-group pull-right"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
			<i class="icon-cogs"></i></a>
			<ul class="dropdown-menu icons-right dropdown-menu-right" style="width:175px;">
			  <li><a data-toggle="modal" role="button" href="#recuperar_senha"><i class="icon-unlocked2"></i> Esqueci minha senha</a></li>
			</ul>
		  </div>
		</div>
		<div class="well">
		  <div class="form-group has-feedback">
			<label>Nome de usuário</label>
			<br/>
			<input type="text" name="username" class="form-control" placeholder="Usuário" value="<?if(!empty($cookie_pw))echo $cookie_ut;?>">
			<i class="icon-users form-control-feedback"></i>
		  </div>
	  <?php
	  }
	  ?>
      <div class="form-group has-feedback">
        <label>Senha</label>
        <input type="password" name="senha" class="form-control" placeholder="Insira sua senha" value="<?if(!empty($cookie_pw))echo $cookie_pw;?>">
        <i class="icon-lock form-control-feedback"></i>
	  </div>
      <div class="row form-actions">
        <div class="col-xs-6">
          <div class="checkbox checkbox-success">
            <label>
              <input type="checkbox" name="lembrar_login" class="styled" <?if(!empty($cookie_pw))echo "checked=checked";?> />
              Lembrar</label>
          </div>
        </div>
        <div class="col-xs-6">
          <button type="submit" class="btn btn-warning pull-right run-second"><i class="icon-menu2"></i> Entrar</button>
        </div>
      </div>
    </div>
  </form>
	  
	    <?php
				
		$unitys = $this->Configurations->getUnitysLogin();
		
		?>
		<!-- Get code page modal -->
		<div id="solicita_codigo" class="modal fade" tabindex="-1" role="form">
		
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" style="text-align:left;"><i class="icon-cogs"></i> Solicitação de código para acesso</h4>
			  </div>
			  <div class="modal-body with-padding">
				<p style="color:#333333;text-align:left;"> 
					Caso sua unidade ainda não tenha em mãos o código de acesso para iniciar o uso ao painel, encaminhe o pedido selecionando a unidade e clicando em Solicitar código.
				</p>
				<br/>
				  <form method="post" action="<?=site_url()?>session/requestCode" class="validate" role="form">
						<h5>Qual o seu nome</h5>
						<input type="text" class="required form-control" name="code_name" value="" placeholder="Digite seu nome">

						<br/>
						
						<h5>Qual a sua unidade</h5>
						<div class="form-group">
							<div class="row">
							  <div class="col-md-12">
								<?php
								echo '<select data-placeholder="Escolha uma unidade..." class="select" tabindex="2" name="code_unidade">';
										foreach($unitys AS $unity)
										{
											echo '<option value="'.$unity->id.'">'.$unity->nome_unidade.'</option>';
										}
								echo '</select>';
								?>
							  </div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-info" data-dismiss="modal"><i class="icon-cancel-circle"></i> Fechar</button>
						<button type="submit" class="btn btn-success"><i class="icon-checkmark-circle"></i> Solicitar código</button>
					</div>
				</form>
			</div>
		  </div>
		</div>
		<!-- /Get code page modal -->
		
		<!-- Recover pass page modal -->
		<div id="recuperar_senha" class="modal fade" role="form">
		
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" style="text-align:left;"><i class="icon-unlocked2"></i> Recuperar senha de acesso</h4>
				  </div>
					  <div class="modal-body with-padding">
						<p style="color:#333333;text-align:left;"> 
							Informe o seu login de acesso ou o e-mail cadastrado no sistema para receber uma nova senha
						</p>
						<br/>
						  <form method="post" action="<?=site_url()?>session/recoverAccess" role="form">
							<div class="form-group">
								<div class="row">
								  <div class="col-md-12">
									<h5>Qual seu nome</h5>
									<input type="text" class="form-control" name="recover_name" value="" placeholder="Digite seu nome">
									<br/>
									<h5>Qual e-mail cadastrado no sistema</h5>
									<input type="text" class="form-control" name="recover_email" value="" placeholder="Digite seu e-mail">
									</div>
								</div>
							</div>
					
						</div>
						<div class="modal-footer">
							<button class="btn btn-info" data-dismiss="modal"><i class="icon-cancel-circle"></i> Fechar</button>
							<button type="submit" class="btn btn-success run-second"><i class="icon-checkmark-circle"></i> Recuperar senha</button>
						</div>
					</form>
			  </div>
			</div>
		</div>
		<!-- /Recover pass page modal -->
</div>
<form method="post" action="<?=site_url()?>session/troca-usuario" name="form_change_user"></form>
<!-- /login wrapper -->
<?php
if(!empty($cookie_pw))
{
?>
<script type="text/javascript">
function change_user()
{
	$('[name=form_change_user]').submit();
}

</script>
<?php
}
?>
<script type="text/javascript">
	/*$(document).ready(function(){
	  $(document).mousemove(function(e){
		event = e;
		 TweenLite.to($('body'), 
			.5, 
			{ css: 
				{
					backgroundPosition: ""+ parseInt(event.pageX/8) + "px "+parseInt(event.pageY/'12')+"px, "+parseInt(event.pageX/'15')+"px "+parseInt(event.pageY/'15')+"px, "+parseInt(event.pageX/'30')+"px "+parseInt(event.pageY/'30')+"px"
				}
			});
	  });
	});*/
</script>