 <!-- ckeditor -->

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
	function referenciar(){
		var cod_topico = $('#add_referencia_select').val();
		var cod_conteudo = $('#cod_conteudo').val();
		$.ajax({
			url : '<?php echo site_url(); ?>categoria/addReferencia', 
			cache : false,
			async : false,
			type : 'post',
			dataType : 'json',
			data : 'cod_topico='+cod_topico+'&cod_conteudo='+cod_conteudo,

			success: function(response,status) {
				if(response.status != 'false'){
					$('#sucessoUpdate').html('Referencia feita com sucesso !');		
					$('#sucessoUpdate').show();
					$('#table_referencias').append('<tr><th colspan="2">' +cod_topico +' - <a href="' +response.link_topico +'" target="_blank" >' +response.nome_topico +'</a></th></tr>');
					setTimeout(function(){
						$('#sucessoUpdate').html('');
						$('#sucessoUpdate').hide();
					},2500);
				}
			}
		});
	}
	function updateConteudo(){
		var cod_conteudo = $('#cod_conteudo').val();
		var nome_conteudo = $('#nome_conteudo').val();
		var ordem = $('#ordem').val();
		$.ajax({
			url : '<?php echo site_url(); ?>categoria/updateConteudo', 
			cache : false,
			async : false,
			type : 'post',
			dataType : 'json',
			data : 'cod_conteudo='+cod_conteudo+'&nome_conteudo='+nome_conteudo+'&ordem='+ordem,

			success: function(response,status) {
				if(response != 'false'){
					$('#sucessoUpdate').html('O conteúdo ' + nome_conteudo + ' foi atualizado com sucesso !');		
					$('#sucessoUpdate').show();
					setTimeout(function(){
						$('#sucessoUpdate').html('');
						$('#sucessoUpdate').hide();
					},2500);
				}
			}
		});
	}

	$(document).ready(function(){

		$('#porcentagemBarra').css('width','15%');

		var i = 25;

		var carregaBarra = setInterval(function()

		{

			if(i<100)

			{

				$('#porcentagemBarra').css('width',i+'%');

				i+=10;

			}	

			else

			{

				$('#porcentagemBarra').css('width','100%');

				$('#progressLoader').css('display','none');

				clearInterval(carregaBarra);

			}

		},200);

		

		

	});

</script>

<body style="overflow-x:hidden !important;">

	<!-- Loader -->

	<div id="progressLoader" style="width: 100%; z-index: 1032 ! important; margin-top: -4em; background-color: rgb(51, 51, 51); position: fixed; height: calc(100% + 4em); opacity: 0.7;">

		<div style="position: relative; left: 13%; width: 75%; height: 50px; top: 43%;" class="progress">

			<span style="font-size: 1.3em; line-height: 2.6em; color: rgb(0, 0, 0); position: absolute; top: 0px; left: 41%;">

				Carregando CK Editor

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

	         		

		        <div class="col-xs-12 col-sm-10" > 

		        <ul id="ulTabs" class="nav nav-pills">

				    <li role="presentation" class="quaseActive"><a href="http://www.p2wiki.com.br/categoria">Categorias</a></li>

				     <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;"></span> </li>

				    <li role="presentation" class="quaseActive"><a href="http://www.p2wiki.com.br/categoria/topicos/<?=$cod_categoria_topico['cod_categoria']?>">Tópicos</a></li>

				     <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;"></span> </li>

				    <li role="presentation" class="quaseActive"><a href="http://www.p2wiki.com.br/categoria/editar_topico/<?=$conteudo['cod_topico']?>">Conteúdos</a></li>

				     <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;"></span> </li>

				    <li role="presentation" class="active"><a href="">Editar Conteúdo</a></li>

				</ul>

		        <div id="sucessoUpdate" class="alert alert-success " role="alert" style="display:none;"></div>

		       		<div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Alterar Conteúdo <?=$conteudo['nome_conteudo']?> - ID: <?=$conteudo['cod_conteudo']?></h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th class="col-sm-1"><input type="text" class="form-control" placeholder="ID" disabled></th>

				                    <th><input type="text" class="form-control" placeholder="Nome do conteúdo" disabled></th>

				                    <th class="col-sm-1"><input type="text" class="form-control" placeholder="Ordem" disabled></th>

				                    <th class="col-sm-1"></th>

				              	</tr>

			                </thead>

			                <tbody>

			                    <tr>		

					                <th><input type="text" class="form-control" id="cod_conteudo" value="<?=$conteudo['cod_conteudo']?>" readonly></th>

					                <th><input type="text" class="form-control" placeholder="Nome do conteúdo" value="<?=$conteudo['nome_conteudo']?>" id="nome_conteudo" ></th>

				                    <th><input type="text" class="form-control" placeholder="Link"  value="<?=$conteudo['ordem']?>" id="ordem" ></th>

				                    <th class="text-center"><span onClick="updateConteudo()" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" style="cursor:pointer;font-size:25px;color:green;"></span></th>

			                	</tr>

			                </tbody>

			            </table>

			        </div>
			        <div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Referenciar tópico</h3>

			            </div>

						<table id="table_referencias" class="table">

			                

			                <tbody>

			                    <tr>		



				                    <th>
				                    	<select type="text" class="form-control" placeholder="Tópico" id="add_referencia_select" >
				                    		<option>ID - Nome tópico</option>
				                    		<?php
				                    			foreach ($topicos_disponiveis as $key => $value) {
				                    				echo "<option value=".$key.">".$key." - ".$value."</option>";
				                    			}
				                    		?>
				                    	</select>
				                    </th>

				                    <th class="text-center"><button onClick="referenciar()" class="btn btn-default form-control" style="">Referenciar</button></th>

			                	</tr>
			                	<?php
				                	if(count($conteudo_referencias)>0){
				                		echo '<tr><th colspan="2" style="text-align:center;">Referencias já feitas:</th></tr>';
				                		foreach ($conteudo_referencias as $key => $value) {
				                			echo '<tr><th colspan="2">'.$value->cod_topico.' - <a href="'.$value->link_topico.'" target="_blank" >'.$value->nome_topico.'</a></th></tr>';
				                		}
				                	}
			                	?>

			                </tbody>

			            </table>

		            </div>

			        <form method="POST" action="http://www.p2wiki.com.br/categoria/updateConteudoCKEDITOR">

				        <div class="panel panel-primary filterable">

							<div class="panel-heading">

				                <h3 class="panel-title">Conteúdo</h3>

				            </div>

							<table class="table">

				                <thead>

				                    <tr class="filters">		

					                	

					              	</tr>

				                </thead>

				                <tbody>

				                    <tr>

				                    	<input type="hidden" value="<?=$conteudo['cod_conteudo']?>" name="cod_conteudo" />		

					                    <textarea name="conteudo" id="editor1" rows="10" cols="80">

							            	<?=$conteudo['conteudo']?>  

							            </textarea>

							            <script>

							                // Replace the <textarea id="editor1"> with a CKEditor

							                // instance, using default configuration.

							                CKEDITOR.replace( 'conteudo' );

							            </script>

								    </tr>

								    <tr>

								    	 <button type="submit" class="btn btn-default panel-title alert-success" style="margin: 1%; width: 98%;">Salvar</button>

								    </tr>

				                </tbody>

				            </table>

				        </div>

			        </form>

				 </div><!-- /.col-xs-12 main -->

			</div>

		</div>

	</div>	

    <script>

    	  

    </script>





  



