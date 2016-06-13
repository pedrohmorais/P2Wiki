<?php
	$categorias_base = (array)$categorias_base;

	foreach($categorias_base as $key=>$value){
		$categorias_base[$key] = (array)$categorias_base[$key];
	}
?>
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

		$('.bs-glyphicons-list li').click(function(){

		    var valorGlyp = $(this).find('span:last').html().substring(10);

		    $('#glyphicon').val(valorGlyp);

		});

	   

	});



	function togleGlypClick(){

		$('.bs-glyphicons').css('display','none');

		$('.bs-glyphicons').slideToggle();

	}

	function togleGlypBlur(){

		$('.bs-glyphicons').css('display','block');

		$('.bs-glyphicons').slideToggle();

	}



	function updateCategoria(){

		var cod_categoria = $('#cod_categoria').val();

		var nome_categoria = $('#nome_categoria').val();

		var categoria_pai = $('#categoria_pai').val();

		var glyphicon = $('#glyphicon').val();

	    $.ajax({



			url : 'http://www.p2wiki.com.br/categoria/updateCategoria', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'cod_categoria='+cod_categoria+'&nome_categoria='+nome_categoria+'&categoria_pai='+categoria_pai+'&glyphicon='+glyphicon+'&publico='+$('#publico:checked' ).length,



			success: function(response,status) {

				$('#sucessoUpdate').html('A categoria ' + nome_categoria + ' foi atualizada com sucesso !');		

				$('#sucessoUpdate').show();

				setTimeout(function(){

					$('#sucessoUpdate').html('');

					$('#sucessoUpdate').hide();

				},2500);		

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

			<!-- <div class="col-xs-12 col-sm-12"  style="margin-top: -6%;"> -->

		    <div class="col-xs-12 col-sm-10" >

		    <ul id="ulTabs" class="nav nav-pills">

			    <li role="presentation" class="quaseActive"><a href="http://www.p2wiki.com.br/categoria">Categorias</a></li>

			    <li role="presentation" class="quaseActive" style="padding-top: 0.8em; width: 1.8em;"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true" style="font-size: 1.1em;"></span> </li>

			    <li role="presentation" class="active"><a href="">Editar Categoria</a></li>

			</ul>

		        <div id="sucessoUpdate" class="alert alert-success" role="alert" style="display:none;"></div>

		        	<div class="panel panel-primary filterable">

						<div class="panel-heading">

			                <h3 class="panel-title">Editar Categoria</h3>

			            </div>

						<table class="table">

			                <thead>

			                    <tr class="filters">		

				                	<th style="width:10%;"><input type="text" class="form-control" placeholder="#" disabled></th>

				                    <th style="width:43%;"><input type="text" class="form-control" placeholder="Nome" disabled></th>

				                    <th style="width:14%;"><input type="text" class="form-control" placeholder="Categoria Pai" disabled></th>

				                    <th style="width:20%;"><input type="text" class="form-control" placeholder="Ícone" disabled></th>

				                    <th style="width:8%;"><input type="text" class="form-control" placeholder="Público" disabled></th>


				              	</tr>

			                </thead>

			                <tbody>

			                    <tr>		

				                	<th><input type="text" class="form-control" id="cod_categoria" value="<?=$categoria['cod_categoria']?>" readonly></th>

				                    <th><input type="text" class="form-control" placeholder="Nome" id="nome_categoria" value="<?=$categoria['nome_categoria']?>"></th>

				                    <th>
					                    <select id='categoria_pai' class="form-control">
					                    	<option value='' selected>Categoria Pai</option>
					                    	<?php 
					                    	foreach ($categorias_base as $cat) {
					                    		if($categoria['cod_categoria'] != $cat['cod_categoria']){
						                    		if($categoria['categoria_pai'] == $cat['cod_categoria']){
						                    			echo 	'<option value="'.$cat['cod_categoria'].'" selected >'.$cat['cod_categoria'].'</option>';		
						                    		}
						                    		else{
						                    			echo 	'<option value="'.$cat['cod_categoria'].'" >'.$cat['cod_categoria'].'</option>';	
						                    		}
						                    	}
					                    	}
					                    	?>
					                    </select>
				                    </th>

				                    <th><input type="text" class="form-control" placeholder="Ícone" id="glyphicon" value="<?=$categoria['glyphicon']?>" onClick="togleGlypClick();" onBlur="togleGlypBlur();"></th>
				                    
				                    <th><input type="checkbox" id="publico" class="form-control"></th>


				                    <th><span onClick="updateCategoria(<?=$categoria['cod_categoria']?>)" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" style="cursor:pointer;font-size:25px;color:green;"></span></th>

			                	</tr>

			                </tbody>



			            </table>

		            </div>

		        <!-- lista de glyphicon -->

	        	<?php $this->load->view('templates/main/listaglyphicon'); ?>

		        </div><!-- /.col-xs-12 main -->

		    </div>

		</div>



	





</body>

<script>
	<?php
		if($categoria['publico']==1){
			echo "$('#publico' ).prop('checked', true);";
		}
	?>
	  $('#cod_categoria').val('<?=$cod_categoria_pagina?>');

</script>





  



