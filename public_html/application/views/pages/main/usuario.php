 <!-- ckeditor --> <?	/*if(!isset($datauser) ){$data_user = $this->session->userdata('data_user');} */ $data_user = $data_user[0]; /* print_r($data_user);	die();*/ 


 		// le todos os arquivos da pasta
		$pasta = $_SERVER['DOCUMENT_ROOT'].'/application/third_party/usuarios_fotos/'.$this->session->userdata('sg_user_id').'/';
		$link_site = site_url().'application/third_party/usuarios_fotos/'.$this->session->userdata('sg_user_id').'/';
		if(file_exists($pasta)){
	   		$diretorio = dir($pasta);
	   	}
	   	else{
	   		$diretorio = false;
	   	}
	    if(is_object($diretorio)){
		  	while($arquivo = $diretorio -> read()){
		  		if($arquivo !== '..' && $arquivo !== '.'){
			  		//$arquivos[]['nome'] = $arquivo;
			  		//$arquivos[]['tamanho'] = filesize($pasta.$arquivo);
			  		switch (strtoupper (substr($arquivo, strlen($arquivo)-3, 3) ) ) {
			  			case 'JPEG':
			  			case 'JPG':
			  			case 'GIF':
			  			case 'PNG':
			  				$imagem  = $link_site.$arquivo;
			  				break;
			  			default:
			  				$imagem  = site_url('images/LogoCursive150.png');
			  				break;
			  		}
			  	}
		   	}
		   	//print_r($imagens);
		   	//print_r($arquivos);
		   	//die();
		   	//seria legal colocar um filtro por imagens e depois colocar em um painel de imagens especifico
		   	$diretorio -> close();
	    }
	    else{
	    	$imagem  = site_url('images/LogoCursive150.png');
	    }
	    if(!isset($imagem)){
	    	$imagem  = site_url('images/LogoCursive150.png');
	    }



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

	        

		        <div class="col-xs-10 col-sm-9" >

		        	<form method='POST'>											
		        		<div class="form-group">							
		        			<label for="exampleInputEmail1">Nome</label>							
		        			<input type="text" name='u_nome' value="<?php echo $data_user->nome;?>" class="form-control" id="exampleInputNome" placeholder="Digite o nome">						
		        		</div>												
		        		<div class="form-group">							
		        			<label for="exampleInputEmail1">Username</label>							
		        			<input type="text" value="<?php echo $data_user->username;?>" class="form-control" id="exampleInputNome" disabled>						
		        		</div>

						<div class="form-group">

							<label for="exampleInputEmail1">Email</label>

							<input type="email" name='u_email' value="<?php echo $data_user->email;?>" class="form-control" id="exampleInputEmail1" placeholder="Digite o email">

						</div>

						<div class="form-group">

							<label for="exampleInputPassword1">Senha atual (OPCIONAL)</label>

							<input type="password" name='u_senha' value="" class="form-control" id="exampleInputPassword1" placeholder="Digite a senha atual">

						</div>												
						<div class="form-group">							
						<label for="exampleInputPassword1">Nova senha (OPCIONAL)</label>							
						<input type="password" name='u_senha_nova' value="" class="form-control" id="exampleInputPassword1" placeholder="Digite a senha atual">						
						</div>												
						<div class="form-group">							
						<label for="exampleInputPassword1">Repita Nova senha (OPCIONAL)</label>							
						<input type="password" name='u_senha_nova_repeat' value="" class="form-control" id="exampleInputPassword1" placeholder="Digite a senha atual">						
						</div>						
						<!--

						<div class="form-group">

							<label for="exampleInputFile">File input</label>

							<input type="file" id="exampleInputFile">

							<p class="help-block">Example block-level help text here.</p>

						</div>						-->												<!--

						<div class="checkbox">

							<label>

							<input type="checkbox"> Check me out

							</label>

						</div>						-->
						<button type="submit" class="btn btn-default">Salvar</button>

					</form>
						<div class="panel panel-default" style="margin: 40px 0px;">
							<div class="panel-body">
									<span style="font-size: 20px; font-weight: bold; display: flex; margin: -30px 10px 10px 0px; background: white none repeat scroll 0% 0%; width: 160px; padding: 0px 0px 0px 15px; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">Foto de Perfil</span>
									<img id="fotoPerfilimg" class="img-thumbnail" alt="" src="<?php echo $imagem; ?>">
									<br>
								
								    <label>Escolher outra foto de perfil:</label>
								    <form id="ajaxSender" method="POST" action="/usuario/uploadFotoPerfil" enctype="multipart/form-data">
								    	<input type="hidden" id="x" name="x" />
							            <input type="hidden" id="y" name="y" />
							            <input type="hidden" id="w" name="w" />
							            <input type="hidden" id="h" name="h" />
							            <input type="hidden" id="resizeW" name="resizeW" />
							            <input type="hidden" id="resizeH" name="resizeH" />

								    	<input type="file" name="arquivo"/>
									    <br>
									    <button id="uploadFotoBtn" disabled style="width: 145px;" type="submit" class="btn btn-default">Upload</button>
								    </form>
								
							</div>
						</div>

				 </div><!-- /.col-xs-12 main -->

			</div>

		</div>

	</div>	

    <script>
    	<?php 
    		if($this->session->flashdata('trocousenha') ){
    			echo 'alert("'.$this->session->flashdata('trocousenha').'");';
    		}
    	?>   	  
    	
    	var objJcrop = null;
    	var fazDuas = false;
		$('input[type=file]').on('change', function(event) {
			if(objJcrop != null){
				console.log(typeof(objJcrop));
				objJcrop.destroy();
				$("#fotoPerfilimg").attr('src','');
				$("#fotoPerfilimg").attr('style','');
				$('#uploadFotoBtn').attr('disabled','disabled');
			}
		    var tmppath = URL.createObjectURL(event.target.files[0]);
		    $("#fotoPerfilimg").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
		    setTimeout(function(){
			    wIm = $('#fotoPerfilimg').width();
			    hIm = $('#fotoPerfilimg').height();
			    $('#resizeW').val(wIm);
			    $('#resizeH').val(hIm);
				objJcrop = $.Jcrop('#fotoPerfilimg',{
		            aspectRatio: 1,
		            onSelect: updateCoords,
		            trueSize: [wIm,hIm]
		        });
		        if(fazDuas=false){
				    
					fazDuas= true;
				}
				else{
					fazDuas= false;
				}
		    },1000);

			//objJcrop.setImage('server/uploads/image.jpg'); 
			//objJcrop.onSelect(updateCoords);
			/*
			$('#fotoPerfilimg').Jcrop({
	            aspectRatio: 1,
	            onSelect: updateCoords
	        });
 			*/
			

		    console.log(tmppath);
		});

 
 
        function updateCoords(c)
        {
			$('#uploadFotoBtn').removeAttr('disabled');
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        };

		function showPreview(coords)
		{
			var rx = 100 / coords.w;
			var ry = 100 / coords.h;

			$('#preview').css({
				width: Math.round(rx * 500) + 'px',
				height: Math.round(ry * 370) + 'px',
				
			});
		}
		$(document).ready(function(){
			
		});
    </script>





  



