 <!-- ckeditor --> 

 <?php	

 	$data_user = $this->session->userdata('data_user');$data_user = $data_user[0];	

 	//$urlPjt = 'http://'.$_SERVER['HTTP_HOST'].'/wiki';
 	$urlPjt = 'http://'.$_SERVER['HTTP_HOST'];

 ?> 

<script src="http://www.p2wiki.com.br/application/third_party/plugins/ckeditor/ckeditor.js"></script>

<style>

.filterable {

    margin-top: 15px;

}

.filterable .panel-heading .pull-right {

    margin-top: -20px;

}

.filterable .filters input[disabled] {

    background-color: transparent;

    border: none;

    cursor: auto;

    box-shadow: none;

    padding: 0;

    height: auto;

}

.filterable .filters input[disabled]::-webkit-input-placeholder {

    color: #333;

}

.filterable .filters input[disabled]::-moz-placeholder {

    color: #333;

}

.filterable .filters input[disabled]:-ms-input-placeholder {

    color: #333;

}

#tabelaTopicos tr:hover{

	background-color:#CCDDED;

}

</style>	

<script>

	$(document).ready(function(){

	    $('.filterable .btn-filter').click(function(){

	        var $panel = $(this).parents('.filterable'),

	        $filters = $panel.find('.filters input'),

	        $tbody = $panel.find('.table tbody');

	        if ($filters.prop('disabled') == true) {

	            $filters.prop('disabled', false);

	            $filters.first().focus();

	            $('#inpTopic').hide();

	        } else {

	            $filters.val('').prop('disabled', true);

	            $tbody.find('.no-result').remove();

	            $tbody.find('tr').show();

	            $('#inpTopic').show();

	        }

	    });



	    $('.filterable .filters input').keyup(function(e){

	        /* Ignore tab key */

	        var code = e.keyCode || e.which;

	        if (code == '9') return;

	        /* Useful DOM data and selectors */

	        var $input = $(this),

	        inputContent = $input.val().toLowerCase(),

	        $panel = $input.parents('.filterable'),

	        column = $panel.find('.filters th').index($input.parents('th')),

	        $table = $panel.find('.table'),

	        $rows = $table.find('tbody tr');

	        /* Dirtiest filter function ever ;) */

	        var $filteredRows = $rows.filter(function(){

	        	var value = $(this).find('td').eq(column).find('input').val();

	        	if(value){

	        		value.toLowerCase();	          

	            	return value.indexOf(inputContent) === -1;

	        	}

	        	return '';				

	        });

	        /* Clean previous no-result if exist */

	        $table.find('tbody .no-result').remove();

	        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */

	        $rows.show();

	        $filteredRows.hide();

	        /* Prepend no-result row if all rows are filtered */

	        if ($filteredRows.length === $rows.length) {

	            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">Nenhum Resultado Encontrado !</td></tr>'));

	        }

	    });

	    

	});

	//before .ready
	function formatar(src, mask){
	    var i = src.value.length;

	    var saida = mask.substring(0,1);
	    var texto = mask.substring(i)
		if (texto.substring(0,1) != saida)
		{
		    src.value += texto.substring(0,1);
		}
	}

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
				setTimeout(function(){$('input[name=cnpj]').focus();$('input[name=cnpj]').val('');},100);
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
		var strCPF = $('input[name=cep]').val();
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
	/*
	function updateUser(user_id){

		var nome = $('#nome'+user_id).val();

		var username = $('#username'+user_id).val();

		var senha = $('#senha'+user_id).val();

		var email = $('#email'+user_id).val();

		var nivel_acesso = $('#nivel_acesso'+user_id).val();

		$.ajax({



			url : 'http://www.p2wiki.com.br/usuario/updateUser', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'user_id='+user_id+'&nome='+nome+'&username='+username+'&senha='+senha+'&email='+email+'&nivel_acesso='+nivel_acesso,



			success: function(response,status) {

				//console.log(response.retorno);

				if(response.retorno!='false'){

					$('#successUpdate').html('Usuario '+nome+' alterado com sucesso !');

					$('#successUpdate').slideToggle();

					setTimeout(function(){

						$('#successUpdate').slideToggle();

						$('#successUpdate').hide();

					},2500);

					return true;

				}

				else{

					$('#successUpdate').html('Usuario '+nome+' não pode ser alterado !');

					$('#failedUpdate').show();

					setTimeout(function(){

						$('#failedUpdate').hide();

					},2500);

					return false;

				}

			}

	   		});

	}
	*/
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

		var cep = $('input[name=cep]').val();

		
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


</script>

<body style="overflow-x:hidden !important;">

	<div class="page-container">

		<!-- menu -->

        <?php $this->load->view('templates/main/menu'); ?>

	    <div class="container">

	        <div class="row row-offcanvas row-offcanvas-left">

	        

		        <div class="col-xs-12 col-sm-10" style="">

		        <div class="alert alert-success" style="display:none;" id="successUpdate" role="alert">Operação realizada com sucesso !</div>

				<div class="alert alert-danger" style="display:none;" id="failedUpdate" role="alert">Operação realizada com erro !</div>

		        	

                	<?php
                	//extrai valores da tabela pessoa fisica
                	extract($cliente_juridico[0]);


                	foreach($cliente as $key=>$value){

                		 

                		 $cod_cliente = $cliente[$key]['cod_cliente'];  	

                		 $nome = $cliente[$key]['nome'];  	

                		 $tipo = $cliente[$key]['tipo'];  	

                		 $cep = $cliente[$key]['cep'];  	

                		 $endereco = $cliente[$key]['endereco'];  	

                		 $complemento = $cliente[$key]['complemento'];  	

                		 $bairro = $cliente[$key]['bairro'];  	

                		 $cidade = $cliente[$key]['cidade'];  	

                		 $estado = $cliente[$key]['estado'];  	

                		 $data_cad = $cliente[$key]['data_cad'];  	

                		 $data_exp = $cliente[$key]['data_exp'];  	

                		 $ativo = $cliente[$key]['ativo'];  	

                    }

                    ?>

                    <h2 class="col-sm-11" style="border-bottom: 1px solid #bbb;padding-bottom: 9px;">Dados do cliente - <?php echo $nome; ?> </h2>

                    <br>

                    <form action="<?=$urlPjt?>/cliente/updateClientAvancadoJuridico" method="POST">

	                    <table class="col-sm-11">

					        <tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">User Id:</label>
								</td>

								<td class="pull-right col-sm-8">

									<input name="cod_cliente" value="<?=$cod_cliente?>" placeholder="cod_cliente" class="form-control" type="text" readonly>

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Nome:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="nome" value="<?=$nome?>" placeholder="nome" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Razão Social:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="ra_social" value="<?php echo $ra_social; ?>" placeholder="ra_social" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cep:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="cep" OnKeyPress="formatar(this, '#####-###')" onBlur="getCep();ValidaCampoCep()" maxlength="9" value="<?=$cep?>" placeholder="cep" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Endereço:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="endereco" value="<?=$endereco?>" placeholder="endereco" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Complemento:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="complemento" value="<?=$complemento?>" placeholder="complemento" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Bairro:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="bairro" value="<?=$bairro?>" placeholder="bairro" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cidade:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="cidade" value="<?=$cidade?>" placeholder="Cidade" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Estado:</label>

								</td>

								<td class="pull-right col-sm-8">

									<select name="estado" value="<?=$estado?>" placeholder="estado" class="form-control" >
										<option value="AC" <?php echo ( $estado == "AC" ? "selected=''" : "" );?>>Acre</option>
										<option value="AL" <?php echo ( $estado == "AL" ? "selected=''" : "" );?>>Alagoas</option>
										<option value="AP" <?php echo ( $estado == "AP" ? "selected=''" : "" );?>>Amapá</option>
										<option value="AM" <?php echo ( $estado == "AM" ? "selected=''" : "" );?>>Amazonas</option>
										<option value="BA" <?php echo ( $estado == "BA" ? "selected=''" : "" );?>>Bahia</option>
										<option value="CE" <?php echo ( $estado == "CE" ? "selected=''" : "" );?>>Ceará</option>
										<option value="DF" <?php echo ( $estado == "DF" ? "selected=''" : "" );?>>Distrito Federal</option>
										<option value="ES" <?php echo ( $estado == "ES" ? "selected=''" : "" );?>>Espirito Santo</option>
										<option value="GO" <?php echo ( $estado == "GO" ? "selected=''" : "" );?>>Goiás</option>
										<option value="MA" <?php echo ( $estado == "MA" ? "selected=''" : "" );?>>Maranhão</option>
										<option value="MS" <?php echo ( $estado == "MS" ? "selected=''" : "" );?>>Mato Grosso do Sul</option>
										<option value="MT" <?php echo ( $estado == "MT" ? "selected=''" : "" );?>>Mato Grosso</option>
										<option value="MG" <?php echo ( $estado == "MG" ? "selected=''" : "" );?>>Minas Gerais</option>
										<option value="PA" <?php echo ( $estado == "PA" ? "selected=''" : "" );?>>Pará</option>
										<option value="PB" <?php echo ( $estado == "PB" ? "selected=''" : "" );?>>Paraíba</option>
										<option value="PR" <?php echo ( $estado == "PR" ? "selected=''" : "" );?>>Paraná</option>
										<option value="PE" <?php echo ( $estado == "PE" ? "selected=''" : "" );?>>Pernambuco</option>
										<option value="PI" <?php echo ( $estado == "PI" ? "selected=''" : "" );?>>Piauí</option>
										<option value="RJ" <?php echo ( $estado == "RJ" ? "selected=''" : "" );?>>Rio de Janeiro</option>
										<option value="RN" <?php echo ( $estado == "RN" ? "selected=''" : "" );?>>Rio Grande do Norte</option>
										<option value="RS" <?php echo ( $estado == "RS" ? "selected=''" : "" );?>>Rio Grande do Sul</option>
										<option value="RO" <?php echo ( $estado == "RO" ? "selected=''" : "" );?>>Rondônia</option>
										<option value="RR" <?php echo ( $estado == "RR" ? "selected=''" : "" );?>>Roraima</option>
										<option value="SC" <?php echo ( $estado == "SC" ? "selected=''" : "" );?>>Santa Catarina</option>
										<option value="SP" <?php echo ( $estado == "SP" ? "selected=''" : "" );?>>São Paulo</option>
										<option value="SE" <?php echo ( $estado == "SE" ? "selected=''" : "" );?>>Sergipe</option>
										<option value="TO" <?php echo ( $estado == "TO" ? "selected=''" : "" );?>>Tocantins</option>
									</select>
								</td>   

							</tr>	
							<tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cnpj:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input onblur="ValidaCNPJ()" maxlength="18" OnKeyPress="formatar(this, '##.###.###/####-##')" name="cnpj" value="<?php echo $cnpj; ?>" placeholder="22.532.531/0001-68" class="form-control" type="text">

								</td>   

							</tr>
							<tr style="padding:2px;">
								<td class="pull-right col-sm-8">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="ativo" <?php echo $ativo==1?'checked':'';?> > 
										</label>
									</div>
								</td>   

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Cliente Ativo:</label>

								</td>


							</tr>
							<tr style="padding:2px;">

								<td class="col-sm-8 text-center" colspam="2">

									<button class="btn btn-default" type="submit" style="width: 100%;">Alterar Cliente</button> 

								</td>  

							</tr>

						</table>

					</form>

				</div><!-- /.col-xs-12 main -->
				<!-- /adicionar_usuario_adm -->		
				<?php $data['cod_cliente'] = $cod_cliente; $this->load->view('pages/main/adicionar_usuario_adm',$data); ?>
				
			</div>

		</div>

	</div>	

    <script>
    //chama verifi~cação do cep
    //if($('input[name=cep]').val()!="")getCep();
    	  

    </script>





  



