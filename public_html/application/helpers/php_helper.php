<?php

/**
 * Prepare Query
 *
 * Generates fields to query
 *
 */
 
if ( ! function_exists('prepareQuery'))
{
	function prepareQuery($data=null)
	{
		$CI =& get_instance();

		$result = $alias = '';
		$s=null;
		if (is_array($data))
		{
			$username_alias = (isset($data['username'])) ? $data['username'] : '';
			$remove_keys = array('username','senha','nova_senha');
			//insert - update
			foreach ($data as $k=>$v)
			{
				if(!in_array($k,$remove_keys))
				{
					if(!empty($v))
					{
						$v = mb_strtolower($v,'UTF-8');
						if(!strpos($v,"/") AND strstr($v,","))
						{
							$detect_value = str_replace("r$ ","",$v);
							$detect_value = str_replace(".","",$detect_value);
							$detect_value = str_replace(",",".",$detect_value);
							$v = ((int)($detect_value)) ? $detect_value : $v;
						}
						//identifica e trata data
						if(!strpos($v,"//") AND substr_count($v, '/')>1)
						{
							$v = explode('/',$v);
							if(isset($v[2]) AND strlen($v[2])==4)$v = $v[2].'-'.$v[1].'-'.$v[0];
						} 
						
						//identifica e trata alias
						if($k == 'nome_fantasia')$alias = reg_caracter($v);
						if($k == 'nome')$alias = reg_caracter($username_alias);
						$result[$k] = "$v";
					}else{
						if($k == "alias"){$result[$k] = "$alias";continue;}
						$result[$k] = "";
					}
				}
			}
		}
		
		//print_r($result);
		//exit;
		
		return $result;
	}
}

// ----------------------------------------------------------------------------------------------

if ( ! function_exists('formata_real'))
{
	function formata_real($number=null)
	{
		$CI =& get_instance();
	
		if($number)
		{ 
			return 'R$ '. number_format($number,2,',','.');
		}
		
		return false;
	}
}

// ----------------------------------------------------------------------------------------------

if ( ! function_exists('get_null_fields'))
{
	function get_null_fields($table=null)
	{
		$CI =& get_instance();
	
		$result= null;
		
		if($table)
		{ 
			$data_null = mysql_query('SHOW COLUMNS FROM ' . $table);
			while($row = mysql_fetch_assoc($data_null))
			{
				$result[$row['Field']] = "";
			}
		}
		
		return $result;
	}
}

// ----------------------------------------------------------------------------------------------

if ( ! function_exists('options_bancos'))
{
	function options_bancos()
	{
		$CI =& get_instance();
		
		$option = '';
		
		$data = mysql_query('SELECT nome_referencia,id FROM empresas_contas WHERE 1 ORDER BY nome_referencia ASC LIMIT 100');
		if(mysql_num_rows($data)>0)
		{
			while($row = mysql_fetch_assoc($data))
			{
				$option .= '<option value="'.$row['id'].'">';
				$option .= strtoupper($row['nome_referencia']);
				$option .= '</option>';
			}
		}
		
		return $option;
	}
}

// ----------------------------------------------------------------------------------------------

if ( ! function_exists('get_empresas'))
{
	function get_empresas($url_last)
	{
		$CI =& get_instance();
		
		$option = $choice_class_second = $url_last_3 = $url_last_2 = '';
		
		if(isset($url_last[2]))$url_last_2 = $url_last[2];
		if(isset($url_last[3]))$url_last_3 = $url_last[3];
		
		$data = mysql_query('SELECT id,nome_fantasia,alias FROM empresas WHERE status=1 ORDER BY nome_fantasia ASC LIMIT 20');
		if(mysql_num_rows($data)>0)
		{
			while($row = mysql_fetch_assoc($data))
			{
				$choice_class_first = ($url_last_2==$row['alias']) ? 'active-second-level' : '';
				$set_id_expand = (strpos($_SERVER['REQUEST_URI'],$row['alias'])) ? 'second-level' : '';
				
				$option .= '<li class="'.$choice_class_first.'"><a class="item-submenu expand" href="" id="'.$set_id_expand.'">'.$row['nome_fantasia'].' <i class="icon-office"></i></a>';
				$option .= '<ul>';
				$option .= '<li class="'.choicesecondclass("a-receber-a-pagar",$url_last_3,$url_last_2,$row['alias']).'"><a class="active level2" href="'.site_url().'intranet/e/'.$row['alias'].'/a-receber-a-pagar"><i class="icon-menu-level2 icon-point-right"></i> A receber / a pagar</a></li>';
				$option .= '<li class="'.choicesecondclass("recebidos-pagos",$url_last_3,$url_last_2,$row['alias']).'"><a class="active level2" href="'.site_url().'intranet/e/'.$row['alias'].'/recebidos-pagos"><i class="icon-menu-level2 icon-thumbs-up2"></i> Recebidos / pagos</a></li>';
				$option .= '<li class="'.choicesecondclass("fornecedores",$url_last_3,$url_last_2,$row['alias']).'"><a class="active level2" href="'.site_url().'intranet/e/'.$row['alias'].'/fornecedores"><i class="icon-menu-level2 icon-truck"></i> Fornecedores</a></li>';
				$option .= '<li class="'.choicesecondclass("funcionarios",$url_last_3,$url_last_2,$row['alias']).'"><a class="active level2" href="'.site_url().'intranet/e/'.$row['alias'].'/funcionarios"><i class="icon-menu-level2 icon-people"></i> Funcionários</a></li>';
				$option .= '<li class="'.choicesecondclass("solicitar-compra",$url_last_3,$url_last_2,$row['alias']).'"><a class="active level2" href="'.site_url().'intranet/e/'.$row['alias'].'/solicitar-compra"><i class="icon-menu-level2 icon-cart3"></i> Solicitar compra</a></li>';
				$option .= '</ul>';
				$option .= '</li>';
			}
		}else{
			$option .= '<li class="'.$choice_class.'"><a class="item-submenu" href="javascript:void(0)">Nennhuma empresa vinculada <i class="icon-warning"></i></a> </li>';
		}	
		
		return $option;
	}
}

function choicesecondclass($string=null,$url_last=null,$url_last2=null,$alias=null)
{
	return (($url_last2=="$alias") AND ($url_last=="$string")) ? 'active-third-level' : '';
}
// ----------------------------------------------------------------------------------------------

if ( ! function_exists('get_empresas_cad'))
{
	function get_empresas_cad()
	{
		$CI =& get_instance();
		
		$option = $option_in = '';
		
		$data = mysql_query('SELECT nome_fantasia,site FROM empresas WHERE status=1 ORDER BY nome_fantasia ASC LIMIT 20');
		if(mysql_num_rows($data)>0)
		{
			$desc_empresa = (mysql_num_rows($data)>1) ? mysql_num_rows($data).' Empresas cadastradas' : mysql_num_rows($data).' Empresa cadastrada';
			while($row = mysql_fetch_assoc($data))
			{
				$site = (!empty($row['site'])) ? "<a href='{$row['site']}' target='_blank'>Acessar site</a>" : 'URL não cadastrada';
				$option_in .= "<li class='li-popover'><i class='office-popover icon-office'></i> <span style='text-transform:capitalize;'>{$row['nome_fantasia']}</span>  <span style='float:right;'>{$site}</span></li>";
			}
			
			$option .= "<a class='a-popover' title=\"+ Informações\" data-container=\"body\" data-toggle=\"popover\" data-html=\"true\" data-placement=\"bottom\" data-content=\"<ul class='li-popover'>$option_in</ul>\" >".$desc_empresa."</a>";
			
		}else{
			$option .= 'Nenhuma empresa cadastrada';
		}	
		return $option;
	}
}

// ----------------------------------------------------------------------------------------------

if ( ! function_exists('getTABS_CONTENT'))
{
	function getTABS_CONTENT($company_alias=null,$action=null)
	{
		$CI =& get_instance();
		
		$content = '<ul class="nav nav-tabs">
						<li class="'.choiceactiveclass("a-receber-a-pagar",$action).'"><a href="'.site_url().'intranet/e/'.$company_alias.'/a-receber-a-pagar"><i class="icon-point-right"></i> A receber / a pagar</a></li>
						<li class="'.choiceactiveclass("recebidos-pagos",$action).'"><a href="'.site_url().'intranet/e/'.$company_alias.'/recebidos-pagos"><i class="icon-thumbs-up2"></i> Recebidos / pagos</a></li>
						
						<li class="'.choiceactiveclass("fornecedores",$action).' dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-truck"></i> Fornecedores <b class="caret"></b>
							</a>
							<ul class="dropdown-menu icons-right">
								<li><a href="'.site_url().'intranet/e/'.$company_alias.'/fornecedores"><i class="icon-list"></i> Ver fornecedores</a></li>
								<li><a href="'.site_url().'intranet/e/'.$company_alias.'/fornecedores/cadastrar-fornecedor"><i class="icon-pencil"></i> Cadastrar novo fornecedor</a></li>
							</ul>
						</li>
						
						<li class="'.choiceactiveclass("funcionarios",$action).' dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-people"></i> Funcionários <b class="caret"></b>
							</a>
							<ul class="dropdown-menu icons-right">
								<li><a href="'.site_url().'intranet/e/'.$company_alias.'/funcionarios"><i class="icon-list"></i> Ver funcionários</a></li>
								<li><a href="'.site_url().'intranet/e/'.$company_alias.'/funcionarios/cadastrar-funcionario"><i class="icon-pencil"></i> Cadastrar novo funcionário</a></li>
							</ul>
						</li>
						<li class="'.choiceactiveclass("solicitar-compra",$action).'"><a href="'.site_url().'intranet/e/'.$company_alias.'/solicitar-compra"><i class="icon-cart3"></i> Solicitar compra</a></li>
						</ul>';
		return $content;
	}
}

function choiceactiveclass($string=null,$action=null)
{
	return ($action=="$string") ? 'active' : '';
}
// ----------------------------------------------------------------------------------------------

if ( ! function_exists('getTABS_CONTENT_SOCIO'))
{
	function getTABS_CONTENT_SOCIO($alias=null,$action=null,$qtd_empresa=null)
	{
		$CI =& get_instance();
		
		$content_ = null;
		
		if(!empty($alias))
		{
			$qtd_empresa = (!empty($qtd_empresa)) ? '<span class="label label-info">'.$qtd_empresa.'</span>' : '';
			$content_ = '<li class="'.choiceactiveclass_SOCIO("minhas-sociedades",$action).'"><a href="'.site_url().'socio/edita/'.$alias.'/minhas-sociedades"><i class="icon-settings"></i> Minhas sociedades '.$qtd_empresa.'</a></li>
						 <li class="'.choiceactiveclass_SOCIO("minhas-conexoes",$action).'"><a href="'.site_url().'socio/edita/'.$alias.'/minhas-conexoes"><i class="icon-bubbles3"></i> Minhas conexões <span class="label label-danger">inativa</span></a></li>';
		}
		
		$content = '<ul class="nav nav-pills nav-justified">
						<li class="'.choiceactiveclass_SOCIO("meu-perfil",$action).'"><a href="'.site_url().'socio/edita/'.$alias.'/meu-perfil"><i class="icon-cogs"></i> Meu perfil</a></li>
						'.$content_.'
					</ul>';
		return $content;
	}
}

function choiceactiveclass_SOCIO($string=null,$action=null)
{
	return ($action=="$string") ? 'active' : '';
}
// ----------------------------------------------------------------------------------------------

function reg_caracter($string){ 
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ,./*'; 
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr    '; 
	$string = utf8_decode($string);     
	$string = strtr($string, utf8_decode($a), $b); 
	$string = strtolower($string); 
	return utf8_encode(str_replace(" ","",$string)); 
} 
// ------------------------------------------------------------------------

/* End of file MY_php_helper.php */
/* Location: ./application/helpers/MY_php_helper.php */