 <!-- ckeditor --> 

 <?	

 $urlCompleta = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

 $urlSemGet = 'http://'.$_SERVER['HTTP_HOST'];



 //$data_user = $this->session->userdata('data_user');$data_user = $data_user[0];	

 $data_user = $this->session->userdata('data_user');$data_user = $data_user[0];	

 //die(print_r($data_user)); 

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

	

</script>

<body style="overflow-x:hidden !important;">

	<div class="page-container">

	 		<!-- menu -->

	        <?php $this->load->view('templates/main/menu'); ?>

	    <div class="container">

	        <div class="row row-offcanvas row-offcanvas-left">

		        <div class="col-xs-10 col-sm-9" style="padding:25px;">

		        <div class="btn-group" style="float: right; margin-top: -2%;">

					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

						Resultados por página <span class="caret"></span>

					</button>

				<ul class="dropdown-menu" role="menu">

					<li>

						<a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=0&itens=5">5</a>

					</li>

					<li>

						<a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=0&itens=10">10</a>

					</li>

					<li>

						<a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=0&itens=15">15</a>

					</li>

					<li>

						<a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=0&itens=20">20</a>

					</li>

					<li>

						<a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=0&itens=30">30</a>

					</li>

				</ul>

				</div>

	       			<h4 class="list-group-item-heading">Procurando resultados para '<?=$pesquisa?>':</h4>				

		        	<div class="list-group">

		        	<?php

		        		if(count($conteudos)>0 && $conteudos!=''){

		        			foreach($conteudos as $key=>$value){

						 		echo '<a href="'.$conteudos[$key]['link_topico'].'" class="list-group-item">';

						        echo '<h4 class="list-group-item-heading">'.$conteudos[$key]['nome_topico'].'</h4>';

						        echo '<p class="list-group-item-text">'.$conteudos[$key]['descricao'].'</p>';

						        echo '</a>';

      						}

		        		}

					    else{

						    echo '<h4 class="list-group-item-heading">Nenhum resultado encontrado !</h4>';

					    }

					?>

					 </div>

					<nav>

					    <ul class="pagination">

					        <li id="primeiraPaginacao" >

						        <a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=<?=($paginaAtual-1)?>&itens=<?=$qtdItensPage?>" aria-label="Previous">

						            <span aria-hidden="true">&laquo;</span>

						        </a>

					        </li>

					        <?php

					        	for($i = 0;$i<$qtdPaginas;$i++){

						       		echo '<li id="paginacaoLi'.$i.'"><a href="'.$urlSemGet.'/search?q='.$pesquisa.'&page='.$i.'&itens='.$qtdItensPage.'">'.($i+1).'</a></li>';

					        	}
					        	if($qtdPaginas > 1 && $qtdPaginas != ($paginaAtual+1)){
						    ?>

					        <li id="ultimaPaginacao">

					       		<a href="<?=$urlSemGet?>/search?q=<?=$pesquisa?>&page=<?=($paginaAtual+1)?>&itens=<?=$qtdItensPage?>" aria-label="Next">

					        		<span aria-hidden="true">&raquo;</span>

					        	</a>

					        </li>
					        <?php } ?>
					    </ul>

					</nav>

				</div><!-- /.col-xs-12 main -->

			</div>

		</div>

	</div>	

    <script>

    	//coloca o action da paginação no lugar certo !

    	$('#paginacaoLi<?=$paginaAtual?>').addClass('active');

    	if($('#paginacaoLi0').attr('class')!=undefined){

    		$('#primeiraPaginacao').css('display','none');

    	}

    	if($('#paginacaoLi<?=$qtdPaginas?>').attr('class')!=undefined){

    		$('#ultimaPaginacao').css('display','none');

    	}

    	//faz dropdown

    	$('.btn-group').click(function(){

			$('.dropdown-menu').slideToggle();

		});

    </script>





  



