<?php

	$nome = $this->session->userdata('sg_nome');

	$url = "http://".$_SERVER['SERVER_NAME'];

?>

 <style>
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
	 	overflow-x: hidden; 
	 	position: absolute; 
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



	    	<div class="navbar-header col-sm-5">



		    	<button id="botaoMenuLateral" type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".sidebar-nav">

		        <span class="icon-bar"></span>



		        <span class="icon-bar"></span>



		        <span class="icon-bar"></span>



		        </button>



	           <a class="navbar-brand" href="http://www.p2wiki.com.br/"><span area-hidden="true" class="glyphicon glyphicon-book" style="font-size: 17px;"></span> P2Wiki</a><span class="navbar-brand"> - &nbsp;&nbsp;<?=ucfirst($nome);?></span>



	    	</div>



			<div class="hidden-xs" style="float: right;">



    			<a id="aLogout" href="http://www.p2wiki.com.br/session/logout/">



	    		<span class="glyphicon glyphicon-off spanImagesTopo" aria-hidden="true" ></span>



	    		<span class="navbar-brand" style="display: block;">Logout</span>



	    		</a>



    		</div>

		    <div style="padding-top: 0.6em;" class="navbar-header col-sm-4 col-xs-10 col-md-5 ">

			    <a id="btnBusca" href="<?=$url?>/search?q="><span style="float: left; font-size: 1.5em; margin-top: 0.3em; margin-right: 0.4em;" class="glyphicon glyphicon-search col-xm-1" aria-hidden="true"></span></a>

				<div class="pull-left" style="width: 28em;"><input type="text" class="form-control" placeholder="Pesquisar" style="border-radius: 5px 0px 0px 5px;" id="inputBusca"></div>

				<div style="" class="pull-left hidden-xs"><button type="button" class="form-control btn btn-default" style="border-radius: 0px 5px 5px 0px; border-left: medium none navy;">Buscar</button></div>

			</div>

	    		

        </div>



    </div>









   







   