<!-- adicionar_usuario_adm.php -->
<script>
	function addUser(){
		var mensagemerro = "";
		var nomedousuario = $('input[name=nomedousuario]').val();
		var username = $('input[name=username]').val();
		var usersenha = $('input[name=usersenha]').val();
		var useremail = $('input[name=useremail]').val();
		var tipo_usuario = $('#selectADDUserType').val();
		var v;
		var c;
		if(nomedousuario.length == 0){
			mensagemerro = "Por favor preencha o campo nome do usuário";
		}
		if(username.length == 0){
			mensagemerro = "Por favor preencha o campo username";
		}
		if(useremail.length == 0){
			mensagemerro = "Por favor preencha o campo email";
		}
		if(usersenha!=$('#repitaSenha').val()){
			mensagemerro = "O campó repita senha esta diferente !";
		}
		$("[name=usersenha]").complexify({}, function (valid, complexity) {
		   v = valid;
		   c = complexity
		});
		if(c < 45 && v != true){
			mensagemerro = 'Sua senha deve conter pelo menos 8 catacteres dentre eles letras maiusculas, minusculas e números';
		}
		if(mensagemerro.length > 0){
			$('#failedUpdateADMCAD').html(mensagemerro);
			$('#failedUpdateADMCAD').fadeIn();

			setTimeout(function(){

				$('#failedUpdateADMCAD').fadeOut();

			},4000);
			return false;
			//console.log(v);
		}

		$.ajax({
			url : '<?php echo site_url(); ?>cliente/insertUserAdm', 
			cache : false,
			async : false,
			type : 'post',
			dataType : 'json',
			data : 'nomedousuario='+nomedousuario+'&username='+username+'&usersenha='+usersenha+'&useremail='+useremail+'&nivel_acesso=2&cod_cliente=<?php echo $this->session->userdata('ultimo_cliente_acessado'); ?>&tipo_usuario='+tipo_usuario,
			success: function(response,status) {
				//console.log(response.retorno);
				if(response.retorno!='false'){
					$('#successUpdateADMCAD').html('Usuario '+nomedousuario+' criado com sucesso !');

					$('#successUpdateADMCAD').slideToggle();

					setTimeout(function(){
						$('#successUpdateADMCAD').slideToggle();
						$('#successUpdateADMCAD').fadeOut();
					},2500);
					return true;
				}
				else{
					$('#failedUpdateADMCAD').html(response.erro);
					$('#failedUpdateADMCAD').fadeIn();

					setTimeout(function(){

						$('#failedUpdateADMCAD').fadeOut();

					},4000);

					return false;
				}
			}
	   	});

	}
	$(document).ready(function(){

	});
</script>
<div class="col-xs-12 col-sm-9"  style="width: 90%; padding-top: 100px;padding-bottom: 100px;">
	<div class="alert alert-success" style="display:none;" id="successUpdateADMCAD" role="alert">Usuário cadastrado com sucesso !</div>
	<div class="alert alert-danger" style="display:none;" id="failedUpdateADMCAD" role="alert">Usuário não cadastrado !</div>
	<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" class="col-sm-11">Incluir Usuário </h2>	
		<table class="col-sm-11" style="padding-top:15px;padding-bottom:100px;">
			<tbody>
				<tr style="padding:2px;">

					<td class="pull-left col-sm-3 form-group">

						<label style="line-height: 2em;">Nome do Administrador:</label>

					</td>

					<td class="pull-right col-sm-8">

						<input type="text"  name="nomedousuario" placeholder="Nome completo do usuário." class="form-control">

					</td>   

				</tr>
				<tr style="padding:2px;">

					<td class="pull-left col-sm-3 form-group">

						<label style="line-height: 2em;">Username:</label>

					</td>

					<td class="pull-right col-sm-8">

						<input type="text"  maxlength="30" name="username" placeholder="Nome único, não pode conter espaços em branco." class="form-control">

					</td>   

				</tr>
				<tr style="padding:2px;">

					<td class="pull-left col-sm-3 form-group">

						<label style="line-height: 2em;">Senha:</label>

					</td>

					<td class="pull-right col-sm-8">

						<input type="password" placeholder="Pelo menos 8 caracteres com letras , números e caracteres especiais." name="usersenha"  class="form-control">

					</td>   

				</tr>	
				<tr style="padding:2px;">

					<td class="pull-left col-sm-3 form-group">

						<label style="line-height: 2em;">Repita a senha:</label>

					</td>

					<td class="pull-right col-sm-8">

						<input type="password" id="repitaSenha" placeholder="Pelo menos 8 caracteres com letras , números e caracteres especiais." class="form-control">

					</td>   

				</tr>
				<tr style="padding:2px;">

					<td class="pull-left col-sm-3 form-group">

						<label style="line-height: 2em;">Email:</label>

					</td>

					<td class="pull-right col-sm-8">

						<input type="text"  name="useremail" placeholder="exemplo@exemplo.com.br" class="form-control">

					</td>   

				</tr>
				<tr style="padding:2px;">

					<td class="pull-left col-sm-3 form-group">

						<label style="line-height: 2em;">Tipo de usuário:</label>

					</td>

					<td class="pull-right col-sm-8">

						<select class="form-control" id="selectADDUserType">
							<option value="ADM">Administrador</option>
							<option value="COMUM">Comum</option>
						</select>

					</td>   

				</tr>

				<tr style="padding:2px;">

					<td class="col-sm-8 text-center" colspam="2">

						<div class="btn btn-default" onclick="addUser()"  style="width: 100%;">Incluir Usuário</div> 

					</td>  

				</tr>

			</tbody>
		</table>
</div>