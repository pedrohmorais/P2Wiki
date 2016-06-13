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

#tabelaCategorias tr:hover{

	background-color:#CCDDED;

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

		/* fancybox

		$("a[rel=modal]").fancybox({

			'onComplete' : function() {$("#fancybox-wrap").unbind('mousewheel.fb');} ,

			'transitionIn'		: 'none',

			'transitionOut'		: 'none',

			'titlePosition' 	: 'inside',

			'width':900,

			'height':510,

			'type':'iframe',

			'scrolling':'no',

			'showNavArrows':false

		});

		$('.iframe').click(function(){

			setTimeout(function(){

				$("#fancybox-content").attr('style','border-color: #333;border-width: 10px;height: 510px;width: 100%;');

			});

		});

		*/

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

	//before .ready

	function deleteCategoria(cod_categoria){

	    $.ajax({



			url : 'http://www.p2wiki.com.br/categoria/deleteCategoria', 



			cache : false,



			async : false,



			type : 'post',



			dataType : 'json',



			data : 'cod_categoria='+cod_categoria,



			success: function(response,status) {



				//console.log(response.retorno);

				$('#categoriaLinha'+cod_categoria).remove();	

			//alert('sucesso !');

			return true;

			}





		});

		

	}

	function insertCategorias(){

		$('#progressLoader').css('display','block');

		$('#porcentagemBarra').css('width','15%');

		var nome_categoria = $('#nome_categoria').val();

		var categoria_pai = $('#categoria_pai').val();

		var glyphicon = $('#glyphicon').val();

		var user_group = $('#group_id').val();

		var publico = $('#publico:checked').length;

		if (user_group < 0 || user_group == '') {
			alert('Código de grupo inválido !');
		}
		else{
		    $.ajax({



				url : 'http://www.p2wiki.com.br/categoria/insertCategorias', 



				cache : false,



				async : false,



				type : 'post',



				dataType : 'json',



				data : 'nome_categoria='+nome_categoria+'&categoria_pai='+categoria_pai+'&glyphicon='+glyphicon+'&user_group='+user_group+'&publico='+publico,



				success: function(response,status) {



					//console.log(response.retorno);

						

					if(response!='false'){

						$('#porcentagemBarra').css('width','45%');

						//var topicoAdd = parseInt($("#tabelaTopicos").find('tr:last').attr('id').substring(11)) + 1;

						var linha =  '<tr id="categoriaLinha' + response + '">';

	                    linha    +=  '<td class="col-sm-1">' + response + '</td>';

	                    linha    +=  '<td>' + nome_categoria + '</td>';

	                    linha    +=  '<td>' + categoria_pai + '</td>';

	                    linha    +=  '<td><span class="hidden-xs showopacity glyphicon ' + glyphicon + ' style="font-size:16px;margin-right:5px;"></span>' + glyphicon + '</td>';

	                    linha    +=     '<td class="col-sm-1 text-center"><a class="iframe" rel="modal"  style="color: rgb(51, 51, 51);" href="http://www.p2wiki.com.br/categoria/editar_categoria/'+ response + '"><span class=" hidden-xs showopacity glyphicon glyphicon-pencil" style="font-size:16px;"></span></a></td>';

	                    linha    +=     '<td class="col-sm-1 text-center"><a class="iframe" rel="modal"  style="color: rgb(51, 51, 51);" href="http://www.p2wiki.com.br/categoria/topicos/'+ response + '"><span class=" hidden-xs showopacity glyphicon glyphicon-th-list" style="font-size:16px;"></span></a></td>';

	                    linha    +=     '<td class="col-sm-1 text-center"><span onClick="deleteCategoria('+ response + ')" class="hidden-xs showopacity glyphicon glyphicon-remove" style="cursor:pointer;font-size:16px;color:red;"></span></td>';

	                    linha    +=  '</tr>';

	                    //alert(linha);

	                  

	                  

	                    $('#tabelaCategorias > tbody').append(linha);

	                    $('#categoria_pai').append('<option value="'+response+'">'+response+'</option>');

					}

					return true;

				}

			});
		}
		$('#porcentagemBarra').css('width','85%');

			setTimeout(function(){

				$('#porcentagemBarra').css('width','100%');

				$('#progressLoader').css('display','none');

				$('#porcentagemBarra').css('width','0%');

			},300);

		

	}

</script>

</head>



<body style="overflow-x:hidden !important;">

	<!-- Loader -->

	<div id="progressLoader" style="display:none;width: 100%; z-index: 1032 ! important; margin-top: -4em; background-color: rgb(51, 51, 51); position: fixed; height: calc(100% + 4em); opacity: 0.7;">

		<div style="position: relative; left: 13%; width: 75%; height: 50px; top: 43%;" class="progress">

			<span style="font-size: 1.3em; line-height: 2.6em; color: rgb(0, 0, 0); position: absolute; top: 0px; left: 41%;">

				Adicionando categoria

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



		<!-- top navbar -->

			   		

	    <div class="container">



	      <div class="row row-offcanvas row-offcanvas-left">



	        



	        



	  	



	        <!-- main area -->

	        	

	        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">

	        <ul id="ulTabs" class="nav nav-pills">

			    <li role="presentation" class="active"><a href="http://www.p2wiki.com.br/categoria">Categorias</a></li>

			    

			</ul>

	        <?php 

				$menu = $this->session->userdata('menu');



				$user_id = $this->session->userdata('sg_user_id');

				$groups = $this->session->userdata('sg_user_groups');

				

				//$categorias_base =  $menu['categorias_base'];



				$categorias_base = (array)$categorias_base;

				

				foreach($categorias_base as $key=>$value){

					$categorias_base[$key] = (array)$categorias_base[$key];

				}

				//die(print_r($categorias_base));

							

			?>

			<div class="panel panel-primary filterable">

				<div class="panel-heading">

	                <h3 class="panel-title">Adicionar Categoria</h3>

	            </div>

				<table class="table">

	                <thead>

	                    <tr class="filters">		

		                	<th><input type="text" class="form-control" placeholder="Nome" id="nome_categoria"></th>

		                    <th class="col-sm-2">
		                    	<select id='categoria_pai' class="form-control">
			                    	<option value='' selected>Categoria Pai</option>
			                    	<?php 
			                    	foreach ($categorias_base as $cat) {
			                    		echo 	'<option value="'.$cat['cod_categoria'].'">'.$cat['cod_categoria'].'</option>';		
			                    	}
			                    	?>
			                    </select>
		                    </th>

		                    <th class="col-sm-2">
		                    	<select class="form-control" id="glyphicon">
		                    		<option value='' selected>Ícone</option>
		                    		<?php $this->load->view('templates/main/selectglyphicon'); ?>
		                    	</select>
		                    </th>

		                    <th>
			                    <select id='group_id' class="form-control">
			                    	<option value='' selected>Id Grupo</option>
			                    	<?php 
			                    	foreach ($groups as $group) {
			                    		echo 	'<option value="'.$group.'">'.$group.'</option>';		
			                    	}
			                    	?>
			                    </select>	
							</th>

		                    <th>
			                    <label class="">
									<input type="checkbox" checked id="publico" > Público
								</label>
							</th>

		                    <th><span onClick="insertCategorias()" class="hidden-xs showopacity glyphicon glyphicon-floppy-saved" style="cursor:pointer;font-size:25px;color:green;"></span></th>

	                	</tr>

	                </thead>



	            </table>

            </div>

	            <div class="panel panel-primary filterable">

		            <div class="panel-heading">

		                <h3 class="panel-title">Categorias</h3>

		                <div class="pull-right">

		                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

		                </div>

		            </div>

		            <table id="tabelaCategorias"class="table">

		                <thead>

		                    <tr class="filters">

		                       	 	 	 

		                        <th><input type="text" class="form-control" placeholder="#" disabled></th>

		                        <th><input type="text" class="form-control" placeholder="Nome" disabled></th>

		                        <th><input type="text" class="form-control" placeholder="Categoria Pai" disabled></th>

		                        <th><input type="text" class="form-control" placeholder="Glyphicon" disabled></th>

		                        <th><input type="text" class="form-control text-center" disabled id="editCategoria" placeholder="Editar"></th>

		                        <th><input type="text" class="form-control text-center" disabled id="inpTopic" placeholder="Tópicos"></th>

		                        <th><input type="text" class="form-control text-center" disabled id="deleteSpan" placeholder="Deletar"></th>



		                    </tr>

		                </thead>

		                <tbody>

		                <?php
		                	if(count($categorias_base > 0)){
			                	foreach($categorias_base as $key=>$value)
								{

				                   echo '<tr id = "categoriaLinha'.$categorias_base[$key]['cod_categoria'].'">';

				                   echo    '<td class="col-sm-1">'.$categorias_base[$key]['cod_categoria'].'</td>';

				                   echo    '<td>'.$categorias_base[$key]['nome_categoria'].'</td>';

				                   echo    '<td>'.$categorias_base[$key]['categoria_pai'].'</td>';

				                   echo    '<td><span class="hidden-xs showopacity glyphicon '.$categorias_base[$key]['glyphicon'].'" style="font-size:16px;margin-right:5px;"></span>'.$categorias_base[$key]['glyphicon'].'</td>';

				                   echo    '<td class="col-sm-1 text-center"><a class="iframe" rel="modal"  style="color: rgb(51, 51, 51);" href="http://www.p2wiki.com.br/categoria/editar_categoria/'.$categorias_base[$key]['cod_categoria'].'"><span class=" hidden-xs showopacity glyphicon glyphicon-pencil" style="font-size:16px;"></span></a></td>';

				                   echo    '<td class="col-sm-1 text-center"><a class="iframe" rel="modal"  style="color: rgb(51, 51, 51);" href="http://www.p2wiki.com.br/categoria/topicos/'.$categorias_base[$key]['cod_categoria'].'"><span class=" hidden-xs showopacity glyphicon glyphicon-th-list" style="font-size:16px;"></span></a></td>';

				                   echo    '<td class="col-sm-1 text-center"><span onClick="deleteCategoria('.$categorias_base[$key]['cod_categoria'].')" class="hidden-xs showopacity glyphicon glyphicon-remove" style="cursor:pointer;font-size:16px;color:red;"></span></td>';

				                   echo '</tr>';

				                }
				            }
				            else{
				            	echo    '<tr>';

			                    echo    '<td colspan="7">Você não possui nenhuma categoria.</td>';

			                    echo    '</tr>';
				            }    
		                ?>

	           			</tbody>

		            </table>

		        </div>

			    



	        </div><!-- /.col-xs-12 main -->



	    </div><!--/.row-->



	  </div><!--/.container-->



	</div><!--/.page-container-->



	<!-- script references -->

	



</body>



	<?php $this->load->view('templates/main/footer'); ?>



	<?php $this->load->view('templates/main/scripts'); ?>



</html>