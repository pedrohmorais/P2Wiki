<?php

	$nome = $this->session->userdata('sg_nome');

	$url = "http://".$_SERVER['SERVER_NAME'];

?>

<script>
	function validaEmailMain(email){
		//var email = $("#email").val();
		if(email != "")
		{
			var filtro = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			if(filtro.test(email))
			{
				//return ("Este endereço de email é válido!");
				return true;
			} else {
				//return ("Este endereço de email não é válido!");
				return false;
			}
		} 
		else {
			//return ('Digite um email!'); 
			return false;
		}
		return false;
	}
	
	//botao de busca

	$('#btnBusca').click(function(){

    	$(this).attr('href', '<?=$url?>/search?q=' + $('#inputBusca').val());

	});

	$('#inputBusca').keypress(function( event ) {

		if(event.which==13){

	    	$('#btnBusca > span').click();

	    }

	});

	$(document).ready(function(){

		//não consegui alterar no bootstrap.css então vai por aqui #gambiarra

		$('.container').css('padding-top','1em');

		$('.container').css('padding-left','1em');



		var paineldastasgs = setInterval(function(){

			if($('#botaoMenuLateral').css('display') == 'none'){

				$('#painelTags').show();

			}

			else{

				$('#painelTags').hide();

			}

		},1000);
		$('#painelTags').click(function(){
			//$(this).hide();
			clearInterval(paineldastasgs);
		});
			
		//some e aparece searsh mobile
		





		//Deleta as tags

	   $('#painelTagsCorpo > button').click(function(){

	   		$(this).remove();

	   });



		$('#aLogin').click(function(){



			$('#modal-login').css('display','block');



			$('#form-login').css('display','block');



		});

		/*

		$('#aLogin').hover(

			function(){

				$('#aLogin').children().first().animate({left: "+=50",

				$('#aLogin').children().next().show( "slide", { direction: "right"  },'slow' );		

			},

			function(){

		  		$('#aLogin').children().next().hide( "slide", { direction: "right"  },'slow' );				

			}

		);

		$('#aLogout').hover(

			function(){

				$('#aLogout').children().next().fadeIn('slow');			

			},

			function(){		  

				$('#aLogout').children().next().fadeOut('slow');				

			}

		); 

  		*/

		$('body').css('overflow-x','hidden');



//botão de tirar o login



		$('#exitForm').click(function(){



			$('#form-login .form-control').val("");



  			$('#modal-login').css('display','none');



			$('#form-login').css('display','none');



		});

	//Soma acessos quando o link do tópico for externo
		$('.liTopico a').each(function(){
	 		$(this).click(function(){
	   			var str = $(this).attr('href');
	   			var n = str.indexOf("p2wiki.com.br/conteudo/visualizar");
	    		if(n<0){
	    			
	    			$.ajax({
						url : 'http://www.p2wiki.com.br/conteudo/inserirAcessoExterno', 
						cache : false,
						async : false,
						type : 'post',

						dataType : 'json',

						data : 'cod_topico='+$(this).attr('cod_topico'),
						success: function(response,status) {
							//console.log(response.retorno);
							if(response.retorno!='false'){
								console.log('sucesso !');
							}
						}
			   		});
	    		}	
	  		});
		});

	});

	//filtro da tabela
	

</script>