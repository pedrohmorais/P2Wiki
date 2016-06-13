<?php

/**
 * Script
 *
 * Generates script to a JS file
 *
 * @access	public
 * @param	mixed	javascript hrefs or an array
 * @param	string	language
 * @param	string	type
 * @param	string	src
 * @return	string
 */
 
if ( ! function_exists('script_tag'))
{
	function script_tag($src = '', $language = 'javascript', $type = 'text/javascript')
	{
		$CI =& get_instance();

		$script = '<script';

		if (is_array($src))
		{
			foreach ($src as $k=>$v)
			{
				$script .= " $k=\"$v\"";
			}

			$script .= ">";
		}
		else
		{
			
			$script .= ' src="'.$src.'" type="'.$type.'" ';

			$script .= '>';
		}

		
		$script .= "</script>";
		return $script;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('css_gradient_background'))
{
	function css_gradient_background($color1, $color2 = '')
	{
			$css = '';
			
			if($color2!=''){
				
				$css .= "
				background: -webkit-gradient(linear, left top, left bottom, from(#$color1), to(#$color2)); ";
				$css .= "
				background: -moz-linear-gradient(top,  #$color1,  #$color2);";
				$css .= "
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#$color1', endColorstr='#$color2');";				
				$css .= "
				background: -ms-linear-gradient(top,  #$color1 0%,#$color1 31%,#$color1 32%,#$color2 100%);";
				/*$css .= "
				background: -o-linear-gradient(left,  rgba(0,0,0,1) 0%,rgba(0,0,0,0.03) 31%,rgba(0,0,0,0) 32%,rgba(0,0,0,0) 100%);";*/
				/*$css .= "
				background: -ms-linear-gradient(to right,  #$color1 0%,#$color1 31%,#$color2 32%,#$color2 100%);";*/
				
				/*
				
				
				$css .= "
				background: #fff;";
				*/
			}
			
			if($color2==''){$css .= "background: #$color1;";}
			
			return $css; 	
	}
}


// ------------------------------------------------------------------------

if ( ! function_exists('css_image_background'))
{
	function css_image_background($img, $position='D')
	{
			$css = '';
			$css .= "background-image:url('$img'); ";
			if($position=='R'){$css .= "background-repeat: repeat; ";}
			elseif($position=='C'){$css .= "background-repeat: no-repeat; background-position: top center; ";}
			elseif($position=='RY'){$css .= "background-repeat: repeat-y;";}
			elseif($position=='RX'){$css .= "background-repeat: repeat-x;";}
			else{$css .= "background-repeat: no-repeat; background-position: top left; ";}
			
			return $css; 	
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('renderCBlock'))
{
	function renderCBlock($site, $contentArray)
	{
			$block = '';
			
			$layout = $contentArray['tipo'];
      
      if($layout == 'area_restrita_nova' && (!isset($contentArray['bloqueioArea']))){								  
        return false;
      } 
			
			if($layout=='galeria' and isset($contentArray['items'])){
				include("content/galeria.php");
			}else{
				include("content/$layout.php");
			}
	
			return $block; 	
	}
}

if ( ! function_exists('html2rgb'))
{
	function html2rgb($color)
	{
	    if ($color[0] == '#')
	        $color = substr($color, 1);
	
	    if (strlen($color) == 6)
	        list($r, $g, $b) = array($color[0].$color[1],
	                                 $color[2].$color[3],
	                                 $color[4].$color[5]);
	    elseif (strlen($color) == 3)
	        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	    else
	        return false;
	
	    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
	
	    return array($r, $g, $b);
	}
}

if ( ! function_exists('renderPhoneImg'))
{
	function renderPhoneImg($colorCode){
			
			$color = html2rgb($colorCode);
		
			$imgname = site_url().'application/third_party/Fonts/fone.gif';
			
			$im = imagecreatefromgif ($imgname);
			
			imagetruecolortopalette($im,false, 255);
			
			$index = imagecolorclosest ( $im,  0,0,0 ); // get White COlor
			
			imagecolorset($im,$index,$color[0],$color[1],$color[2]); // SET NEW COLOR
			
			$imgNewname = "application/third_party/Fonts/".$colorCode.'.gif';
			$imgNewPath = site_url().'application/third_party/Fonts/'.$colorCode.'.gif';
			
			imagegif($im, $imgNewname); // save image as gif			
			
			return "<img src='$imgNewPath'>"; 	
	}
}

/*
if ( ! function_exists('renderSiteDinamic'))
{
	function renderSiteDinamic($url = NULL) 
	{ 
		$curl_ls = curl_init(); 
		curl_setopt($curl_ls,CURLOPT_URL,$url); 
		curl_setopt($curl_ls,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($curl_ls,CURLOPT_ENCODING,'gzip');
		$output = curl_exec($curl_ls); 
		curl_close($curl_ls); 
		return $output;
	} 

	$contents = 'http://centralliguesite.com.br/wiki/application/helpers/sitecake/index.php';

	#SAÍDA VISUAL
	//return renderSiteDinamic($contents);

}
*/
/* End of file MY_html_helper.php */
/* Location: ./application/helpers/MY_html_helper.php */