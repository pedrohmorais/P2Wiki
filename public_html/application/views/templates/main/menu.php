<?php 
	$this->load->library('../controllers/cliente');
	$classClientes = new Cliente;
	$clientes = $classClientes->sg_clientes;
	
	//$menu = $this->session->userdata('menu');
	$menu = $classClientes->getMenu();
	$menu = $menu['menu'];

	$user_id = $this->session->userdata('sg_user_id');
	if($this->session->userdata('sg_clientes')){
		$clientes = $this->session->userdata('sg_clientes');
	}
	$categorias_base =  $menu['categorias_base'];

	$categorias_base = (array)$categorias_base;

	$nivel_acesso = $user_id = $this->session->userdata('sg_nivel_acesso');
	
	foreach($categorias_base as $key=>$value){
		$categorias_base[$key] = (array)$categorias_base[$key];
	}
	//die(print_r($categorias_base));
				
?>
<style>
	    body,html{
		height: 100%;
	}

	/* remove outer padding */
	.main .row{
		padding: 0px;
		margin: 0px;
	}

	/*Remove rounded coners*/

	nav.sidebar.navbar {
		border-radius: 0px;
	}

	nav.sidebar, .main{
		-webkit-transition: margin 200ms ease-out;
	    -moz-transition: margin 200ms ease-out;
	    -o-transition: margin 200ms ease-out;
	    transition: margin 200ms ease-out;
	}

	/* Add gap to nav and right windows.*/
	.main{
		padding: 10px 10px 0 10px;
	}

	/* .....NavBar: Icon only with coloring/layout.....*/

	/*small/medium side display*/
	@media (min-width: 768px) {

		/*Allow main to be next to Nav*/
		.main{
			position: absolute;
			width: calc(100% - 40px); /*keeps 100% minus nav size*/
			margin-left: 40px;
			float: right;
		}

		/*lets nav bar to be showed on mouseover*/
		nav.sidebar:hover + .main{
			margin-left: 200px;
		}

		/*Center Brand*/
		nav.sidebar.navbar.sidebar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand {
			margin-left: 0px;
		}
		/*Center Brand*/
		nav.sidebar .navbar-brand, nav.sidebar .navbar-header{
			text-align: center;
			width: 100%;
			margin-left: 0px;
		}

		/*Center Icons*/
		nav.sidebar a{
			padding-right: 13px;
		}

		/*adds border top to first nav box */
		nav.sidebar .navbar-nav > li:first-child{
			border-top: 1px #e5e5e5 solid;
		}

		/*adds border to bottom nav boxes*/
		nav.sidebar .navbar-nav > li{
			border-bottom: 1px #e5e5e5 solid;
		}

		/* Colors/style dropdown box*/
		nav.sidebar .navbar-nav .open .dropdown-menu {
			position: static;
			float: none;
			width: auto;
			margin-top: 0;
			background-color: transparent;
			border: 0;
			-webkit-box-shadow: none;
			box-shadow: none;
		}

		/*allows nav box to use 100% width*/
		nav.sidebar .navbar-collapse, nav.sidebar .container-fluid{
			padding: 0 0px 0 0px;
		}

		/*colors dropdown box text */
		.navbar-inverse .navbar-nav .open .dropdown-menu>li>a {
			color: #777;
		}

		/*gives sidebar width/height*/
		nav.sidebar{
			width: 200px;
			height: 100%;
			/*margin-left: -160px;*/
			float: left;
			z-index: 8000;
			margin-bottom: 0px;
		}

		/*give sidebar 100% width;*/
		nav.sidebar li {
			width: 100%;
		}

		/* Move nav to full on mouse over
		nav.sidebar:hover{
			margin-left: 0px;
		}*/
		/*for hiden things when navbar hidden*/
		.forAnimate{
			opacity: 0;
		}
	}

	/* .....NavBar: Fully showing nav bar..... */

	@media (min-width: 1330px) {

		/*Allow main to be next to Nav*/
		.main{
			width: calc(100% - 200px); /*keeps 100% minus nav size*/
			margin-left: 200px;
		}

		/*Show all nav*/
		nav.sidebar{
			margin-left: 0px;
			float: left;
		}
		/*Show hidden items on nav*/
		nav.sidebar .forAnimate{
			opacity: 1;
		}
	}
	/*
	nav.sidebar .navbar-nav .open .dropdown-menu>li>a:hover, nav.sidebar .navbar-nav .open .dropdown-menu>li>a:focus {
		color: #CCC;
		background-color: transparent;
	}
	*/
	nav:hover .forAnimate{
		opacity: 1;
	}
	section{
		padding-left: 15px;
	}
	#liMenuEsquerdo li{
		cursor:pointer;
	}
	#divLiMenuEsquerdo{
		z-index:1;
		border-right:1px solid #ddd;
		margin-right: -3px;
	}
	#liMenuEsquerdo li:hover a{
		color:#158AC4;
	}
	#classeMenuDrop:hover a{
		color:#158AC4;
	}
	#liMenuEsquerdo li a{
		background-color:#F8F8F8;
	}
	#liMenuEsquerdo li:first-child{
		border-top:none;
	}

	#liMenuEsquerdo li a{
		color:#333;
	}

	#divLiMenuEsquerdo{
		border:1px solid #ddd;
	}

	#divLiMenuEsquerdo{
		border-top:none;
	}

	#liMenuEsquerdo li:first{
		border-top:none;
	}

	.ulfilho li a{
		color:#333333 !important;
	}

	.ulfilho li{
		background-color:#F8F8F8;
	}
	.ulfilho li{		
		background-color: #6a6a6a;		
	}

	.textoCategoria:hover{		
		color: #7CBDDE !important;		
	}

	.liTopico:hover a{		
		color: #7CBDDE !important;		
	}
</style>
<script>
$(document).ready(function(){
	//por algum motivo o css disso não pega mesmo com !important
	//$('.ulfilho >li > a').attr('style','color:#333 !important;');

	$('#liMenuEsquerdo .dropdown-toggle').click(function(){
		if($(this).next().css('display')=='none'){
			$(this).attr('style','color:#333333 !important;background-color:#158AC4;');
			$(this).parent().find('li a').css('background-color','#ddd')
		}
		else{
			$(this).css('background-color','#F8F8F8');
			$(this).css('color','#333333');
		}
		$(this).next().slideToggle();
		var x = $(this).offset().top;
		window.scrollTo(0,x-50);
	});
	
	/*
	$('.classeMenuDrop').click(function(){
		if($('#divLiMenuEsquerdo').height() < $('html, body').height() ){
			$('html, body').animate({
	   			scrollTop: 0
			}, 100);
		}
		else{
			$('html, body').animate({
	   			scrollTop: $(this).offset().top - $(this).height()
			}, 100);
		}		
	});
	*/
	
});
	
</script>
<nav id="divLiMenuEsquerdo" class="navbar navbar-inverse sidebar hidden-xs" role="navigation">
    <div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
			<ul id="liMenuEsquerdo" class="nav navbar-nav">
				<li ><a href="<?php echo site_url(); ?>">Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>
				<?php
					if($user_id!=0)
						//echo '<li ><a href="'.site_url().'usuario">Usuário<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>';
						echo '<li class="dropdown classeMenuDrop">
									<a data-toggle="dropdown" class="dropdown-toggle textoCategoria" style="color: rgb(51, 51, 51); background-color: rgb(248, 248, 248);">
										Usuário 
										<span class="caret"></span>
										<span class="pull-right hidden-xs showopacity glyphicon glyphicon-user" style="font-size:16px;"></span>
									</a>
									<ul style="width: 100%; display: none;" class="nav navbar-nav ulfilho">
										<li class="liTopico">
											<a href="'.site_url().'grupos" style="color:#333;">
												Meus grupos
											</a>
										</li>
										<li class="liTopico">
											<a href="'.site_url().'usuario" style="color:#333;">
												Minha conta
											</a>
										</li>
								</ul>
								</li>';
						if($nivel_acesso >= 3){
							//echo '<li><a href="'.site_url().'usuario">Gerenciar Usuários<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span><span style="font-size: 16px; margin-right: -7px; color: rgb(136, 136, 136);" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>';
							if($nivel_acesso == 4){
								echo '<li class="dropdown classeMenuDrop" id="relatoriosPdf">
									<a data-toggle="dropdown"  class="dropdown-toggle textoCategoria" style="color: rgb(51, 51, 51); background-color: rgb(248, 248, 248);">
										Relatórios 
										<span class="caret"></span>
										<span class="pull-right hidden-xs showopacity glyphicon glyphicon-book" style="font-size:16px;"></span>
									</a>
									<ul style="width: 100%; display: none;" class="nav navbar-nav ulfilho">
										<li class="liTopico">
											<a href="'.site_url().'pdf?rel=clientes_fisicos" style="color:#333;">
												Clientes Físicos
											</a>
										</li>
										<li class="liTopico">
											<a href="'.site_url().'pdf?rel=clientes_juridicos" style="color:#333;">
												Clientes Jurídicos
											</a>
										</li>
										<li class="liTopico">
											<a href="'.site_url().'pdf?rel=usuarios" style="color:#333;">
												Usuários
											</a>
										</li>
										<li class="liTopico">
											<a href="'.site_url().'pdf?rel=acessos_topicos" style="color:#333;">
												Tópicos
											</a>
										</li>
								</ul>
								</li>';
							}
							echo '<li><a href="'.site_url().'usuario/gerenciar">Gerenciar Usuários<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a></li>';
							if($nivel_acesso == 4){
								echo '<li><a href="'.site_url().'cliente/gerenciar">Gerenciar Clientes<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-usd"></span></a></li>';
							}
						}
						if($nivel_acesso >=2 ){
							echo '<li ><a href="'.site_url().'categoria">Gerenciar Categorias<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-list-alt"></span></a></li>';
							if(true){
								if(count($clientes)>0 ){
									//echo '<li ><a href="'.site_url().'categoria"><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks"></span></a></li>';
									echo '<li class="dropdown classeMenuDrop">
										<a data-toggle="dropdown" class="dropdown-toggle textoCategoria" style="color: rgb(51, 51, 51); background-color: rgb(248, 248, 248);">
											Painel do Cliente 
											<span class="caret"></span>
											<span class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks" style="font-size:16px;"></span>
										</a>
										<ul style="width: 100%; display: none;" class="nav navbar-nav ulfilho">';
									foreach ($clientes as $cliente) {
										echo '<li class="liTopico"><a href="'.site_url().'cliente/painel/'.$cliente['cod_cliente'].'" style="color:#333;">';
										echo  $cliente['nome'];
										echo'	</a></li>';
									}
									echo '</ul></li>';
								}
							}
						}
				?>
				<!--
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>
					<ul class="nav navbar-nav ulfilho" style="display:none;" >
						<li><a >Action</a></li>
						<li><a >Another action</a></li>
						<li><a >Something else here</a></li>
						<li class="divider"></li>
						<li><a >Separated link</a></li>
						<li class="divider"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>
							<ul class="nav navbar-nav ulfilho" style="display:none;" >
								<li><a >Action</a></li>
								<li><a >Another action</a></li>
								<li><a >Something else here</a></li>
								<li class="divider"></li>
								<li><a >Separated link</a></li>
								<li class="divider"></li>
								<li><a >One more separated link</a></li>
							</ul>
						</li>
					</ul>
				</li>
				-->
			<?php
				$categoria_temp = array();
				foreach($categorias_base as $key=>$value)
				{
					if(isset($categorias_base[$key]['cod_categoria'])){
						$categoria_temp = $value;

						echo '<li id="categoriaMenu'.$categorias_base[$key]['cod_categoria'].'" class="dropdown classeMenuDrop"><a class="dropdown-toggle textoCategoria" data-toggle="dropdown">'.$categorias_base[$key]['nome_categoria'].' <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon '.$categorias_base[$key]['glyphicon'].'"></span></a><ul class="nav navbar-nav ulfilho" style="display:none;width: 100%;" >';
							
						$this->load->model('categoria/Categorias');
						$topicos = $this->Categorias->getTopicos($categorias_base[$key]['cod_categoria'],$this->session->userdata('sg_user_id'));
						$topicos = (array)$topicos;
						
						for($i = 0;$i<count($topicos);$i++){
							$topicos[$i] = (array)$topicos[$i];
						}
						
						for($i = 0;$i<count($topicos);$i++)
						{
							
							if(!empty($topicos[$i]['link_topico']))
							{
								echo '<li class="liTopico"><a style="color:#333;" cod_topico="'.$topicos[$i]['cod_topico'].'" href="'.$topicos[$i]['link_topico'].'" >'.$topicos[$i]['nome_topico'].'</a></li>';
							}
							
						}
						
						if($categorias_base[$key]['categoria_pai']>0){
							
							if(empty($filhos)){
								$filhos = array(array($categorias_base[$key]['categoria_pai'],$categorias_base[$key]['cod_categoria']));
							}
							else{
								$filhos[$key] = array($categorias_base[$key]['categoria_pai'],$categorias_base[$key]['cod_categoria']);
							}	
						}	
						
						echo '
							</ul>
						</li>';
					}	
				}
				echo "<script>";
				if(isset($filhos)){
					foreach($filhos as $key=>$value)	
					{
						echo "$('#categoriaMenu".$filhos[$key][1]."').appendTo($('#categoriaMenu".$filhos[$key][0]." > ul:first'));";
					}
				}
				echo "</script>";
			?>
				</ul>
				<!--
				<li><a >Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>
				<li ><a >Profile<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>
				<li ><a >Messages<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-envelope"></span></a></li>
				<li class="dropdown">
					<a  class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>
					<ul class="dropdown-menu forAnimate" role="menu">
						<li><a >Action</a></li>
						<li><a >Another action</a></li>
						<li><a >Something else here</a></li>
						<li class="divider"></li>
						<li><a >Separated link</a></li>
						<li class="divider"></li>
						<li><a >One more separated link</a></li>
					</ul>
				</li>
				-->
			</ul>
		</div>
	</div>
</nav>
<div class="main">
<!-- Content Here -->
</div>