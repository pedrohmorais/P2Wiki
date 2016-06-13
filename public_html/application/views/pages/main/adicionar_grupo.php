<h2 style="border-bottom: 1px solid #bbb;margin-bottom:50px;"> Adicionar Grupo</h2>
	<div class="alert alert-success" style="display:none;cursor:pointer;" id="successInsertGrupo" onclick="$(this).fadeOut();" role="alert">Grupo cadastrado com sucesso !</div>
	<div class="alert alert-danger" style="display:none;cursor:pointer;" id="failedInsertGrupo" onclick="$(this).fadeOut();" role="alert">Grupo não cadastrado !</div>
	<form method="POST" action="/grupos/inserir_grupo">
	<table>
		<tr>
			<td style="padding:10px;" class="form-inline">
				<label>Nome do grupo: </label>
			</td>	
			<td class="form-inline" style="width:75%;">
				<input type="text" style="width:100%;" class="form-control" name="nome_grupo"  />
			</td>	
		</tr>
		<tr>
			<td style="padding:10px;" class="form-inline">
				<label>Nome do cliente: </label>
			</td>	
			<td class="form-inline">
				<select name="cod_cliente" id="select_cliente_grupo_add"  class="form-control pull-right" style="min-width: 50%;">
					<?php
						foreach ($this->session->userdata('sg_clientes') as $cliadm) {
							echo '<option value="'.$cliadm['cod_cliente'].'">'.$cliadm['nome'].'</option>' ;
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="form-inline" style="padding-top: 20px;padding-bottom: 20px;">
				<input class="btn btn-default" type="submit" style="width: 100%;" value="Adicionar Contato">
			</td>
		</tr>
	</table>	
<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;"></h2>
<?php
	echo '<script>';
	if ($this->session->flashdata('sg_insert_grupo')) {
		echo 	'$("#successInsertGrupo").html("'.$this->session->flashdata('sg_insert_grupo').'");';
		echo 	'$("#successInsertGrupo").fadeIn();';
	}
	elseif ($this->session->flashdata('sg_erro_insert_grupo')) {
		echo 	'$("#failedInsertGrupo").html("'.$this->session->flashdata('sg_erro_insert_grupo').'");';
		echo 	'$("#failedInsertGrupo").fadeIn();';
	}
	echo '</script>';
?>