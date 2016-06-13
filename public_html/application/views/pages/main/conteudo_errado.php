<script>

	//Deleta as tags

   $('#painelTagsCorpo > button').click(function(){

   		$(this).remove();

   });

</script>	

</head>



	<body style="overflow-x:hidden !important;">



		



		<div class="page-container">

		<!-- menu -->



        <?php $this->load->view('templates/main/menu'); ?>

        <?php 

        	//die(print_r($tags));

	        if(isset($cod_topico)){

			    if($cod_topico!=''){

		?>

			        <div id="painelTags" style="position: fixed; width: 19%; height: auto; right: 0.2em; top: 4.7em; border-radius: 8px;" class="panel panel-primary filterable">

					<div class="panel-heading">

							<h3 class="panel-title"><span style="color: white; margin-right: 10px;" class="hidden-xs showopacity glyphicon glyphicon-tags" onclick="insertConteudos()"></span>Tags:</h3>

						</div>	   

					    <div id="painelTagsCorpo" class="panel-body ">

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

		<?php 

	        	}

	        }

		?>



		    <div class="container">



		      <div class="row row-offcanvas row-offcanvas-left">



		        <!-- main area -->



		        <div class="col-xs-12 col-sm-11">



		        <?php

		        if(isset($cod_topico)){

		           	if($cod_topico==''){

		        ?>

		        	<head>
						<link rel="stylesheet" type="text/css" href="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/style.css" />
						<script type="text/javascript" src="http://www.p2wiki.com.br/application/third_party/wowSliderEngine/jquery.js"></script>
					</head>
					<body style="background-color:#d7d7d7;margin:auto">
						<div id="wowslider-container1">
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

		        ?>

		        <?php

		        	}

		        	else

		        	{

		        		if(isset($conteudos))

		        		foreach($conteudos as $key=>$value)

		        		{

		        			$conteudos[$key] = (array)$conteudos[$key];

		        			if(isset($conteudos[$key]['conteudo']))

		        				if($conteudos[$key]['conteudo']=='')

		        					echo "<h1>Tópico vazio !</h1>";

		        				else

		        					echo $conteudos[$key]['conteudo'];

		        			else

		        				echo "<h1>Tópico vazio !</h1>";

		        		}

		        		else

		        			echo "<h1>Tópico vazio !</h1>";

		           	}

		        }

		        else{

		        ?>
				<head>
				</head>
				<body style="background-color:#d7d7d7;margin:auto">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" >
					  <!-- Indicators -->
					  <ol class="carousel-indicators">
					    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
					    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
					    
					  </ol>

					  <!-- Wrapper for slides -->
					  <div class="carousel-inner" role="listbox">
					    <div class="item active">
					      <img alt="900x500" data-src="holder.js/900x500/auto/#555:#555" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgdmlld0JveD0iMCAwIDkwMCA1MDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzkwMHg1MDAvYXV0by8jNTU1OiM1NTUKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNGVmZTY0N2I2MCB0ZXh0IHsgZmlsbDojNTU1O2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjQ1cHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE0ZWZlNjQ3YjYwIj48cmVjdCB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iIzU1NSIvPjxnPjx0ZXh0IHg9IjMzMyIgeT0iMjcxIj45MDB4NTAwPC90ZXh0PjwvZz48L2c+PC9zdmc+" data-holder-rendered="true">
					      <div class="carousel-caption">
					        1
					      </div>
					    </div>
					    <div class="item">
					      <img alt="900x500" data-src="holder.js/900x500/auto/#555:#555" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgdmlld0JveD0iMCAwIDkwMCA1MDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzkwMHg1MDAvYXV0by8jNTU1OiM1NTUKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNGVmZTY0N2I2MCB0ZXh0IHsgZmlsbDojNTU1O2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjQ1cHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE0ZWZlNjQ3YjYwIj48cmVjdCB3aWR0aD0iOTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iIzU1NSIvPjxnPjx0ZXh0IHg9IjMzMyIgeT0iMjcxIj45MDB4NTAwPC90ZXh0PjwvZz48L2c+PC9zdmc+" data-holder-rendered="true">
					      <div class="carousel-caption">
					        2
					      </div>
					    </div>
					    
					  </div>

					  <!-- Controls -->
					  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
					    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					    <span class="sr-only">Previous</span>
					  </a>
					  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
					    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					    <span class="sr-only">Next</span>
					  </a>
					</div>

		        <?php	

		        }

		        ?>



		          



		          



		        </div><!-- /.col-xs-12 main -->



		    </div><!--/.row-->



		  </div><!--/.container-->



		</div><!--/.page-container-->



	</body>



	<?php $this->load->view('templates/main/footer'); ?>



</html>