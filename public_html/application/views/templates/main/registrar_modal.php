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
	#reloadCaptchaIMG:hover{
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
	function validarCNPJ(cnpj) {
 
	    cnpj = cnpj.replace(/[^\d]+/g,'');
	 
	    if(cnpj == '') return false;
	     
	    if (cnpj.length != 14)
	        return false;
	 
	    // Elimina CNPJs invalidos conhecidos
	    if (cnpj == "00000000000000" || 
	        cnpj == "11111111111111" || 
	        cnpj == "22222222222222" || 
	        cnpj == "33333333333333" || 
	        cnpj == "44444444444444" || 
	        cnpj == "55555555555555" || 
	        cnpj == "66666666666666" || 
	        cnpj == "77777777777777" || 
	        cnpj == "88888888888888" || 
	        cnpj == "99999999999999")
	        return false;
	         
	    // Valida DVs
	    tamanho = cnpj.length - 2
	    numeros = cnpj.substring(0,tamanho);
	    digitos = cnpj.substring(tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--) {
	      soma += numeros.charAt(tamanho - i) * pos--;
	      if (pos < 2)
	            pos = 9;
	    }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(0))
	        return false;
	         
	    tamanho = tamanho + 1;
	    numeros = cnpj.substring(0,tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--) {
	      soma += numeros.charAt(tamanho - i) * pos--;
	      if (pos < 2)
	            pos = 9;
	    }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(1))
	          return false;
	           
	    return true;
	}
	function ValidaCNPJ() {
		var cnpj = $('input[name=cnpj]').val();
		if(cnpj!=""){
			if(validarCNPJ(cnpj)== false){
				alert('Cnpj Inválido !');
				//setTimeout(function(){$('input[name=cnpj]').focus();$('input[name=cnpj]').val('');},100);
				$('input[name=cnpj]').attr('style','border-color: #E60000;box-shadow: 0 1px 1px rgb(230, 0, 0) inset, 0 0 8px rgb(238, 77, 77) outline: 0 none;');
			}
			else{
				$('input[name=cnpj]').attr('style','');
			}
		}
		else{
			$('input[name=cnpj]').attr('style','');
		}

	}
	function ValidaCampoCep() {
		var strCPF = $('#cep').val();
		if(strCPF!=""){
			while(strCPF.indexOf('.') > 0 || strCPF.indexOf('.')>0){
				strCPF = strCPF.replace(".", ""); 
				strCPF = strCPF.replace("-", ""); 
			}
			var validacep = /^[0-9]{5}-?[0-9]{3}$/;
			if(!validacep.test(strCPF)) {
				//alert('Cpf Inválido !');
				setTimeout(function(){$('input[name=cep]').focus();$('input[name=cep]').val('');},100);
				$('input[name=cep]').attr('style','border-color: #E60000;box-shadow: 0 1px 1px rgb(230, 0, 0) inset, 0 0 8px rgb(238, 77, 77) outline: 0 none;');
			}
			else{
				$('input[name=cep]').attr('style','');
			}
		}
		else{
			$('input[name=cep]').attr('style','');
		}

	}
	
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

function getCep(){
		var cep = $('#cep').val();
        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{5}-?[0-9]{3}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

               
                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
               
                alert("Formato de CEP inválido.");
            }
        } //end if.
	}
	function TestaCPF(strCPF) {
	    var Soma;
	    var Resto;
	    if(strCPF.length !=11)
	    	return false;

	    Soma = 0;
		if (strCPF == "00000000000") return false;
	    
		for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
		Resto = (Soma * 10) % 11;
		
	    if ((Resto == 10) || (Resto == 11))  Resto = 0;
	    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
		
		Soma = 0;
	    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
	    Resto = (Soma * 10) % 11;
		
	    if ((Resto == 10) || (Resto == 11))  Resto = 0;
	    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
	    return true;
	}
	function ValidaCampoCpf() {
		var strCPF = $('#cpf').val();
		if(strCPF!=""){
			while(strCPF.indexOf('.') > 0 || strCPF.indexOf('.')>0){
				strCPF = strCPF.replace(".", ""); 
				strCPF = strCPF.replace("-", ""); 
			}
			if(TestaCPF(strCPF)== false){
				alert('Cpf Inválido !');
				//setTimeout(function(){$('#cpf').focus();$('#cpf').val('');},100);
				$('#cpf').attr('style','border-color: #E60000;box-shadow: 0 1px 1px rgb(230, 0, 0) inset, 0 0 8px rgb(238, 77, 77) outline: 0 none;');
			}
			else{
				$('#cpf').attr('style','');
			}
		}
		else{
			$('#cpf').attr('style','');
		}

	}
</script>
<!-- Modal -->
<div class="modal fade" id="permition1Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content" style="height: 760px;">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="myModalLabel">Preencha seus dados</h4>

			</div>

			<form id="formUpdateClient" method="POST" action="<?php echo site_url('captcha/valideCaptcha'); ?>" target="iframeResposta">
				<div class="modal-body" id='modalBodyDados' style="height:635px;">

					<table class="col-sm-12">

						<tbody>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Nome:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input type="text" class="form-control" placeholder="Nome" name="nome">

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cep:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input type="text" class="form-control" placeholder="cep"  id="cep" maxlength="9" onblur="getCep();ValidaCampoCep()" name="Cep">

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Endereço:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input type="text" class="form-control" placeholder="Endereco" name="endereco">

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Complemento:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input type="text" class="form-control" placeholder="Complemento" name="complemento">

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Bairro:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input type="text" class="form-control" placeholder="Bairro" name="bairro">

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cidade:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input type="text" class="form-control" placeholder="Cidade" name="cidade">

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Estado:</label>

								</td>

								<td class="pull-right col-sm-8">

									<select class="form-control" name="estado">
										<option>Selecione</option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espirito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MT">Mato Grosso</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
									</select>

								</td>   

							</tr>
							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Pessoa:</label>

								</td>

								<td class="pull-right col-sm-8">

									<label class="radio-inline">
	                                    <input type="radio" value="F" id="radioPF" name="pessoaRadio" onclick="setPessoa()">Física
	                                </label>
	                                <label class="radio-inline">
	                                    <input type="radio" value="J" id="radioPJ" name="pessoaRadio" onclick="setPessoa()">Jurídica
	                                </label>
	                                <!-- valor do radio é armazenado aqui   v -->
									<input name="pessoa" id="pessoaHidden" type="hidden"/>

								</td>   

							</tr>	
							<!-- pessoa fisica : -->
							<tr style="padding:2px;" class="pessoaFisica hidden">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Data de Nascimento:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input autocomplete="off" name="data_nasc" id="dp1" onclick="setaZindexDatepickerChrome()" data-date-format="yyyy-mm-dd" placeholder="01/01/1985" class="form-control" type="text" readonly>
									<!-- datepicker
									<div class="well">
							            <input type="text" class="span2" value="02-16-2012" id="dp1">
							        </div>
									-->
								</td>

							</tr>
							<tr style="padding:2px;" class="pessoaFisica hidden">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">RG:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input id="rg" name="rg" placeholder="11.111.111-1" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;" class="pessoaFisica hidden">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Estado de emissão do RG:</label>

								</td>

								<td class="pull-right col-sm-8">

									<select name="estado_rg" class="form-control" >
										<option>Selecione</option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espirito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MT">Mato Grosso</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
									</select>	
								</td>   

							</tr>
							<tr style="padding:2px;" class="pessoaFisica hidden">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cpf:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input onblur="ValidaCampoCpf()" id="cpf" maxlength="14"  name="cpf" placeholder="111.111.111-11" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;" class="pessoaFisica hidden">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Sexo:</label>

								</td>

								<td class="pull-right col-sm-8">

									<select name="sexo" class="form-control">

										<option value = 'M'>Masculino</option>	
										<option value = 'F'>Feminino</option>	
									</select>

								</td>   

							</tr>
							
							<!-- fim pessoa fisica -->
							<!-- pessoa juridica -->
							<tr style="padding:2px;" class="pessoaJuridica hidden">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Razão Social:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="ra_social" placeholder="ra_social" class="form-control" type="text">

								</td>   

							</tr>
						<tr style="padding:2px;" class="pessoaJuridica hidden">

					        <td class="pull-left col-sm-3 form-group">

								<label style="line-height: 2em;">Cnpj:</label>

							</td>

							<td class="pull-right col-sm-8">

								<input onblur="ValidaCNPJ()" maxlength="18" name="cnpj"  placeholder="22.532.531/0001-68" class="form-control" type="text">

							</td>   

						</tr>
						<tr style="" class="hidden">

							<td class="pull-right col-sm-8">

								<input name="textCaptcha" id="textCaptcha" type="text"/>

							</td>   

						</tr>	

						</tbody>
					</table>
				</div>
				<div class="modal-body hidden" id="modalContatoBody" style="height:635px;">

					<table class="col-sm-12">

						<tbody>
							<tr style="padding:2px;">
								<td class="pull-left col-sm-3 form-group">
									<label style="line-height: 2em;">Telefone:</label>
								</td>
								<td class="pull-right col-sm-8">
									<input type="text" class="form-control"  name="telefone">
								</td>   
							</tr>
							<tr style="padding:2px;">
								<td class="pull-left col-sm-3 form-group">
									<label style="line-height: 2em;">Celular:</label>
								</td>
								<td class="pull-right col-sm-8">
									<input type="text" class="form-control"  name="celular">
								</td>   
							</tr>
							<tr style="padding:2px;">
								<td class="pull-left col-sm-12 form-group">
									<label style="line-height: 2em;"><input type="checkbox" value="" id="radioSP"> Meu número de celular tem 9 digitos.</label>
								</td>
							</tr>
							<tr style="padding:2px;">
								<td class="pull-left col-sm-3 form-group">
									<label style="line-height: 2em;">Email:</label>
								</td>
								<td class="pull-right col-sm-8">
									<input type="email" class="form-control"  name="email">
								</td>   
							</tr>
							<tr style="padding:2px;">
								<td class=" col-sm-3 form-group">
									<label style="line-height: 2em;">Qual seu objetivo com a plataforma P2WIKI ?</label>
								</td>
							</tr>
							<tr style="padding:2px;">
								<td class=" col-sm-3 form-group">
									<textarea class="form-control" rows="5" maxlength="500" placeholder="Até 500 caracteres." id="areaDescObj" onblur="contaCaracteres('areaDescObj','callbackContaCaracteres')" oncontextmenu="contaCaracteres('areaDescObj','callbackContaCaracteres')" onpaste="contaCaracteres('areaDescObj','callbackContaCaracteres')" onkeyup="contaCaracteres('areaDescObj','callbackContaCaracteres')" onclick="contaCaracteres('areaDescObj','callbackContaCaracteres')"></textarea>
								</td>
							</tr>
							<tr style="padding:2px;">
								<td class=" col-sm-3 form-group">
									<label id='callbackContaCaracteres'>0</label> caracteres escritos.
								</td>
							</tr>
						</tbody>

					</table>

				</div>
			</form>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal" id="closeForm" onclick="$('#form-login').show();">Fechar</button>

				<button type="button" class="btn btn-primary" id="nextForm" onclick="proximoForm()">Proximo</button>

				<button type="button" class="btn btn-primary hidden" id="prevForm" onclick="voltaForm()">Anterior</button>

				<button type="button hidden" class="btn btn-primary hidden" id="formReady">Registrar</button>

			</div>

		</div>

	</div>

</div>
<div class="blocoLogin hidden" id="bloco-captcha" style="z-index:1045;background-color: rgba(217, 217, 217, 0.66); border-radius: 15px; margin-bottom: 30px; padding: 20px;position: absolute; left: 40%; top: 50%; transform: translateY(-50%); min-width: 280px; width: 20%; height: 330px;">
	<div class="row captchaDivPre">
		<h3 style="text-align:center;">Digite os caracteres</h3>
		<h3 style="text-align:center;">Que aparecem a seguir.</h3>
	</div>  
	<div class="row captchaDivPre">
		<div class="col-md-12 text-center">
			<span style="cursor:pointer; margin-right: 10px;" onclick="atualizarCaptchaa( );">
				<img id="reloadCaptchaIMG" src="<?php echo site_url('images/icon-reload.png'); ?>" alt="reload">
			</span>
			<span id="imgCaptcha">
				<img src="<?php echo site_url('captcha'); ?>" id="srcCaptcha">
			</span>
		</div>
		<div class="col-md-12 text-center">
			<input type="text" style="width: 180px;" name="captcha" id="captcha" autocomplete="off">
		</div>
		<div class="col-md-12 text-center">
			<input type="button" value="Cadastrar" onclick="cadastrarCliente()" class="btn btn-gvt" id="btn-entrar">
			<button onclick="$('#permition1Modal').modal('show');$('#bloco-captcha').hide();" class="btn" id="btn-entrar" type="button">Cancelar</button>
		</div>
	</div>
	<div class="row captchaDivLoading hidden" style="">
	 	<img class="rotateInfinite" src="http://www.p2wiki.com.br/images/icon-reload.png" style="margin-top: 120px; height: 40px; margin-left: 135px;">
	</div>
	<div style="margin-top: -20px;" class="row captchaDivReady hidden">
		<h3 style="text-align:center;">Sua requisição para ter acesso à plataforma P2Wiki foi enviada !</h3>
		<h3 style="text-align:center;">Em breve enviaremos no email informado o usuário e senha para seu cadastro.</h3>
		<h3 style="text-align:center;">Caso sua requisição não seja aprovada nós também informaremos via email.</h3>
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
						atualizarCaptchaa(  );
						alert(retornoCaptcha);
					}
					clearInterval(interval);
				}
			},1000);
		},2000);
			
		
		/*
		
		*/
	}
	function atualizarCaptchaa(){
        //$("#imgCaptcha").hide();
        //var _img    =   $("#imgCaptcha").load ( 'ajax/ajax.php' );
        //$("#imgCaptcha").html ( '<img src="' + _img + '">' );
        $("#srcCaptcha").attr('src', '<?php echo site_url(); ?>captcha?' + Date.now() );
        $('#captcha').val('');
        console.log('muda');
        //$("#imgCaptcha").show();
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
    	$('#permition1Modal').on('hidden.bs.modal', function (e) {

		})
		$('#permition1Modal').on('show.bs.modal', function (e) {
			$('#form-login').hide();
		});
		$('.datepicker-dropdown').css('z-index','1070');

		//tem que dar update no captcha toda vez que carregar a tela.
		atualizarCaptchaa();
	});
    $('#reloadCaptchaIMG').click(function(){
		$("#srcCaptcha").attr('src', 'http://www.p2wiki.com.br/captcha?' + Date.now() );
	  	$('#captcha').val('');
  	});
    function setPessoa( ){
        $('input[name=pessoa]').val($('input[name=pessoaRadio]:checked').val());
    }
    function contaCaracteres( elemento , callback ){
        var valCallback = $('#' + elemento).val().length;
        $('#' + callback).html( valCallback );
    }
    
</script>

