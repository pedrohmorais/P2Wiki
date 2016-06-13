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

#ulTabs .quaseActive a{

	color:#555555;

	background-color:transparent;

}

#ulTabs .quaseActive a:hover{

	color:#7CBDDE;

	cursor:pointer;

	background-color:transparent;

}

#ulTabs .active a:hover{

	color:#7CBDDE;

	cursor:pointer;

}

</style>	

<script>

	/*

	Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !

	*/

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

	            var value = $(this).find('td').eq(column).text().toLowerCase();

	            return value.indexOf(inputContent) === -1;

	        });

	        /* Clean previous no-result if exist */

	        $table.find('tbody .no-result').remove();

	        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */

	        $rows.show();

	        $filteredRows.hide();

	        /* Prepend no-result row if all rows are filtered */

	        if ($filteredRows.length === $rows.length) {

	            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));

	        }

	    });

	   

	});



	function deleteTopicos(cod_topico){

	    $.ajax({



			url : 'http://www.p2wiki.com.br/categoria/deleteTopicos', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'cod_topico='+cod_topico,



			success: function(response,status) {



				//console.log(response.retorno);

				$('#topicoLinha'+cod_topico).remove();	

			//alert('sucesso !');

			return true;

			}





		});

		

	}

	function insertTopicos(){

		$('#progressLoader').css('display','block');

		$('#porcentagemBarra').css('width','15%');

		var nome_topico = $('#nome_topico').val();

		var link_topico = $('#link_topico').val();

		var cod_categoria = $('#cod_categoria').val();

		var ordem = $('#ordem').val();

		

	    $.ajax({



			url : 'http://www.p2wiki.com.br/categoria/insertTopicos', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'nome_topico='+nome_topico+'&link_topico='+link_topico+'&cod_categoria='+cod_categoria+'&ordem='+ordem,



			success: function(response,status) {

				$('#porcentagemBarra').css('width','45%');	

				//console.log(response.retorno);

					

				if(response!='false'){

					

					//var topicoAdd = parseInt($("#tabelaTopicos").find('tr:last').attr('id').substring(11)) + 1;

					//var linha =  '<tr id="topicoLinha' + response + '">';

                    //linha    +=  '<td class="col-sm-1">' + response + '</td>';

                    //linha    +=  '<td>' + nome_topico + '</td>';

                    //linha    +=  '<td>' + link_topico + '</td>';

                    //linha    +=  '<td>' + cod_categoria + '</td>';

                    //linha    +=  '<td class="col-sm-1 text-center">' + ordem + '</td>';

                    //linha    +=  '<td class="col-sm-1 text-center"><span onClick="deleteTopicos(' + response + ')" class="hidden-xs showopacity glyphicon glyphicon-remove" style="cursor:pointer;font-size:16px;color:red;"></span></td>';

                    //linha    +=  '</tr>';

                    //alert(linha);

                    //$('#tabelaTopicos > tbody').append(linha);

                    $('#porcentagemBarra').css('width','75%');

                    location.reload(); 

				}

				return true;

			}





		});

		if(link_topico=='' || link_topico=='#'){

			('#link_topico').val("http://www.p2wiki.com.br/conteudo/visualizar/" + response);

		}

		

	}

</script>

<body style="overflow-x:hidden !important;">

	<!-- Loader -->

	<div id="progressLoader" style="display:none;width: 100%; z-index: 1032 ! important; margin-top: -4em; background-color: rgb(51, 51, 51); position: fixed; height: calc(100% + 4em); opacity: 0.7;">

		<div style="position: relative; left: 13%; width: 75%; height: 50px; top: 43%;" class="progress">

			<span style="font-size: 1.3em; line-height: 2.6em; color: rgb(0, 0, 0); position: absolute; top: 0px; left: 41%;">

				Inserindo Tópico

			</span>

			<div id="porcentagemBarra" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%">

				<span class="sr-only">

					61% Complete

				</span>

			</div>

		</div>

	</div>

	<!-- Loader -->



	<div class="page-container">

		 <!-- menu -->

	    <?php $this->load->view('templates/main/menu'); ?>

	    <div class="container">

	        <div class="row row-offcanvas row-offcanvas-left">

	        

		        <div class="col-xs-12 col-sm-9" >

		        <ul id="ulTabs" class="nav nav-pills">

				    <li role="presentation" class="quaseActive"><a href="http://www.p2wiki.com.br/categoria">Categorias</a></li>

				     <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;"></span> </li>

				    <li role="presentation" class="active"><a href="">Topicos</a></li>

				</ul>

		        <?php 

					foreach($topicos as $key=>$value){

						$topicos[$key] = (array)$topicos[$key];

					}

						

				?>

					<div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Adicionar</h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th><input type="text" class="form-control" placeholder="Nome" id="nome_topico"></th>

				                    <th><input type="text" class="form-control" placeholder="Link" id="link_topico"></th>

				                    <th><input type="text" class="form-control" placeholder="Categoria" id="cod_categoria" readonly></th>

				                    <th><input type="text" class="form-control" placeholder="Ordem" id="ordem"></th>

				                    <th><span onClick="insertTopicos()" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" style="cursor:pointer;font-size:25px;color:green;"></span></th>

			                	</tr>

			                </thead>



			            </table>

		            </div>
		            <?php 
		            	$categoria_validada = isset($categoria_validada)?$categoria_validada:'N';
		            	if($categoria_validada != 'S'){
		            ?>
		            <div class="panel panel-primary filterable">

			            <div class="panel-heading">

			                <h3 class="panel-title">Tópicos</h3>

			                <div class="pull-right">

			                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

			                </div>

			            </div>

			            <table id="tabelaTopicos" class="table">

			                <thead>

			                    <tr class="filters">

			                       	 	 	 

			                        <th><input type="text" class="form-control" placeholder="#" disabled></th>

			                        <th><input type="text" class="form-control" placeholder="Nome" disabled></th>

			                        <th><input type="text" class="form-control" placeholder="Link" disabled></th>

			                        <th><input type="text" class="form-control" placeholder="Categoria" disabled></th>

			                        <th><input type="text" class="form-control" placeholder="Ordem" disabled></th>

			                        <th><input type="text" class="form-control text-center" disabled id="editTopico" placeholder="Editar"></th>

			                        <th><input type="text" class="form-control text-center" disabled id="inpTopic" placeholder="Deletar"></th>

			                    </tr>

			                </thead>

			                <tbody>

			                <?php

			                	foreach($topicos as $key=>$value)

								{

									if(isset($topicos[$key]['cod_topico']))

									{

					                   echo '<tr id="topicoLinha'.$topicos[$key]['cod_topico'].'">';

					                   echo    '<td class="col-sm-1">'.$topicos[$key]['cod_topico'].'</td>';

					                   echo    '<td>'.$topicos[$key]['nome_topico'].'</td>';

					                   echo    '<td>'.$topicos[$key]['link_topico'].'</td>';

					                   echo    '<td>'.$topicos[$key]['cod_categoria'].'</td>';

					                   echo    '<td class="col-sm-1 text-center">'.$topicos[$key]['ordem'].'</td>';

					                   echo    '<td class="col-sm-1 text-center"><a href="'.site_url().'categoria/editar_topico/'.$topicos[$key]['cod_topico'].'"><span class="hidden-xs showopacity  glyphicon glyphicon-pencil" style="cursor:pointer;font-size:16px;color:#333;"></span></a></td>';

					                   echo    '<td class="col-sm-1 text-center"><span onClick="deleteTopicos('.$topicos[$key]['cod_topico'].')" class="hidden-xs showopacity glyphicon glyphicon-remove" style="cursor:pointer;font-size:16px;color:red;"></span></td>';

					                   echo '</tr>';

					                   $cod_categoria = $topicos[$key]['cod_categoria'];

					                }

				                }

			                ?>

		           			</tbody>

			            </table>

			        </div>
			        <?php 
			    		}
			    		else{
			    			echo '<h1 style="text-align: center; padding: 20px;">Categoria já foi validada e não pode ser editada no momento, caso precise fazer alterações fale com o Administrador de seu grupo.</h1>';
			    		}
			         ?>
				  

		        </div><!-- /.col-xs-12 main -->



	  		</div>

		</div>

	</div>	

	 

	 	        

    <script>

    	  $('#cod_categoria').val('<?php echo $cod_categoria_pagina.' - '.$categoria_info->nome_categoria; ?>');

    </script>





  



