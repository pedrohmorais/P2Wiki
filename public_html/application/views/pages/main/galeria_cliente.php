<?php 

get_instance()->load->helper('utilitarios');

?>
<div class="col-sm-10">
	<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" >Repositório de arquivos </h2>
	<div class="form-group">
		<form method="post" action="/cliente/uploadGallery?cliente=<?php echo $cod_cliente ?>" enctype="multipart/form-data">
		    <label>Upload de arquivo para o repositório :</label>
		    <input type="file" name="arquivo">
		    <br>
		    <button class="btn btn-default" type="submit" style="width: 145px;">Upload</button>
		</form>
	</div>
	<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" ></h2>
	<div class="panel panel-default">
                        <div class="panel-heading">
                            <?php
								//formata o tamanho
	                            
	                            echo formataTamanho($tamanho_utilizado).' utilizados de um limite de '.'64 000 kb'; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class=""><a data-toggle="tab" href="#arquivosGaleria" aria-expanded="true">Arquivos</a>
                                </li>
                                <li class="active"><a data-toggle="tab" href="#imagensGaleria" aria-expanded="false">Imagens</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="arquivosGaleria" class="tab-pane fade ">
                                    <div class="table-responsive">
									    <table class="table table-hover">
									        <thead>
									            <tr>
									                <th>#</th>
									                <th>Nome do Arquivo</th>
									                <th>Link Download</th>
									                <th>Tamanho</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php foreach ($lista_arquivos as $key => $value) {
									        		//formata o tamanho
									        		$value['tamanho'] = formataTamanho($value['tamanho']);
									        		echo '<tr>';
										            echo '    <td>'.($key+1).'</td>';
										            echo '    <td>'.$value['nome'].'</td>';
										            echo '    <td>'.$value['link'].'</td>';
										            echo '    <td style="width: 10%;">'.$value['tamanho'].'</td>';
										            echo '</tr>';
									        	}
									        	?>
									        </tbody>
									    </table>
									</div>
                                </div>
                                <div id="imagensGaleria" class="tab-pane fade active in" >
                                    <div class="table-responsive">
							        	<?php foreach ($lista_imagens as $key => $value) {
							        		//formata o tamanho
									        $value['tamanho'] = formataTamanho($value['tamanho']);
							        		echo '<div style="float:left;padding:10px;">';
								            echo '   <div class="img-thumbnail" data-toggle="popover" data-placement="bottom"';
								            echo '		data-content="';
								            echo '			'.$value['nome'].'';
								            echo '			Tamanho: '.$value['tamanho'].'';
								            echo '	  ">';
								            echo '	 	<img src="'.$value['link'].'" alt="'.$value['link'].'"  style="max-height: 200px;">';
								            echo ' 	 </div>';
								            echo '</div>';
							        	}
							        	?><td>
									</div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
	
</div>
<script>
	//aparece com as informações da imagem
	$('.img-thumbnail').hover(function(){
		$(this).popover('toggle');
	});
</script>
