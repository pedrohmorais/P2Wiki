<?php
if($_POST)
{
	extract($_POST);
	if(strpos($dominio,'http://')===false)$dominio="http://".$dominio;
	//Validate domain
	if(preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $dominio)) {
		echo 1;
	} else {
		echo 0;
	}
}