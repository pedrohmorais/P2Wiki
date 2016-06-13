<?php
if($_GET['a'])
{	
	//construtor default
	echo '
		<script type="text/javascript">
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				document.getElementById(responsePlace).innerHTML=xmlhttp.responseText;
				}
			  }
		</script>
	';
	//switch($_GET['a'])
	//{
		//case'sitesDashboard':
			echo '
				<script type="text/javascript">
					var responsePlace = "'.$_GET['p'].'";
					xmlhttp.open("GET","http://www.centralliguesite.com.br/meuweb/rest/'.$_GET['a'].'",true);
					xmlhttp.send();
				</script>
			';
		//break;
	//}
}

?>