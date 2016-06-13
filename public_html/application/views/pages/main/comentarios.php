<style>
	#unlikesLabel:hover{
		cursor: pointer;
		color: #337AB7;
	}
	#likesLabel:hover{
		cursor: pointer;
		color: #337AB7;
	}
</style>
<div style="padding:20px 0 20px 0px;">
	<h1 style="border-bottom:1px solid #bbb">Comentários</h1>
	<div id="trueComentario" role="alert" class="alert alert-success hidden">
      
    </div>
    <div id="falseComentario" role="alert" class="alert alert-danger hidden">
      
    </div>
    <?php if(isset($comentarios_controller) && is_array($comentarios_controller) && count($comentarios_controller)>0)foreach ($comentarios_controller as $comentario) {
    ?>
	<div id="divComentario<?php echo $comentario['cod_comentario']?>" class="comentariosDivs panel panel-default col-lg-8 col-sm-10 ">
		<div class="panel-body hidden-xs" style="padding-left: 0px;">
			<img src="<?php echo $comentario['link_foto']; ?>" alt="" class="img-thumbnail" id="fotoPerfilimg" style="width: 170px;">
			<div style="float: right; width: calc(100% - 170px); padding: 10px; font-size: 20px;">
				<span onclick='deleteComentario("<?php echo $comentario['cod_comentario']?>");' class="glyphicon glyphicon-remove" aria-hidden="true" style="float: right; color: rgb(204, 0, 0); cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Excluir tópico"></span>
				<span style="font-weight: bold;display:block;"><?php echo $comentario['nome_usuario']?> escreveu:</span>
				<span style="display: block; font-size: 18px; padding-left: 20px; min-height: 86px;"><?php echo $comentario['comentario']?> </span>
			<!--
  			<div style="font-size: 24px; float: left;">
				<label id="likesLabel">
					<span>10 </span><span style="margin-right: 20px;" class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
				</label>
				<label id="unlikesLabel">
					<span>3 </span>
					<span aria-hidden="true" class="glyphicon glyphicon-thumbs-down"></span>
				</label>
			</div>
			-->
			</div>
			
		</div>
		<div id="divComentario<?php echo $comentario['cod_comentario']?>" class="panel-body visible-xs" style="padding-left: 0px;">
			<img src="<?php echo $comentario['link_foto']; ?>" alt="" class="img-thumbnail" id="fotoPerfilimg" style="width: 70%;margin:10px 15%;">
			<div style="padding: 10px; font-size: 20px;">
				<span onclick='deleteComentario("<?php echo $comentario['cod_comentario']?>");' class="glyphicon glyphicon-remove" aria-hidden="true" style="float:right;color: rgb(204, 0, 0); cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Excluir tópico"></span>
				<span style="font-weight: bold;display:block;"><?php echo $comentario['nome_usuario']?> escreveu:</span>
				<span style="display: block; font-size: 18px; padding-left: 20px; min-height: 86px;"><?php echo $comentario['comentario']?> </span>
			<!--
  			<div style="font-size: 24px; float: left;">
				<label id="likesLabel">
					<span>10 </span><span style="margin-right: 20px;" class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
				</label>
				<label id="unlikesLabel">
					<span>3 </span>
					<span aria-hidden="true" class="glyphicon glyphicon-thumbs-down"></span>
				</label>
			</div>
			-->
			</div>
			
		</div>
	</div>
	<?php
    }
    if($this->session->userdata('sg_user_id'))
	{
    ?>

	<div class="col-lg-8 col-sm-10 bgTransparent" style="padding: 5px 0px 0px;">
		<form action="/conteudo/inserirComentario/<?php echo $cod_topico; ?>" method="POST">
			<label style="margin-left: 15px; width: 210px; border: 1px solid rgb(204, 204, 204); position: relative; background-color: white; display: block; padding: 3px 5px 3px 10px; margin-bottom: -12px; border-radius: 3px;">Escreva sobre este tópico.</label>
			<textarea name="comentario" style="padding-top: 12px;" class="form-control"></textarea>
			<button class="btn btn-primary" onclick="submit()" type="button" style="margin: 10px 0px;"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Publicar Comentário </button>
		</form>
	</div>
	<?php } ?>
</div>

<script>
	function deleteComentario(cod_comentario){
		$.ajax({
			url : '<?php echo site_url(); ?>/conteudo/deleteComentario', 
			cache : false,
			async : false,
			type : 'post',
			dataType : 'json',
			data : 'cod_comentario='+cod_comentario,
			success: function(response,status) {
				//console.log(response.retorno);
				if(response.erro!='false'){
					$('#trueComentario').html(response.mensagem);
					$('#trueComentario').removeClass('hidden');
					//$('#trueComentario').slideToggle();
					setTimeout(function(){
						$('#trueComentario').slideToggle();
						$('#trueComentario').hide();
						$('#trueComentario').addClass('hidden');
					},2500);
					$('#divComentario'+cod_comentario).remove();
					return true;
				}
				else{
					$('#falseComentario').removeClass('hidden');
					$('#falseComentario').html(response.mensagem);
					$('#falseComentario').show();
					setTimeout(function(){
						$('#falseComentario').hide();
						$('#falseComentario').addClass('hidden');
					},2500);

					return false;
				}
			}
	   	});

	}
	<?php 
		if($this->session->flashdata('erroinserircomentario')){
			$errocomentario = $this->session->flashdata('erroinserircomentario');
			echo "$('#".$errocomentario['erro'].'Comentario\').html("'.$errocomentario['mensagem'].'");';
			echo "$('#".$errocomentario['erro'].'Comentario\').removeClass("hidden");';	
			echo "var x = $('#trueComentario').offset().top;";
			echo "window.scrollTo(0,x-100);";		
		}
	?>
	$('#trueComentario').on('click',function(){
		$(this).fadeOut('slow');
	})
	$('#falseComentario').on('click',function(){
		$(this).fadeOut('slow');
	});
	$(function () {
	  	$('[data-toggle="tooltip"]').tooltip()
	})
</script>

