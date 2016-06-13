<style>
	/* Chrome, Safari, Opera */ 
	@-webkit-keyframes mymove {
	    0% {
	    		-webkit-transform:rotate(0deg);
	    	}
	    	50% {
	    		-webkit-transform:rotate(180deg);
	    	}
	    	100% {
	    		-webkit-transform:rotate(360deg);
	    	}
	}
	@-moz-keyframes mymove {
	    0% {
	    		-moz-transform:rotate(0deg);
	    	}
	    	50% {
	    		-moz-transform:rotate(180deg);
	    	}
	    	100% {
	    		-moz-transform:rotate(360deg);
	    	}
	}
	@-ms-keyframes mymove {
	    0% {
	    		-ms-transform:rotate(0deg);
	    	}
	    	50% {
	    		-ms-transform:rotate(180deg);
	    	}
	    	100% {
	    		-ms-transform:rotate(360deg);
	    	}
	}
	/* Standard syntax */
	@keyframes mymove {
	   0% {
	    		-moz-transform:rotate(0deg);
	    	}
	    	50% {
	    		-moz-transform:rotate(180deg);
	    	}
	    	100% {
	    		-moz-transform:rotate(360deg);
	    	}
	} 
	#reloadCaptchaIMGRecSenha:hover{
		-webkit-animation: mymove 1.5s linear infinite;
	    -moz-animation: mymove 1.5s linear infinite;
	    -ms-animation: mymove 1.5s linear infinite;
	    animation: mymove 1.5s linear infinite;
	}
	.rotateInfinite{
		-webkit-animation: mymove 1.5s linear infinite;
	    -moz-animation: mymove 1.5s linear infinite;
	    -ms-animation: mymove 1.5s linear infinite;
	    animation: mymove 1.5s linear infinite;
	}
</style>
<script>

	
function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            $('input[name=endereco]').val(conteudo.logradouro);
            $('input[name=bairro]').val(conteudo.bairro);
            $('input[name=cidade]').val(conteudo.localidade);
            $('select[name=estado] option[value='+conteudo.uf+']').attr('selected','selected');
       		//$('input[name=estado]').val(conteudo.uf);
           // $('input[name=endereco]').val(conteudo.ibge);
        } //end if.
        else {
            //CEP não Encontrado.
            
            alert("CEP não encontrado.");
        }
    }

</script>
<!-- Modal -->
<div class="modal fade" id="esqueciSenhaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content" style="height: 395px;">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="myModalLabel">Esqueci minha senha</h4>

			</div>

			<form id="formEsqueciSenha" method="POST" action="<?php echo site_url('usuario/esqueciMinhaSenha'); ?>" >
				<div class="modal-body" id='modalEsqueciSenhaBodyDados' style="height:275px;overflow: hidden;">

					<table class="col-sm-12">

						<tbody>
							<tr style="padding:2px;">

								<td>Email do usuário:</td>

							</tr>
							<tr style="padding:2px;">
								<td>
									<input type="email" class="form-control" name="email_recuperar" placeholder="Email configurado na sua conta de usuário." />
								</td>
							</tr>
							<tr style="padding:2px;">
								<td align="center"  style="padding: 8px;">
									<span style="text-align:center;font-size:18px;font-weight:bold;">Digite os caractéres da imagem:</span>
								</td>
							</tr>
							<tr style="padding:2px;">
								<td align="center">
									<span style="cursor:pointer; margin-right: 10px;" onclick="atualizarCaptcha( );">
										<img id="reloadCaptchaIMGRecSenha" src="<?php echo site_url('images/icon-reload-black.png'); ?>" style="margin-bottom: 10px;" alt="reload">
									</span>
								
									<span id="imgCaptcha">
										<img src="<?php echo site_url('captcha'); ?>" id="srcCaptchaRecSenha">
									</span>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input type="text" autocomplete="off" id="captchaRecSenha" name="textCaptcha" class="form-control" style="width:70%;">
								</td>
							</tr>
							<tr>
								<td align="center"  style="padding: 8px;">
									<input type="submit" value="Cadastrar" class="btn" style="width: calc(70% + 10px);" />
								</td>
							</tr>
							<!--
								</div>
								<div class="col-md-12 text-center">
									<input type="text" style="width: 180px;" name="captcha" id="captcha" autocomplete="off">
								</div>
								<div class="col-md-12 text-center">
								<input type="submit" value="Cadastrar" class="btn btn-gvt"> -->
							</tr>
						</tbody>
					</table>
				</div>
			</form>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal" id="closeForm" onclick="$('#form-login').show();">Fechar</button>

			</div>

		</div>

	</div>

</div>
<iframe id="iframeResposta" name="iframeResposta" class='hidden' src="<?php echo site_url('captcha/valideCaptcha'); ?>"></iframe>
<script>
	function setaZindexDatepickerChrome(){
		$('.datepicker-dropdown').css('z-index','1055');
	}
	function proximoForm(){
		$('#modalBodyDados').addClass('hidden');		
		$('#modalContatoBody').removeClass('hidden');		
		$('#closeForm').removeClass('hidden');
		$('#nextForm').addClass('hidden');	
		$('#prevForm').removeClass('hidden');				
		$('#formReady').removeClass('hidden');				

	}
	function voltaForm(){
		$('#modalContatoBody').addClass('hidden');	
		$('#modalBodyDados').removeClass('hidden');		
		$('#prevForm').addClass('hidden');
		$('#nextForm').removeClass('hidden');
		$('#formReady').addClass('hidden');			
	}
	function cadastrarCliente(){
		$('.captchaDivPre').addClass('hidden');
		$('.captchaDivLoading').removeClass('hidden');
		$('#textCaptcha').val($('#captcha').val());
		$('#formUpdateClient').submit();
		var retorno = $('#iframeResposta').contents().find('#respostaEnvioCaptcha');
		setTimeout(function(){
			var interval = setInterval(function(){
				retorno = $('#iframeResposta').contents().find('#respostaEnvioCaptcha');
				if(typeof(retorno.html())!='undefined'){
					//console.log(retorno.html());
					var retornoCaptcha = retorno.html();
					if(retornoCaptcha=='true'){
						var nome = $('input[name=nome]').val();
						var Cep = $('input[name=Cep]').val();
						var endereco = $('input[name=endereco]').val();
						var complemento = $('input[name=complemento]').val();
						var bairro = $('input[name=bairro]').val();
						var cidade = $('input[name=cidade]').val();
						var estado = $('select[name=estado]').val();
						var pessoa = $('input[name=pessoa]').val();
						var rg = $('input[name=rg]').val();
						var estado_rg = $('select[name=estado_rg]').val();
						var data_nasc = $('input[name=data_nasc]').val();
						var cpf = $('input[name=cpf]').val();
						var sexo = $('select[name=sexo]').val();
						var ra_social = $('input[name=ra_social]').val();
						var cnpj = $('input[name=cnpj]').val();
						var captcha = $('#captcha').val().trim();
						var celular = $('input[name=celular]').val();
						var telefone = $('input[name=telefone]').val();
						var email = $('input[name=email]').val();
						var mensagem = $('#areaDescObj').val().trim();
						var validou = true;
						if (nome == '' || Cep == '' || endereco == '' || bairro == '' || cidade == '' || celular == '' || telefone == '' || email == '' || mensagem == '') {
							validou = false;
						};	
						if(pessoa == 'F'){
							if (estado_rg == '' || data_nasc == '' || cpf == '') {
								validou = false;
							};
						}
						else{
							if (ra_social == '' || cnpj == '') {
								validou = false;
							};
						}
						if(validou == true){
						    $.ajax({
								url : '<?php echo site_url('cliente/registerClient'); ?>', 
								cache : false,
								async : false,
								type : 'post',
								dataType : 'html',
								data :  'captcha='+captcha+
										'&nome='+nome+
										'&Cep='+Cep+
										'&endereco='+endereco+
										'&complemento='+complemento+
										'&bairro='+bairro+
										'&cidade='+cidade+
										'&estado='+estado+
										'&pessoa='+pessoa+
										'&rg='+rg+
										'&estado_rg='+estado_rg+
										'&data_nasc='+data_nasc+
										'&cpf='+cpf+
										'&sexo='+sexo+
										'&ra_social='+ra_social+
										'&celular='+celular+
										'&telefone='+telefone+
										'&email='+email+
										'&mensagem='+mensagem+
										'&cnpj='+cnpj,
								success: function(response,status) {
									//console.log(response + '1');
									//console.log(status + '2');
									//$('#categoriaLinha'+cod_categoria).remove();	
									//alert('sucesso !');
									if(response == 'success'){
										$('.captchaDivLoading').addClass('hidden');
										$('.captchaDivReady').removeClass('hidden');
										setTimeout(function(){
											location.reload();
										},4000);
									}
									//alert(status);
								}
							});	
						}
						else{
							alert("Dados preenchidos incorretamente !");
							$('.captchaDivLoading').addClass('hidden');
							$('.captchaDivPre').removeClass('hidden');
						}
					}
					else{
						$('.captchaDivLoading').addClass('hidden');
						$('.captchaDivPre').removeClass('hidden');
						atualizarCaptcha(  );
						alert(retornoCaptcha);
					}
					clearInterval(interval);
				}
			},1000);
		},2000);
			
		
		/*
		
		*/
	}
	$(document).ready(function(){
		//mascara cpf, é chamado no footer seu import
		$("#cpf").mask ( "999.999.999-99" );
		$("#cep").mask ( "99999-999" );
		$("input[name=cnpj]").mask ( "99.999.999/9999-99" );
		$('input[name=celular]').mask ( "(99)9999-9999" );
		$('input[name=telefone]').mask ( "(99)9999-9999" );
		//verifica o padrao do numeo
		$('#radioSP').on('click',function(){
			if($('#radioSP:checked').length>0){
				$('input[name=celular]').mask ( "(99)99999-9999" );
			}
			else{
				$('input[name=celular]').mask ( "(99)9999-9999" );
			}
		});

		//$("#rg").mask ( "99.999.999-x" );
		//datepicker data nasc
    	$('#dp1').datepicker({
	        language: 'pt-BR',
	        format: 'yyyy-mm-dd',
	        autoclose: true
		});

    	$('#radioPF').on('click',function(){
    		$('.pessoaJuridica').fadeOut('350');
    		setTimeout(function(){
	    		$('.pessoaFisica').fadeIn('350');
	    		$('.pessoaFisica').removeClass('hidden');
    		},345);
    	});
    	$('#radioPJ').on('click',function(){
    		$('.pessoaFisica').fadeOut('350');
    		setTimeout(function(){
	    		$('.pessoaJuridica').fadeIn('350');
	    		$('.pessoaJuridica').removeClass('hidden');
			},345);
    	});
    	$('#formReady').on('click',function(){
    		var nome = $('input[name=nome]').val();
			var Cep = $('input[name=Cep]').val();
			var endereco = $('input[name=endereco]').val();
			var complemento = $('input[name=complemento]').val();
			var bairro = $('input[name=bairro]').val();
			var cidade = $('input[name=cidade]').val();
			var estado = $('select[name=estado]').val();
			var pessoa = $('input[name=pessoa]').val();
			var rg = $('input[name=rg]').val();
			var estado_rg = $('select[name=estado_rg]').val();
			var data_nasc = $('input[name=data_nasc]').val();
			var cpf = $('input[name=cpf]').val();
			var sexo = $('select[name=sexo]').val();
			var ra_social = $('input[name=ra_social]').val();
			var cnpj = $('input[name=cnpj]').val();
			var captcha = $('#captcha').val().trim();
			var celular = $('input[name=celular]').val();
			var telefone = $('input[name=telefone]').val();
			var email = $('input[name=email]').val();
			var mensagem = $('#areaDescObj').val().trim();
			var validou = true;
			if (nome == '' || Cep == '' || endereco == '' || bairro == '' || cidade == '' || celular == '' || telefone == '' || email == '' || mensagem == '') {
				validou = false;
			};	
			if(pessoa == 'F'){
				if (estado_rg == '' || data_nasc == '' || cpf == '') {
					validou = false;
				};
			}
			else{
				if (ra_social == '' || cnpj == '') {
					validou = false;
				};
			}
			if(validou == true){
	    		$('#bloco-captcha').fadeIn('350');
	    		$('#permition1Modal').modal('hide')
		    	$('#bloco-captcha').removeClass('hidden');
		    }
		    else{
		    	alert('Dados preendhidos incorretamente !')
		    }
    	});
    	$('#esqueciSenhaModal').on('hidden.bs.modal', function (e) {
    		$('#form-login').slideDown('slow');
		})
		$('#esqueciSenhaModal').on('show.bs.modal', function (e) {
			$('#form-login').hide();
		});
		$('.datepicker-dropdown').css('z-index','1070');

		//tem que dar update no captcha toda vez que carregar a tela.
		atualizarCaptcha();
	});
	function atualizarCaptcha( ){
        //$("#imgCaptcha").hide();
        //var _img    =   $("#imgCaptcha").load ( 'ajax/ajax.php' );
        //$("#imgCaptcha").html ( '<img src="' + _img + '">' );
        $("#srcCaptchaRecSenha").attr('src', '<?php echo site_url(); ?>captcha?' + Date.now() );
        $('#captchaRecSenha').val('');
        //$("#imgCaptcha").show();
    }
    function setPessoa( ){
        $('input[name=pessoa]').val($('input[name=pessoaRadio]:checked').val());
    }
    function contaCaracteres( elemento , callback ){
        var valCallback = $('#' + elemento).val().length;
        $('#' + callback).html( valCallback );
    }
    
</script>

