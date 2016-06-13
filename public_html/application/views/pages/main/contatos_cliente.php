<div class="page-container">
	<div class="container" style="padding-top: 1em; padding-left: 1em;">
		<div class="col-xs-12 col-sm-10"  style="padding-bottom: 100px;">
			<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" >Inserir Contatos</h2>
				<div class="alert alert-success" style="display:none;" id="successUpdateADDCONTATO" role="alert">Usuário cadastrado com sucesso !</div>
				<div class="alert alert-danger" style="display:none;" id="failedUpdateADDCONTATO" role="alert">Usuário não cadastrado !</div>
				<div>
					<table>
						<tr>
							<td style="padding:10px;" class="form-inline">
								<label>Contato: </label>
							</td>	
							<td class="form-inline" style="width:75%;">
								<input type="text" style="width:100%;" class="form-control" name="contato" id="send_contato" onblur="formataCelular('#send_contato','#select_tipo_contato')" />
							</td>	
						</tr>
						<tr>
							<td style="padding:10px;" class="form-inline">
								<label>Falar com: </label>
							</td>	
							<td class="form-inline" style="width:75%;">
								<input type="text" style="width:100%;" class="form-control" name="falar_com" id="contato_falar_com" />
							</td>	
						</tr>
						<tr>
							<td style="padding:10px;" class="form-inline">
								<label>Tipo de Contato: </label>
							</td>	
							<td class="form-inline">
								<select name="tipo_contato" id="select_tipo_contato"  class="form-control pull-right" onchange="changeMaskContato()">
									<option value="TF">Telefone Fixo</option>
									<option value="TC">Telefone Celular</option>
									<option value="EM">Email</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="form-inline" style="padding-top: 20px;padding-bottom: 20px;">
								<div class="btn btn-default" onclick="addContato()" style="width: 100%;">Adicionar Contato</div>
							</td>
						</tr>
					</table>	
					<!-- Nav tabs -->
					<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" >Contatos</h2>
					<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#Telefone_Fixo" aria-controls="Telefone Fixo" role="tab" data-toggle="tab">Telefone Fixo</a></li>
					<li role="presentation"><a href="#Celular" aria-controls="Celular" role="tab" data-toggle="tab">Celular</a></li>
					<li role="presentation"><a href="#Email" aria-controls="Email" role="tab" data-toggle="tab">Email</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="Telefone_Fixo">
							<div class="panel-body" style="background: white none repeat scroll 0% 0%; border-right: 1px solid rgb(221, 221, 221); border-bottom: 1px solid rgb(221, 221, 221); border-left: 1px solid rgb(221, 221, 221); border-radius: 0px 0px 5px 5px;">
		                            <div class="table-responsive">
		                                <table class="table table-hover">
		                                    <thead>
		                                        <tr>
		                                            <th>#</th>
		                                            <th>Contato</th>
		                                            <th>Falar com</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                            <?php 
		                                            	foreach ($contatos_cliente as $value) {
		                                            		if($value['tipo']=='TF'){
			                                            		echo '<tr>';
			                                            		echo 	'<td>'.$value['cod_contato'].'</td>';
			                                            		echo    '<td>'.$value['contato'].'</td>';
			                                            		echo    '<td>'.$value['falar_com'].'</td>';
			                                            		echo '</tr>';
			                                            	}
		                                            	}
		                                            ?>
		                                    </tbody>
		                                </table>
		                            </div>
		                            <!-- /.table-responsive -->
		                        </div>
						</div>
						<div role="tabpanel" class="tab-pane" id="Celular">
							<div class="panel-body">
		                            <div class="table-responsive">
		                                <table class="table table-hover">
		                                    <thead>
		                                        <tr>
		                                            <th>#</th>
		                                            <th>Contato</th>
		                                            <th>Falar com</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                            <?php 
		                                            	foreach ($contatos_cliente as $value) {
		                                            		if($value['tipo']=='TM'){
			                                            		echo '<tr>';
			                                            		echo 	'<td>'.$value['cod_contato'].'</td>';
			                                            		echo    '<td>'.$value['contato'].'</td>';
			                                            		echo    '<td>'.$value['falar_com'].'</td>';
			                                            		echo '</tr>';
			                                            	}
		                                            	}
		                                            ?>
		                                    </tbody>
		                                </table>
		                            </div>
		                            <!-- /.table-responsive -->
		                        </div>
						</div>
						<div role="tabpanel" class="tab-pane" id="Email">
							<div class="panel-body">
		                            <div class="table-responsive">
		                                <table class="table table-hover">
		                                    <thead>
		                                        <tr>
		                                            <th>#</th>
		                                            <th>Contato</th>
		                                            <th>Falar com</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                            <?php 
		                                            	foreach ($contatos_cliente as $value) {
		                                            		if($value['tipo']=='EM'){
			                                            		echo '<tr>';
			                                            		echo 	'<td>'.$value['cod_contato'].'</td>';
			                                            		echo    '<td>'.$value['contato'].'</td>';
			                                            		echo    '<td>'.$value['falar_com'].'</td>';
			                                            		echo '</tr>';
			                                            	}
		                                            	}
		                                            ?>
		                                    </tbody>
		                                </table>
		                            </div>
		                            <!-- /.table-responsive -->
		                        </div>
						</div>
					</div>

				</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		changeMaskContato();
	});
	function changeMaskContato(){
		switch($('#select_tipo_contato').val()){ 
			case "EM":
				$.mask.definitions['_']=/[^ ]/;
				$("#send_contato").mask ("?___________________________________________________",{placeholder:" "});
			break;
			case "TC":
				$("#send_contato").mask ("(99)99999-999?9");
			break;
			case "TF":
				$("#send_contato").mask ("(99)9999-9999");
			break;
		}
	}
	function formataCelular(e,sel){
		if($(sel).val() == "TC"){
			var str = $(e).val();
			if(str.indexOf('_')==12){
			  $(e).mask ("(99)9999-9999");
			}
			if(str=='(__)____-____' || str.trim() == ''){
				$(e).mask ("(99)9999-9999?9");
			}
		}
	}
	function addContato(){
		var mensagemerro = "";
		var tipo_contato = $('#select_tipo_contato').val();
		var contato = $('#send_contato').val();
		var falar_com = $('#contato_falar_com').val();
		if(contato.length == 0){
			mensagemerro = "Por favor preencha o campo contato.";
		}
		if(falar_com.length == 0){
			mensagemerro = "Por favor preencha o campo 'Falar com'";
		}
		if(mensagemerro.length > 0){
			$('#failedUpdateADDCONTATO').html(mensagemerro);
			$('#failedUpdateADDCONTATO').fadeIn();

			setTimeout(function(){

				$('#failedUpdateADDCONTATO').fadeOut();

			},4000);
			return false;
			//console.log(v);
		}

		$.ajax({
			url : '<?php echo site_url(); ?>cliente/inserirContato', 
			cache : false,
			async : false,
			type : 'post',
			dataType : 'json',
			data : 'tipo_contato='+tipo_contato+'&contato='+contato+'&falar_com='+falar_com+'&cod_cliente=<?php 
				echo $this->session->userdata('ultimo_cliente_acessado');
			?>',
			success: function(response,status) {
				//console.log(response.retorno);
				if(response.retorno!='false'){
					$('#successUpdateADDCONTATO').html('Contato '+contato+' criado com sucesso !');

					$('#successUpdateADDCONTATO').slideToggle();

					setTimeout(function(){
						$('#successUpdateADDCONTATO').slideToggle();
						$('#successUpdateADDCONTATO').fadeOut();
					},2500);
					return true;
				}
				else{
					$('#failedUpdateADDCONTATO').html(response.erro);
					$('#failedUpdateADDCONTATO').fadeIn();

					setTimeout(function(){

						$('#failedUpdateADDCONTATO').fadeOut();

					},4000);

					return false;
				}
			}
	   	});

	}
</script>