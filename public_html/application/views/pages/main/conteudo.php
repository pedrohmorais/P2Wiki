<script>

	//Deleta as tags

   $('#painelTagsCorpo > button').click(function(){

   		$(this).remove();

   });
   $(document).ready(function(){
   		//arruma o painel de tags e topicos relacionados
	   	$('#painelTags').mouseenter(function(e){
		   	$(this).css('right','-10px');
		   	$(this).find('.panel-heading').attr('style','');
		   	$('#painelTagsCorpo').attr('style','');
		   	$('#painelRelacionados').css('top','calc(7.7em + '+($('#painelTagsCorpo').height()+30)+'px)');
	   	});
	  	$('#painelTags').mouseleave(function(e){
		   	$(this).css('right','calc(-19% + 40px)');
		   	$(this).find('.panel-heading').attr('style','border-radius:5px;');
		   	$('#painelTagsCorpo').css('display','none');
		   	$('#painelRelacionados').css('top','7.7em');

	   	});
		$('#painelRelacionados').mouseenter(function(e){
		   	$(this).css('right','-10px');
		   	$(this).find('.panel-heading').attr('style','');
		   	$('#painelRelacionadosCorpo').attr('style','');
			//$('#painelRelacionados').css('top','calc(7.7em + '+($('#painelTagsCorpo').height()+30)+'px)');
		});
		   $('#painelRelacionados').mouseleave(function(e){
		   	$(this).css('right','calc(-19% + 40px)');
		   	$(this).find('.panel-heading').attr('style','border-radius:5px;');
		   	$('#painelRelacionadosCorpo').css('display','none');
			//$('#painelRelacionadosCorpo').css('top','7.7em');
		})
   });

</script>	

</head>


	<div style="position: fixed; width: 100%; height: 100%; background: transparent url('<?php echo site_url(); ?>images/binary.jpg') no-repeat scroll 0px -350px; z-index: -1; opacity: 0.4;"></div>
	<body style="overflow-x: hidden; background: transparent none repeat scroll 0% 0%;">



		



		<div class="page-container">

		<!-- menu -->



        <?php $this->load->view('templates/main/menu'); ?>

        <?php 

        	//die(print_r($tags));

	        if(isset($cod_topico)){

			    if($cod_topico!=''){

		?>

			        <div id="painelTags" style="z-index:1;position: fixed; width: 19%; height: auto; right: calc(-19% + 40px); top: 4.7em; border-radius: 8px;" class="panel panel-primary filterable">
					<div class="panel-heading" style="border-radius:5px;">
							<h3 class="panel-title"><span style="color: white; margin-right: 10px;" class="hidden-xs showopacity glyphicon glyphicon-pushpin" onclick="insertConteudos()"></span>Palavras chave:</h3>
						</div>	   
					    <div id="painelTagsCorpo" class="panel-body " style="display:none;">
					    	<?php
					    	if(isset($tags)){
					    		foreach($tags as $key=>$value)
					    		{	
					    			if(isset($tags[$key]['tag_name'])){
					    				if($tags[$key]['tag_name'] !=''){
							    			echo '<button type="button" class="btn btn-primary" style="margin-right: 0.5em; margin-bottom: 0.8em;" id="btnTag'.$tags[$key]['tag_name'].'">';
										    echo $tags[$key]['tag_name'].'&nbsp;&nbsp;<span class="badge"><span aria-hidden="true" class="glyphicon glyphicon-remove text-center" style="font-size: 10px; margin-top: -20px; margin-right: -3px; margin-left: -3.4px;"></span></span></span>';
											echo '</button>';
					    				}	
									}
					    		}
					    	}
					    	?>
					    </div>
					</div>
					<div id="painelRelacionados" style="z-index:1;position: fixed; width: 19%; height: auto; right: calc(-19% + 40px); top: 7.7em; border-radius: 8px;" class="panel panel-primary filterable">
					<div class="panel-heading" style="border-radius:5px;">
							<h3 class="panel-title"><span style="color: white; margin-right: 10px;" class="hidden-xs showopacity glyphicon glyphicon-paperclip" ></span>Tópicos relacionados:</h3>
						</div>	   
					    <div id="painelRelacionadosCorpo" class="panel-body " style="display:none;">
					    	<?php
					    	if(isset($topicosRelacionados) && count($topicosRelacionados)>0 && is_array($topicosRelacionados) ){
					    		foreach($topicosRelacionados as $key=>$value)
					    		{	
					    			if(isset($topicosRelacionados[$key]->cod_topico)){
					    				if($topicosRelacionados[$key]->cod_topico !=''){
							    			echo '<a target="_blank" href="'.$topicosRelacionados[$key]->link_topico.'"><button type="button" class="btn btn-primary" style="margin-right: 0.5em; margin-bottom: 0.8em;" >';
										    echo $topicosRelacionados[$key]->cod_topico.' - '.$topicosRelacionados[$key]->nome_topico;
											echo '</button></a>';
					    				}	
									}
					    		}
					    	}
					    	?>
					    </div>
					</div>
		<?php 

	        	}

	        }

		?>



		    <div class="container">



		      <div class="row row-offcanvas row-offcanvas-left">



		        <!-- main area -->



		        <div class="col-xs-10 col-sm-9 col-md-9 col-lg-10" style="background-color: transparent;">



		        <?php

		        if(isset($cod_topico)){

		           	if($cod_topico==''){

		        ?>

		        	<head>
						<link rel="stylesheet" type="text/css" href="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/style.css" />
						<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/jquery.js"></script>
					</head>
					<body style="background-color:#d7d7d7;margin:auto">
						<div style="text-align: center; opacity: 0.4; position: fixed; left: 50%; transform: translateX(-50%);"><img src="/images/LogoCursive.png"></div>
						<!--
						<div id="wowslider-container1" style="height:480px;background-color:transparent;">
							<div class="ws_images"><ul>
								<li><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/images/imagem1.jpg" alt="imagem1" title="imagem1" id="wows1_0"/></li>
								<li><a href="http://wowslider.com"><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/images/imagem2.jpg" alt="css slideshow" title="imagem2" id="wows1_1"/></a></li>
								<li><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/images/imagem3.jpg" alt="imagem3" title="imagem3" id="wows1_2"/></li>
							</ul></div>
								<div class="ws_bullets"><div>
									<a href="#" title="imagem1"><span><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/tooltips/imagem1.jpg" alt="imagem1"/>1</span></a>
									<a href="#" title="imagem2"><span><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/tooltips/imagem2.jpg" alt="imagem2"/>2</span></a>
									<a href="#" title="imagem3"><span><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/tooltips/imagem3.jpg" alt="imagem3"/>3</span></a>
								</div>
							</div>
						<div class="ws_shadow"></div>
						</div>	
						<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/wowslider.js"></script>
						<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/script.js"></script>
					-->

		        ?>

		        <?php

		        	}

		        	else

		        	{

		        		if(isset($conteudos))
		        		{
		        			if(isset($infoTopico->aprovado) && $infoTopico->aprovado=='S'){
		        				echo '<p style="font-weight: bold; font-size: 21px; border-bottom: 1px solid rgb(204, 204, 204);">Tópico validado pelo gerente responsável <span class="glyphicon glyphicon-thumbs-up"  style="color: rgb(68, 204, 0);text-shadow: 1px 1px rgb(51, 153, 0); " aria-hidden="true"></span></p>';
		        			}
		        			else{
		        				echo '<p style="font-weight: bold; font-size: 21px;  border-bottom: 1px solid rgb(204, 204, 204);">Tópico não validado pelo gerente responsável <span  style="color: rgb(255, 51, 51);text-shadow: 1px 1px rgb(179, 0, 0);" aria-hidden="true" class="glyphicon glyphicon-thumbs-down"></span></p>';
		        			}
			        		foreach($conteudos as $key=>$value)

			        		{

			        			$conteudos[$key] = (array)$conteudos[$key];

			        			if(isset($conteudos[$key]['conteudo'])){

			        				if($conteudos[$key]['conteudo']=='')

			        					echo "<h1>Tópico vazio !</h1>";

			        				else{

			        					echo $conteudos[$key]['conteudo'];
			        					$str_referencia = "<h3> Referências: </h3>";
			        					if(isset($conteudos[$key]['referencias']) && is_array($conteudos[$key]['referencias']) && count($conteudos[$key]['referencias']) > 0){
				        					foreach ($conteudos[$key]['referencias'] as $key => $value) {
				        						$str_referencia .= "<h3 style='display: inline-block;margin-right:15px;'><a target='_blank' href='".$value->link_topico."'>".$value->cod_topico." - ".$value->nome_topico."</a></h3>, ";
				        					}
				        					echo substr($str_referencia,0,-2);
				        				}
			        				}
			        			}
			        			else{
			        				echo "<h1>Tópico vazio !</h1>";
			        			}

			        		}
			        	}
		        		else{
		        			echo "<h1>Tópico vazio !</h1>";
		        		}
		        		$data = array('comentarios_controller'=>$comentarios_controller);
		        		$this->load->view('pages/main/comentarios',$data);
		           	}

		        }

		        else{

		        ?>
				<head>
					<link rel="stylesheet" type="text/css" href="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/style.css" />
					<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/jquery.js"></script>
				</head>
				<body style="background-color:#d7d7d7;margin:auto">
					<div style="text-align: center; opacity: 0.4; position: fixed; left: 50%; transform: translateX(-50%);"><img src="/images/LogoCursive.png"></div>
					<!--
					<div id="wowslider-container1" height="480" style="height:480px;background-color:transparent;">
						<div class="ws_images"><ul>
							<li><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/images/imagem1.jpg" alt="imagem1" title="Organize suas documentações !" id="wows1_0"/></li>
							<li><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/images/imagem2.jpg" alt="css slideshow" title="Sistema de busca inteligente !" id="wows1_1"/></li>
							<li><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/images/imagem3.jpg" alt="imagem3" title="A solução para seus documentos !" id="wows1_2"/></li>
						</ul></div>
							<div class="ws_bullets"><div>
								<a href="#" title="imagem1"><span><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/tooltips/imagem1.jpg" alt="imagem1"/>1</span></a>
								<a href="#" title="imagem2"><span><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/tooltips/imagem2.jpg" alt="imagem2"/>2</span></a>
								<a href="#" title="imagem3"><span><img src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/data1/tooltips/imagem3.jpg" alt="imagem3"/>3</span></a>
							</div>
						</div>
					<div class="ws_shadow"></div>
					</div>	
					<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/wowslider.js"></script>
					<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/script.js"></script>
				-->

		        <?php	

		        }

		        ?>



		          



		          



		        </div><!-- /.col-xs-12 main -->



		    </div><!--/.row-->



		  </div><!--/.container-->



		</div><!--/.page-container-->



	</body>


</html>