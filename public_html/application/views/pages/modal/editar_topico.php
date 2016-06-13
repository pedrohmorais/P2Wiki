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

	   //Deleta as tags
	   
	   //$('#painelTagsCorpo > button').on('click',function(){
	   	
		
		/*
		$('#painelTagsCorpo > button').click(function(){

	   		var cod_topico = <?php echo $cod_topico_pagina; ?>;

	   		var tag_name = $(this).attr('id').substring(6);

	   		$.ajax({



			url : 'http://www.p2wiki.com.br/categoria/deleteTags', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'tag_name='+tag_name+'&cod_topico='+cod_topico,



			success: function(response,status) {

				//console.log(response.retorno);

				if(response.retorno!='false')

				

	   			$(this).remove();

				return true;

			}

	   		});

		});
		*/

	});//Document.ready
		//deleta as tags	
		function deleteTags(tag_name){
	   		var cod_topico = <?php echo $cod_topico_pagina; ?>;

	   		var idLive = 'btnTag' + tag_name;


	   		$.ajax({

				url : 'http://www.p2wiki.com.br/categoria/deleteTags', 

				cache : false,

				async : false,

				type : 'post',

				dataType : 'json',

				data : 'tag_name='+tag_name+'&cod_topico='+cod_topico,

				success: function(response,status) {

					//console.log(response.retorno);

					if(response.retorno!='false'){

						$('#'+idLive).remove();

						return true;

					}

				}

		   	});
		}


		function deleteConteudo(cod_conteudo){

		    $.ajax({



				url : 'http://www.p2wiki.com.br/categoria/deleteConteudo', 



				cache : false,



				async : false,



				type : 'post',



				dataType : 'json',



				data : 'cod_conteudo='+cod_conteudo,



				success: function(response,status) {

					//console.log(response.retorno);

					if(response!='false')

					$('#conteudoLinha'+cod_conteudo).remove();	

					//alert('sucesso !');

					return true;

				}





			});

			

		}

		function insertConteudos(){

			var cod_topico = $('#add_cod_topico').val();

			var nome_conteudo = $('#add_nome').val();

			var ordem = $('#add_ordem').val();







		    $.ajax({



				url : 'http://www.p2wiki.com.br/categoria/insertConteudos', 



				cache : false,



				async : false,



				type : 'post',



				dataType : 'json',



				data : 'cod_topico='+cod_topico+'&nome_conteudo='+nome_conteudo+'&ordem='+ordem,



				success: function(response,status) {



					//console.log(response.retorno);

						

					if(response!='false'){

						

						//var topicoAdd = parseInt($("#tabelaTopicos").find('tr:last').attr('id').substring(11)) + 1;

						/*

						var linha =  '<tr id="conteudoLinha' + response + '">';

	                    linha    +=  '<td class="col-sm-1">' + response + '</td>';



	                    var linha = '<tr id="conteudoLinha' + response + '">';

	                    linha    +=  '<td class="col-sm-1">' + response + '</td>';

	                    linha    +=  '<td class="col-sm-1">' + cod_topico + '</td>';

	                    linha    +=  '<td>' + nome_conteudo + '</td>';

	                    linha    +=  '<td>' + ordem + '</td>';

	                    linha    +=  '<td class="col-sm-1 text-center"><span class="hidden-xs showopacity glyphicon glyphicon-pencil" style="cursor:pointer;font-size:16px;color:#333;"></span></td>';

	                    linha    +=  '<td class="col-sm-1 text-center"><span onClick="deleteConteudo(' + response + ')" class="hidden-xs showopacity glyphicon glyphicon-remove" style="cursor:pointer;font-size:16px;color:red;"></span></td>';

	                    linha    +=  '</tr>';

	                    //alert(linha);

	                    $('#tabelaTopicos > tbody').append(linha);

	                    */

	                    location.reload(); 

					}

					return true;

				}





			});

			

		}



		function insertTags(){

			var tag_name = $('#add_tag_name').val();

			if(tag_name!='')

			{

			    $.ajax({



					url : 'http://www.p2wiki.com.br/categoria/insertTags', 



					cache : false,



					async : false,



					type : 'post',



					dataType : 'json',



					data : 'tag_name='+tag_name+'&cod_topico='+<?php echo $cod_topico_pagina; ?>,



					success: function(response,status) {

						if(response=='true'){

							var botNovo = '<button type="button" class="btn btn-primary" style="margin-right: 0.5em; margin-bottom: 0.8em;" id="btnTag'+tag_name+'" onclick="deleteTags(\''+tag_name+'\')">';

							botNovo    += tag_name+'&nbsp;&nbsp;<span class="badge"><span aria-hidden="true" class="glyphicon glyphicon-remove text-center" style="font-size: 10px; margin-top: -20px; margin-right: -3px; margin-left: -3.4px;"></span></span></span>';

							botNovo    +=  '</button>';

							if($('#painelTagsCorpo > button:last').size()!=0){

		                   		$('#painelTagsCorpo > button:last').after(botNovo);

							}

							else{

								$('#painelTagsCorpo').html(botNovo);

							}

						}

						if(response=='repetido'){

							$('#alertTagRepetida').toggle('slow');

							setTimeout(function(){

								$('#alertTagRepetida').toggle('slow');

							},3000);		

						}

						return true;

					}

				});

			}

			else{

				$('#alertTagVazia').toggle('slow');

				setTimeout(function(){

					$('#alertTagVazia').toggle('slow');

				},3000);		

			}

		}



		function updateTopico(){

			$('#progressLoader').css('display','block');

			$('#porcentagemBarra').css('width','0%');

			var alt_cod_topico = $('#alt_cod_topico').val();

			var alt_nome = $('#alt_nome').val();

			var alt_link = $('#alt_link').val();

			var alt_categoria = $('#alt_categoria').val();

			var alt_publico = $('#alt_publico:checked' ).length;

			var alt_ordem = $('#alt_ordem').val();

			var alt_descricao = $('#alterarDescricao').val();



			$('#porcentagemBarra').css('width','15%');

			if(alt_link=='' || alt_link=='#')

			{

				alt_link = "http://www.p2wiki.com.br/conteudo/visualizar/" + alt_cod_topico;

				$('#alt_link').val(alt_link);

			}

			$('#porcentagemBarra').css('width','25%');

			 $.ajax({



				url : 'http://www.p2wiki.com.br/categoria/updateTopico', 



				cache : false,



				async : false,



				type : 'post',



				dataType : 'json',



				data : 'cod_topico='+alt_cod_topico+'&nome_topico='+alt_nome+'&link_topico='+alt_link+'&cod_categoria='+alt_categoria+'&ordem='+alt_ordem+'&descricao='+alt_descricao+'&publico='+alt_publico,



				success: function(response,status) {

					$('#porcentagemBarra').css('width','55%');

					$('#sucessoUpdate').html('O tópico ' + alt_nome + ' foi atualizado com sucesso !');		

					$('#sucessoUpdate').show();

					setTimeout(function(){

						$('#sucessoUpdate').html('');

						$('#sucessoUpdate').hide();

					},2500);		

				}





			});

			$('#porcentagemBarra').css('width','85%');

			setTimeout(function(){

				$('#porcentagemBarra').css('width','100%');

				$('#progressLoader').css('display','none');

				$('#porcentagemBarra').css('width','0%');

			},300);

		}

</script>

<body style="overflow-x:hidden !important;">

	<!-- Loader -->

	<div id="progressLoader" style="display:none;width: 100%; z-index: 1032 ! important; margin-top: -4em; background-color: rgb(51, 51, 51); position: fixed; height: calc(100% + 4em); opacity: 0.7;">

		<div style="position: relative; left: 13%; width: 75%; height: 50px; top: 43%;" class="progress">

			<span style="font-size: 1.3em; line-height: 2.6em; color: rgb(0, 0, 0); position: absolute; top: 0px; left: 41%;">

				Atualizando Tópico

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





	    <div id="painelTags" style="position: fixed; width: 19%; height: auto; right: 0.2em; top: 3.6em; border-radius: 8px;" class="panel panel-primary filterable">



		    <div id="divAddTags" class="panel panel-primary" style="border-bottom: medium none; border-left: medium none; border-right: medium none;">

				<div class="panel-heading">

	            	<h3 class="panel-title"><span style="color: white; margin-right: 10px;" class="hidden-xs showopacity glyphicon glyphicon-tags" onclick="insertConteudos()"></span>Adicionar Palavra Chave</h3>

	            </div>

				<table class="table">

	                <thead>

	                    <tr class="filters">		

			                <th class="col-sm-10"><input type="text" class="form-control" placeholder="Nome da palavra chave" disabled></th>

			                <th class="col-sm-1"></th>

		                	</tr>

	                </thead>

	                <tbody>

	                    <tr>		

			                <th><input type="text" class="form-control" placeholder="Nome" id="add_tag_name" ></th>

		                    <th class="text-center"><span onClick="insertTags()" class="hidden-xs showopacity glyphicon glyphicon-tags" style="cursor:pointer;font-size:25px;color:green;"></span></th>

	                	</tr>

	                </tbody>

	            </table>

	        </div>

	        <div id="alertTagRepetida" style="display:none" class="alert alert-danger" role="alert">Esta palavra chave já existe !</div>

	        <div id="alertTagVazia" style="display:none" class="alert alert-danger" role="alert">Esta palavra chave está vazia !</div>

			<div class="panel-heading">

				<h3 class="panel-title"><span style="color: white; margin-right: 10px;" class="hidden-xs showopacity glyphicon glyphicon-tags" onclick="insertConteudos()"></span>Palavras chaves:</h3>

			</div>	   

		    <div id="painelTagsCorpo" class="panel-body ">

		    	<?php

		    	if(isset($tags)){

		    		foreach($tags as $key=>$value)

		    		{	

		    			if(isset($tags[$key]['tag_name'])){
		    				if($tags[$key]['tag_name']!=''){

				    			echo '<button type="button" class="btn btn-primary" style="margin-right: 0.5em; margin-bottom: 0.8em;" id="btnTag'.$tags[$key]['tag_name'].'" onclick="deleteTags(\''.$tags[$key]['tag_name'].'\')">';

							    echo $tags[$key]['tag_name'].'&nbsp;&nbsp;<span class="badge"><span aria-hidden="true" class="glyphicon glyphicon-remove text-center" style="font-size: 10px; margin-top: -20px; margin-right: -3px; margin-left: -3.4px;"></span></span></span>';

								echo '</button>';
							}

						}

		    		}

		    		

		    	}

		    	

		    	?>

		    </div>

		</div>



	    <div class="container">

	        <div class="row row-offcanvas row-offcanvas-left">

	        

		        <div class="col-xs-12 col-sm-9" >

		        <div id="sucessoUpdate" class="alert alert-success " role="alert" style="display:none;"></div>

		        <?php 

					foreach($conteudos as $key=>$value){

						$conteudos[$key] = (array)$conteudos[$key];

					}

						

				?>

					<ul id="ulTabs" class="nav nav-pills">

					    <li role="presentation" class="quaseActive">

					    	<a href="http://www.p2wiki.com.br/categoria">Categorias</a>

					    </li>

					    <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;">

					     	<span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;">

					     	</span> 

					    </li>

					    <li role="presentation" class="quaseActive">

					    	<a href="http://www.p2wiki.com.br/categoria/topicos/<?php echo $topico['cod_categoria'];?>">Topicos</a>

					    </li>

					    <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;">

					    	<span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;">

					    	</span> 

					    </li>

					    <li role="presentation" class="active"><a href="">Conteúdos</a></li>

					</ul>
					<?php
						if(isset($topico['aprovado']) && $topico['aprovado'] == 'N'){
					?>
					<div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Alterar Tópico <?php echo $topico['nome_topico'];?> - ID:<?php echo $cod_topico_pagina; ?></h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th class="col-sm-1"><input type="text" class="form-control" placeholder="#" disabled></th>

				                    <th><input type="text" class="form-control" placeholder="Nome" disabled></th>

				                    <th><input type="text" class="form-control" placeholder="Link" disabled></th>

				                    <th class="col-sm-2"><input type="text" class="form-control" placeholder="Categoria" disabled></th>

				                    <th class="col-sm-1"><input type="text" class="form-control" placeholder="Público" disabled></th>

				                    <th class="col-sm-1"><input type="text" class="form-control" placeholder="Ordem" disabled></th>

				                    <th class="col-sm-1"></th>

				              	</tr>

			                </thead>

			                <tbody>

			                    <tr>		

					                <th><input type="text" class="form-control" id="alt_cod_topico" value="<?php echo $topico['cod_topico'];?>" readonly></th>

					                <th><input type="text" class="form-control" placeholder="Nome" value="<?php echo $topico['nome_topico'];?>" id="alt_nome" ></th>

				                    <th><input type="text" class="form-control" placeholder="Link"  value="<?php echo $topico['link_topico'];?>" id="alt_link" ></th>

				                    <th><input type="text" class="form-control" placeholder="Categoria" value="<?php echo $topico['cod_categoria'];?>"  id="alt_categoria" ></th>
				                    
				                    <th><input type="checkbox" class="form-control" <?php echo ($topico['publico']==1)?'checked="checked"':''; ?>  id="alt_publico" ></th>

				                    <th class="col-sm-1"><input type="text" class="form-control" placeholder="Ordem" value="<?php echo $topico['ordem'];?>"  id="alt_ordem" ></th>

				                    <th class="text-center"><span onClick="updateTopico(<?php echo $topico['cod_topico'];?>)" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" style="cursor:pointer;font-size:25px;color:green;"></span></th>

			                	</tr>

			                	<tr class="filters ">		

					                <th colspan="6">

					                	<input type="text" disabled="" class="form-control text-center" placeholder="Descrição"></th>

					            </tr>

			                	<tr class="filters ">		

					                <th colspan="6">

					                	<textarea style="resize:vertical;" id="alterarDescricao" class="form-control" rows="2"><?php echo $topico['descricao'];?></textarea>

					                </th>

					            </tr>

			                </tbody>

			            </table>

			        </div>

			        <div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Adicionar Conteúdo</h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th class="col-sm-1"><input type="text" class="form-control" placeholder="Tópico" disabled></th>

				                	<th><input type="text" class="form-control" placeholder="Nome do Conteúdo" disabled></th>

				                    <th class="col-sm-2"><input type="text" class="form-control" placeholder="Ordem" disabled></th>

				                    <th class="col-sm-1"></th>

 			                	</tr>

			                </thead>

			                <tbody>

			                    <tr>		

					                <th><input type="text" class="form-control" id="add_cod_topico" value="<?php echo $topico['cod_topico'];?>" readonly></th>

					                <th><input type="text" class="form-control" placeholder="Nome" id="add_nome" ></th>

				                    <th><input type="text" class="form-control" placeholder="Ordem" id="add_ordem" ></th>

				                    

				                    <th class="text-center"><span onClick="insertConteudos()" class="hidden-xs showopacity glyphicon glyphicon-floppy-open" style="cursor:pointer;font-size:25px;color:green;"></span></th>

			                	</tr>

			                </tbody>

			            </table>

		            </div>

		            <div class="panel panel-primary filterable">

			            <div class="panel-heading">

			                <h3 class="panel-title">Conteúdos do Tópico <?php echo $topico['nome_topico'];?></h3>

			                <div class="pull-right">

			                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

			                </div>

			            </div>

			            <table id="tabelaTopicos" class="table">

			                <thead>

			                    <tr class="filters">

			                        <th class="col-sm-1"><input type="text" class="form-control" placeholder="#" disabled></th>

			                        <th class="col-sm-1"><input type="text" class="form-control" placeholder="Tópico" disabled></th>

			                        <th><input type="text" class="form-control" placeholder="Nome" disabled></th>

			                        <th class="col-sm-1"><input type="text" class="form-control" placeholder="Ordem" disabled></th>

			                        <th class="col-sm-1"><input type="text" class="form-control text-center" disabled id="editTopic" placeholder="Editar"></th>

			                        <th class="col-sm-1"><input type="text" class="form-control text-center" disabled id="inpTopic" placeholder="Deletar"></th>

			                    </tr>

			                </thead>

			                <tbody>

			                <?php

			                	foreach($conteudos as $key=>$value)

								{

									if(isset($conteudos[$key]['cod_topico']))

									{

					                   echo '<tr id="conteudoLinha'.$conteudos[$key]['cod_conteudo'].'">';

					                   echo    '<td class="col-sm-1">'.$conteudos[$key]['cod_conteudo'].'</td>';

					                   echo    '<td class="col-sm-1">'.$conteudos[$key]['cod_topico'].'</td>';

					                   echo    '<td>'.$conteudos[$key]['nome_conteudo'].'</td>';

					                   echo    '<td>'.$conteudos[$key]['ordem'].'</td>';

					                   echo    '<td class="col-sm-1 text-center"><a href="http://www.p2wiki.com.br/categoria/conteudo/'.$conteudos[$key]['cod_conteudo'].'"> <span class="hidden-xs showopacity glyphicon glyphicon-pencil" style="cursor:pointer;font-size:16px;color:#333;"></span></td></a>';

					                   echo    '<td class="col-sm-1 text-center"><span onClick="deleteConteudo('.$conteudos[$key]['cod_conteudo'].')" class="hidden-xs showopacity glyphicon glyphicon-remove" style="cursor:pointer;font-size:16px;color:red;"></span></td>';

					                   echo '</tr>';

					                   

					                }

				                }

			                ?>

		           			</tbody>

			            </table>

			        </div>
			     <?php  
			 		}
			     	else{
			     		echo '<h1 style="text-align:center;padding:20px;">Tópico foi aprovado e não pode ser alterado. <br> Caso queira alterá-lo entre em contato com o administrador do grupo criador deste tópico.</h1>';
			     	}
			     ?>   
				  

		        </div><!-- /.col-xs-12 main -->



	  		</div>

		</div>

	</div>	

	 

	 	        

    <script>

    	  $('#cod_categoria').val('<?php echo $cod_topico_pagina; ?>');

    </script>





  



