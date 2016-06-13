 <!-- ckeditor --> 

 <?php	

 	$data_user = $this->session->userdata('data_user');$data_user = $data_user[0];	

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
		//ativa os datepickers
		$('input[id^=data_]').attr('data-date-format',"yyyy-mm-dd");
		$('input[id^=data_]').datepicker();

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

	        		value = value.toLowerCase();	          

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

	function updateClient(cod_cliente){
		var nome = $('#nome'+cod_cliente).val();
		var cep = $('#cep'+cod_cliente).val();
		var tipo = $('#tipo'+cod_cliente).val();
		var data_cad = $('#data_cad'+cod_cliente).val();
		var data_exp = $('#data_exp'+cod_cliente).val();

		$.ajax({

			url : 'http://www.p2wiki.com.br/cliente/updateClient', 

			cache : false,

			async : false,

			type : 'post',

			dataType : 'json',

			data : 'cod_cliente='+cod_cliente+'&nome='+nome+'&cep='+cep+'&tipo='+tipo+'&data_cad='+data_cad+'&data_exp='+data_exp,

			success: function(response,status) {

				//console.log(response.retorno);

				if(response.retorno!='false'){

					$('#successUpdate').html('Cliente '+nome+' alterado com sucesso !');

					$('#successUpdate').slideToggle();

					setTimeout(function(){

						$('#successUpdate').slideToggle();

						$('#successUpdate').hide();

					},2500);

					return true;

				}

				else{

					$('#successUpdate').html('Cliente '+nome+' não pode ser alterado !');

					$('#failedUpdate').show();

					setTimeout(function(){

						$('#failedUpdate').hide();

					},2500);

					return false;

				}

			}

	   		});

	}
	function addClient(){

		var nome = $('#addnome').val();

		var tipo = $('#addTipo').val();

		$.ajax({



			url : 'http://www.p2wiki.com.br/cliente/addClient', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'nome='+nome+'&tipo='+tipo,



			success: function(response,status) {

				//console.log(response.retorno);

				if(response.retorno!='false'){

					$('#successUpdate').html('Cliente '+nome+' criado com sucesso !');

					$('#successUpdate').slideToggle();

					setTimeout(function(){

						$('#successUpdate').slideToggle();

						$('#successUpdate').hide();

					},2500);

					return true;

				}

				else{

					$('#successUpdate').html('Cliente '+nome+' não pode ser criado !');

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

			                <h3 class="panel-title">Adicionar Cliente</h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th><input type="text" disabled="" placeholder="Nome" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Tipo" class="form-control"></th>

			                        <!--<th><input type="text" disabled="" placeholder="Tipo de Inclusão" class="form-control text-center"></th>-->
			                        <th><input type="text" disabled="" placeholder="" class="form-control text-center"></th>

			                	</tr>

			                </thead>
			                <tbody>
			                	<tr>
			               	 		<th><input type="text" placeholder="Nome" id="addnome" class="form-control"></th>

			               	 		<th>
			                        	<select type="text" id="addTipo" class="form-control">

			                        		<option selected="">F</option>
			                        		<option>J</option>

			                        	</select>
			                    	</th>

			                       <!-- <th><button class="form-control btn btn-default" onClick="addClient()" style="width:50%;border-radius: 5px 0px 0px 5px;" type="button">Genérica</button><button class="form-control btn btn-default" type="button"  style="width:50%;border-radius: 0px 5px 5px 0px; border-left: medium none navy;">Avançada</button></th>-->
				           			<th><button class="form-control btn btn-default" onClick="addClient()"  type="button">Incluir</button>
				           		</tr>
				            </tbody>        	


			            </table>

		            </div>


		        	<div class="panel panel-primary filterable">

			            <div class="panel-heading">

			                <h3 class="panel-title">Clientes</h3>

			                <div class="pull-right">

			                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

			                </div>

			            </div>

			            <table class="table" id="tabelaTopicos">

			                <thead>

			                    <tr class="filters">

			                       	 	 	 

			                        <th><input type="text" disabled="" placeholder="Código Cliente" class="form-control"></th>

			                        <th><input type="text" disabled="" placeholder="Nome Cliente" class="form-control"></th>

			                        <th style="width: 15%;"><input type="text" disabled="" placeholder="Cep" class="form-control"></th>

			                        <th style="width: 5%;"><input type="text" disabled="" placeholder="Tipo" class="form-control"></th>

			                        <th style="width: 11.4%;"><input type="text" disabled="" placeholder="Cadastro em:" class="form-control" style="padding-right: 0px; padding-left: 4px;"></th>

			                        <th style="width: 11.4%;"><input type="text" disabled="" placeholder="Expira em:" class="form-control"></th>

			                        <th style="width: 8.3%;"><input type="text" disabled="" disabled placeholder="Informações" class="form-control"></th>

			                        <th style="width: 6.5%;"><input type="text" class="form-control" disabled placeholder="Alterar" disabled=""></th>

			                     

			                    </tr>

			                </thead>

			                <tbody>

			                	<tr>

			                	<?php
			                	if($cliente != 'vazio')
			                	foreach($cliente as $key=>$value){

			                		echo '<tr class="filtrable">';
			                		//cod_cliente,nome,cep,tipo,data_cad,data_exp
			                        //echo '<td><input type="hidden" id="user_id" class="form-control">';

			                        echo '<td><input type="text" placeholder="Código" class="form-control" id="cod_cliente'.$cliente[$key]['cod_cliente'].'" value = "'.$cliente[$key]['cod_cliente'].'"></td>';

			                        echo '<td><input type="text" placeholder="Nome" class="form-control" id="nome'.$cliente[$key]['cod_cliente'].'" value = "'.$cliente[$key]['nome'].'"></td>';

			                        echo '<td><input type="text" placeholder="00000-000" class="form-control" id="cep'.$cliente[$key]['cod_cliente'].'" value = "'.$cliente[$key]['cep'].'"></td>';

			                        echo '<td><input type="text" placeholder="F" class="form-control" id="tipo'.$cliente[$key]['cod_cliente'].'" value = "'.$cliente[$key]['tipo'].'"></td>';

			                        echo '<td><input type="text" placeholder="01/01/2015" class="form-control" id="data_cad'.$cliente[$key]['cod_cliente'].'" value = "'.$cliente[$key]['data_cad'].'"></td>';
			                       
			                        echo '<td><input type="text" placeholder="01/01/2015" class="form-control" id="data_exp'.$cliente[$key]['cod_cliente'].'" value = "'.$cliente[$key]['data_exp'].'"></td>';

			                        echo '<td class=" text-center"><a href="'.$urlPjt.'/cliente/avancado/'.$cliente[$key]['cod_cliente'].'/'.$cliente[$key]['tipo'].'"><span style="cursor: pointer; font-size: 27px;" class="hidden-xs showopacity glyphicon glyphicon-briefcase" ></span></a></td>';

			                        echo '<td class="text-center"><span style="cursor: pointer; color: green; font-size: 27px;" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" onclick="updateClient('.$cliente[$key]['cod_cliente'].')"></span></td>';

			                        echo '</tr>';

			                    }
			                    else{
			                    	echo '<tr class="filtrable">';

			                        echo '<td class=" text-center" colspan="8">Sem Clientes !</td>';


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





  



