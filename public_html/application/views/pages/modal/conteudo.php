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

</style>	

<script>

	function updateConteudo(){

		var cod_conteudo = $('#cod_conteudo').val();

		var nome_conteudo = $('#nome_conteudo').val();

		var ordem = $('#ordem').val();

		

		$.ajax({



			url : 'http://www.p2wiki.com.br/categoria/updateConteudo', 



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

</script>

<body style="overflow-x:hidden !important;">

	<div class="page-container">

	 		<!-- menu -->

	        <?php $this->load->view('templates/main/menu'); ?>

	    <div class="container">

	        <div class="row row-offcanvas row-offcanvas-left">

	        

		        <div class="col-xs-12 col-sm-9" >

		        <div id="sucessoUpdate" class="alert alert-success " role="alert" style="display:none;"></div>

		       		<div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Alterar Conteúdo <?=$conteudo['nome_conteudo']?> - ID: <?=$conteudo['cod_conteudo']?></h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th class="col-sm-1"><input type="text" class="form-control" placeholder="#" disabled></th>

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

							                This is my textarea to be replaced with CKEditor.

							            </textarea>

							            <script>

							                // Replace the <textarea id="editor1"> with a CKEditor

							                // instance, using default configuration.

							                CKEDITOR.replace( 'conteudo' );

							            </script>

								    </tr>

								    <tr>

								    	 <button type="submit" class="btn btn-default panel-title" style="margin: 1%; width: 98%;">Salvar</button>

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





  



