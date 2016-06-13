 <!-- ckeditor --> 

 <?php	

 	$data_user = $this->session->userdata('data_user');$data_user = $data_user[0];	

 	$urlPjt = 'http://'.$_SERVER['HTTP_HOST'];

 ?> 

<script src="<?php echo site_url(); ?>application/third_party/plugins/ckeditor/ckeditor.js"></script>

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
			url : '<?php echo site_url(); ?>usuario/updateUser', 
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
	function addUser(){

		var nome = $('#addnome').val();

		var username = $('#addusername').val();

		var senha = $('#addsenha').val();

		var email = $('#addemail').val();
		if(validaEmailMain(email)==false){
			$('#failedUpdate').html('Email inválido !');

			$('#failedUpdate').show();
			setTimeout(function(){

				$('#failedUpdate').fadeOut('slow');

			},2500);

			return false;
		}
		if(nivel_acesso>4){
			$('#failedUpdate').html('Nível de acesso inválido !');

			$('#failedUpdate').show();
			setTimeout(function(){

				$('#failedUpdate').fadeOut('slow');

			},2500);

			return false;
		}

		var nivel_acesso = $('#addnivel').val();

		$.ajax({
			url : '<?php echo site_url(); ?>usuario/addUser', 
			cache : false,
			
			type : 'post',
			dataType : 'json',
			data : 'nome='+nome+'&username='+username+'&senha='+senha+'&email='+email+'&nivel_acesso='+nivel_acesso,
			success: function(response,status) {

				//console.log(response.retorno);

				if(response.retorno!='false'){

					$('#successUpdate').html('Usuario '+nome+' criado com sucesso !');

					$('#successUpdate').slideToggle();

					setTimeout(function(){

						$('#successUpdate').slideToggle();

						$('#successUpdate').hide();

					},2500);

					return true;

				}

				else{

					$('#failedUpdate').html(response.erro);

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

		        	<div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Adicionar Usuário</h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th><input type="text" disabled="" placeholder="Nome" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Username" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Senha" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Email" class="form-control"></th>

			                        <th style="width: 5.2%;"><input type="text" disabled="" placeholder="Nivel" class="form-control"></th>

			                	</tr>

			                </thead>
			                <tbody>
			                	<tr>
			               	 		<th><input type="text" placeholder="Nome" id="addnome" class="form-control"></th>

			                        <th><input type="text" placeholder="Username" id="addusername" class="form-control"></th>

			                        <th><input type="text" placeholder="Senha" id="addsenha" class="form-control"></th>

			                        <th><input type="text" placeholder="Email" id="addemail" class="form-control"></th>

			                        <th style="width: 5.2%;"><input type="text" id="addnivel" placeholder="Nivel" class="form-control"></th>

				                    <th><span onClick="addUser()" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" style="cursor:pointer;font-size:25px;color:green;"></span></th>
				           		</tr>
				            </tbody>        	


			            </table>

		            </div>


		        	<div class="panel panel-primary filterable">

			            <div class="panel-heading">

			                <h3 class="panel-title">Usuários</h3>

			                <div class="pull-right">

			                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

			                </div>

			            </div>

			            <table class="table" id="tabelaTopicos">

			                <thead>

			                    <tr class="filters">

			                       	 	 	 

			                        <th><input type="text" disabled="" placeholder="Nome" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Username" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Senha" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Email" class="form-control"></th>

			                        <th style="width: 5.2%;"><input type="text" disabled="" placeholder="Nivel" class="form-control"></th>

			                        <th style="width: 8.3%;"><input type="text" disabled="" placeholder="Avançado" class="form-control"></th>

			                        <th style="width: 6.5%;"><input type="text" class="form-control" placeholder="Alterar" disabled=""></th>

			                     

			                    </tr>

			                </thead>

			                <tbody>

			                	<tr>

			                	<?php

			                	foreach($usuario as $key=>$value){

			                		echo '<tr class="filtrable">';

			                        //echo '<td><input type="hidden" id="user_id" class="form-control">';

			                        echo '<td><input type="text" placeholder="Nome" class="form-control" id="nome'.$usuario[$key]['user_id'].'" value = "'.$usuario[$key]['nome'].'"></td>';

			                        echo '<td><input type="text" placeholder="Username" class="form-control" id="username'.$usuario[$key]['user_id'].'" value = "'.$usuario[$key]['username'].'"></td>';

			                        echo '<td><input type="password" placeholder="Senha" class="form-control" id="senha'.$usuario[$key]['user_id'].'" value = "||vazio||"></td>';

			                        echo '<td><input type="text" placeholder="Email" class="form-control" id="email'.$usuario[$key]['user_id'].'" value = "'.$usuario[$key]['email'].'"></td>';

			                        echo '<td><input type="text" placeholder="Nivel" class="form-control" id="nivel_acesso'.$usuario[$key]['user_id'].'" value = "'.$usuario[$key]['nivel_acesso'].'"></td>';

			                        echo '<td class=" text-center"><a href="'.$urlPjt.'/usuario/avancado/'.$usuario[$key]['user_id'].'"><span style="cursor: pointer; font-size: 27px;" class="hidden-xs showopacity glyphicon glyphicon-briefcase" ></span></a></td>';

			                        echo '<td class="text-center"><span style="cursor: pointer; color: green; font-size: 27px;" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" onclick="updateUser('.$usuario[$key]['user_id'].')"></span></td>';

			                        echo '</tr>';

			                    }

			                    ?>

			                    </tr>

		           			</tbody>

			            </table>

			        </div>

				</div><!-- /.col-xs-12 main -->

			</div>

		</div>

	</div>	

    <script>

    	  

    </script>





  



