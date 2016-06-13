<?php

#FLASH ZONE - EXIBE TANTO OS FLASH QUANTO OS DIRETOS - AS MSGS DE SESSÃO SOBREPÕEM AS DIRETAS

if($message = $this->session->flashdata('mensagem')){

	if($msg_class = $this->session->flashdata('msg_class')){}else{$msg_class = "info_msg";}
	
	echo "<div class='alert $msg_class'>".urldecode($message)."</div>";
	
}elseif(isset($msg) AND $msg!=''){
	
	if(!isset($msg_class)){$msg_class = "info_msg";}
	
	echo "<div class='alert $msg_class'>$msg</div>";
	
}

?>