<?php

	$msg_error = '<div class="alert alert-danger" role="alert">'.$this->session->userdata('msg_error').'</div>';

	if(!$this->session->userdata('msg_error')){
		$msg_error = '';
	}

?>

<?php

	$nome = $this->session->userdata('sg_nome');

	$url = "http://".$_SERVER['SERVER_NAME'];

?>
<script>

	if("<?php if($msg_error != '')echo $msg_error; else echo '|none|';?>"!='|none|'){

		$('.blocoLogin').attr('style','height:0%;');

		$('.blocoLogin > .input-group').prepend('<?=$msg_error?>');

	}

	$(document).ready(function(){
		
		$('#botaoMenuLateral').click(function(){

			if($('#divCampoBusca').attr('class').indexOf('hidden-xs')!=-1){
				$('#divCampoBusca').removeClass('hidden-xs');
				$('#divCampoBusca').css('display','none')
			}
			if($('#divCampoBusca').css('display')!='block')
			{
				$('.navbar-toggle').css('background-color', '#ddd');
			}
			else
			{
				$('.navbar-toggle').css('background-color','rgb(248, 248, 248)');
			}
			$('#divCampoBusca').slideToggle();
		});
		$('#permition1Modal').on('hidden.bs.modal', function (e) {
			if($('#bloco-captcha').attr('class').indexOf('hidden')>=0){
				$('#form-login').fadeIn('300');
  			}
		});
		/*
		$('#botaoMenuLateral').click(function(){
			var divSearsh = $('#inputBusca').parent().parent();
			if($(divSearsh).css('display') == 'none'){

				$(divSearsh).show();

			}

			else{

				$(divSearsh).hide();

			}
		});
*/


		//arruma uma borda preta que tinha no menu e depois coloca a borda normal !

		$('#liMenuEsquerdo').css('width','101%');

		$('#divLiMenuEsquerdo').css('border-right','3px solid #ddd');



	});

</script>

 <style>
 	.hover-orange:hover{
 		color: #158ac4;
	    font-weight: bold;
	    text-shadow: 0.1px 0.1px #ddd;
 	}
 	.hover-orange{
 		cursor:pointer;
 	}
 	.spanImagesTopo{
 		display: inline-block;
	    float: left;
	    font-size: 20px;
	    margin-right: 4px;
	    padding-top: 14px;
 	}

 	#aLogin,#aLogout{
 		cursor:pointer;
 	}

 	#aLogin:hover{
 		cursor:pointer;
 	}

 	#aLogin:hover{
 		background-color:red;
 	}

 	#modal-login{
	 	display: none; 
	 	width: 100%; 
	 	overflow: hidden; 
	 	position: fixed; 
	 	height: 104%; 
	 	margin-top: -4%; 
	 	text-align: center; 
	 	background-color: gray; 
	 	opacity: 0.8; 
	 	z-index: 1031;
 	}

 	#form-login{
 		display:none;
 	    left: 50%;
	    position: absolute;
	    top: 50%;
	    z-index: 1032;
	}	

	.blocoLogin{
		background-color:#F8F8F8;
		width:470px;
		height:225px;
		padding:25px;
		border:2px solid #969696;
	}

	.input-group span{
		width:80px;
	}

	.input-group div input{
		width:175%;
	}

	.login{
		width:32.4%;
	}

	#exitForm{
		background-color: #f8f8f8;
	    border-radius: 35px;
	    float: right;
	    height: 21px;
	    margin-right: -8px;
	    margin-top: -8px;
	    width: 20px;
	}

	#exitForm span{
		color: rgb(150, 150, 150);
	    font-size: 23px;
	    margin-left: -1px;
	    margin-top: -2px;
	}

	#exitForm:hover{
		background-color: #E6E6E6;
	    border-radius: 35px;
	    float: right;
	    height: 21px;
	    margin-right: -8px;
	    margin-top: -8px;
	    width: 20px;
	}

</style>


 	<div class="navbar navbar-default navbar-fixed-top" role="navigation">


       <div class=""><!-- container -->


    	<div class="navbar-header">


           <button id="botaoMenuLateral" type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".sidebar-nav">


             <span class="icon-bar"></span>


             <span class="icon-bar"></span>


             <span class="icon-bar"></span>


           </button>

           <div style="float: left; overflow-x: hidden; padding: 8px; width: 51px;"><img style="height: 33px;" src="/images/p2wikiLogo.png"></div>
           <a class="navbar-brand" href="http://www.p2wiki.com.br/" style="padding-left: 5px; font-size: 24px;"> P2Wiki</a>


    	</div>

    	<span class="navbar-brand col-sm-4 hidden-xs"></span>


    		<div class="hidden-xs" style="float: right; width: 7.5em;">

	    		<a id="aLogin">

		    		<span class="glyphicon glyphicon-user spanImagesTopo" aria-hidden="true" ></span>

		    		<span class="navbar-brand" style="display: block;">Login</span>

		    	</a>

	    	</div>

		    <div  id = "divCampoBusca" style="padding-bottom: 0.3em;padding-top: 0.6em;background-color:transparent;" class="hidden-xs navbar-header col-sm-4 col-xs-12 col-md-5 " >

			    <a id="btnBusca" class = "hidden-xs" href="<?=$url?>/search?q="><span style="float: left; font-size: 1.5em; margin-top: 0.3em; margin-right: 0.4em;" class="glyphicon glyphicon-search col-xm-1" aria-hidden="true"></span></a>

				<div class="pull-left col-xs-9" style="padding-left:0;padding-right:0;"><input type="text" class="form-control" placeholder="Pesquisar" style="border-radius: 5px 0px 0px 5px;" id="inputBusca"></div>

				<div style="" class="pull-left">
					<button type="button" class="form-control btn btn-default" style="border-radius: 0px 5px 5px 0px; border-left: medium none navy;" onclick="btnBusca.click()">Buscar</button>
				</div>

			</div>	

       </div>

    </div>

	<div id="modal-login"></div>

</div>

<form id="form-login" method="POST" action="http://www.p2wiki.com.br/session/login/" style="position: absolute; top: calc(50% - 130px); left: calc(50% - 219px); display: none;">

	<div id="exitForm" style="cursor:pointer;">

		<span class="glyphicon glyphicon-remove-circle" aria-hidden="true" style="font-size: 23px; color: rgb(150, 150, 150); margin-top: -2px; margin-left: -1px;">

		</span>

	</div>

    <div class="blocoLogin">

   		<div class="input-group " style="width: 100%;">

   			<div style="float:left;">

   				<span class="navbar-brand">Login</span>

   			</div>

				<div style="float: left; padding: 10px; width: 80%;">

       			<input aria-describedby="sizing-addon2" class="form-control" name="username" data-fieldid="0" placeholder="Usuário" >

       		</div>


			<br>


            <div style="float:left;">


   				<span class="navbar-brand">Senha</span>


   			</div>


   			<div style="float:left;padding: 10px; width: 80%;">


            	<input type="password" aria-describedby="sizing-addon2" class="form-control" name="senha" data-fieldid="0" placeholder="Senha">


   			</div>
   			<br>
   			<div style="width: 80%; float: right; max-height: 15px; padding-top: 0px; margin-top: -15px;margin-bottom: 10px;">


   				<span class="navbar-brand hover-orange" style="width: 80%; padding: 10px; font-size: 15px;" data-toggle="modal" data-target="#esqueciSenhaModal" >Esqueci minha senha.</span>


   			</div>


   		</div><!-- Fim input-group -->


   		<br>


   		<div aria-label="..." role="group" class="btn-group" style="width:100%;padding-left:3%;padding-right:3%;">


			<button class="btn btn-default" style="width:50%;" type="submit">Entrar</button>

		
			<button class="btn btn-default" style="width:50%;" type="button" data-toggle="modal" data-target="#permition1Modal">Registrar</button>


		</div><!-- Fim btn-group -->


   	</div><!-- Fim bloco login -->


</form>

<!-- #permition1Modal -->
<?php $this->load->view('templates/main/registrar_modal'); ?>

<!-- #esqueciSenhaModal -->
<?php $this->load->view('templates/main/esquecisenha_modal'); ?>
   







   