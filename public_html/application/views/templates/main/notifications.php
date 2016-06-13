<?php

#FLASH ZONE - EXIBE TANTO OS FLASH QUANTO OS DIRETOS - AS MSGS DE SESSÃO SOBREPÕEM AS DIRETAS

if($message = $this->session->flashdata('message')){

	if($this->session->flashdata('msg_header')) 
		$msg_header = $this->session->flashdata('msg_header');
	else
		$msg_header = 'Alerta!';
	
	if($msg_class = $this->session->flashdata('msg_class')){}else{$msg_class = "growl-warning";}
	
	echo "<script type='text/javascript'>
		$(document).ready(function(){
			$.jGrowl('$message', { sticky: false, theme: '$msg_class', header: '$msg_header' });
		});
		</script>";	
	
}elseif(isset($msg) AND $msg!=''){
	
	if(!isset($msg_class)){$msg_class = "info_msg";}
	if(!isset($msg_header)){$msg_header = "Alerta!";}
	
	if($this->session->flashdata('msg_header')) 
		$msg_header = $this->session->flashdata('msg_header');
	
	echo "<script type='text/javascript'>
		$(document).ready(function(){
			$.jGrowl('$msg', { sticky: false, theme: '$msg_class', header: '$msg_header' });
		});
		</script>";	
	
}

?>