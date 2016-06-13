 <!-- ckeditor --> 

 <?php	
 	if(!isset($data_user))
 	$data_user = $this->session->userdata('data_user');

 	$data_user = $data_user[0];	
 	//print_r($data_user);die();
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

                	foreach($usuario as $key=>$value){

                		 

                		 $user_id = $usuario[$key]['user_id'];  	

                		 $nome = $usuario[$key]['nome'];  	

                		 $username = $usuario[$key]['username'];  	

                		 $senha = $usuario[$key]['senha'];  	

                		 $email = $usuario[$key]['email'];  	

                		 $created = $usuario[$key]['created'];  	

                		 $created_by = $usuario[$key]['created_by'];  	

                		 $last_mod = $usuario[$key]['last_mod'];  	

                		 $last_mod_by = $usuario[$key]['last_mod_by'];  	

                		 $last_login = $usuario[$key]['last_login'];  	

                		 $last_login_ip = $usuario[$key]['last_login_ip'];  	

                		 $nivel_acesso = $usuario[$key]['nivel_acesso'];  	

                		 //$cargo_acesso = $usuario[$key]['cargo_acesso']; 



                    }

                    ?>

                    <h2 class="col-sm-11" style="border-bottom: 1px solid #bbb;padding-bottom: 9px;">Dados do usuário - <?=$nome?> </h2>

                    <br>

                    <form action="<?=$urlPjt?>/usuario/updateUserAvancado" method="POST">

	                    <table class="col-sm-11">

					        <tr style="padding:2px;">

						        <td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">User Id:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="user_id" value="<?=$user_id?>" placeholder="user_id" class="form-control" type="text" readonly>

								</td>   

							</tr>



							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Nome do usuário:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="nome" value="<?=$nome?>" placeholder="nome" class="form-control" type="text">

								</td>

							</tr>



							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Username:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="username" value="<?=$username?>" placeholder="username" class="form-control" type="text">

								</td>

							</tr>

							

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Email:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="email" value="<?=$email?>" placeholder="email" class="form-control" type="text">

								</td>

							</tr>

							

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Criado em:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="created" value="<?=$created?>" placeholder="created" class="form-control" type="text">

								</td>

							</tr>

							

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Criado por:</label>

								</td>

								<td class="pull-right col-sm-8">

									<select name="created_by" class="form-control">

										<option value="<?=$created_by?>" selected><?=$criado_por?></option>

										<?php

					                	foreach($lista_usuarios as $key=>$value){

					            		echo '<option value="'.$lista_usuarios[$key]['user_id'].'">'.$lista_usuarios[$key]['nome'].'</option>';

					                    }

					                    ?>

									</select>

								</td>

							</tr>

							

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Ultima modificação:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="last_mod" value="<?=$last_mod?>" placeholder="last_mod" class="form-control" type="text" readonly>

								</td>

							</tr>

								

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Modificação feita por:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="last_mod_by" value="<?php echo $last_mod_by ;?>"class="form-control" type="hidden">

									<input  value="<?php echo  $last_mod_by_name; ?>" placeholder="Nunca modificado !" class="form-control" type="text">

									

								</td>

							</tr>

							

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Ultimo login:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="last_login" value="<?=$last_login?>" placeholder="last_login" class="form-control" type="text">

								</td>

							</tr>

							

							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">IP do ultimo login:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="last_login_ip" value="<?=$last_login_ip?>" placeholder="last_login_ip" class="form-control" type="text">

								</td>

							</tr>



							<tr style="padding:2px;">

								<td class="pull-left col-sm-3 form-group">

									<label style="line-height: 2em;">Nivel de acesso:</label>

								</td>

								<td class="pull-right col-sm-8">

									<input name="nivel_acesso" value="<?=$nivel_acesso?>" placeholder="nivel_acesso" class="form-control" type="text">

								</td>

							</tr>



							<tr style="padding:2px;">

								<td class="col-sm-8 text-center" colspam="2">

									<button class="btn btn-default" type="submit" style="width: 80%;">Alterar Usuário</button> 

								</td>  

							</tr>

						</table>

					</form>

				</div><!-- /.col-xs-12 main -->

			</div>

		</div>

	</div>	

    <script>

    	  

    </script>





  



