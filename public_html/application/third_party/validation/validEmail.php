<?php
function validaEmail($mail){
	if(preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{2,})(\.[[:lower:]]{2,3})(\.[[:lower:]]{2})?$/", $mail)) {
		return true;
	}else{
		return false;
	}
}
?>