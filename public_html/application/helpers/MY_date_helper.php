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

 

if ( ! function_exists('tableDate'))

{

	function tableDate($dateString, $time = "", $format = "d/m/Y", $firstString=1)

	{
		$date = "";

		if(!$dateString){return '';}

		$CI =& get_instance();
		
		date_default_timezone_set('America/Sao_Paulo');

		if($firstString){$date .= "<span style='display:none;'>$dateString</span>";}

		$date .= date("$format $time", strtotime($dateString));

		return $date;

	}

}


if ( ! function_exists('tableDateTime'))

{

	function tableDateTime($dateString)

	{
		if(!$dateString){return '';}

		$CI =& get_instance();
		
		date_default_timezone_set('America/Sao_Paulo');
		
		$dateFull = explode(' ',$dateString);
		
		$date = explode('-',$dateFull[0]);
		$hour = explode(':',$dateFull[1]);
		
		$date = $date[2].'/'.$date[1].'/'.$date[0];
		$hour = $hour[0].':'.$hour[1].':'.$hour[2];
		
		return $date.' '.$hour;

	}

}

if ( ! function_exists('translateMonth'))

{

	function translateMonth($dateString)

	{
		/**
		*	$dateString = m/Y
		*/
		
		if(!$dateString){return '';}

		$CI =& get_instance();
		
		date_default_timezone_set('America/Sao_Paulo');
		
		$dateFull = explode('/',$dateString);
		
		$date_month = $dateFull[0];
		
		switch($date_month)
		{
			case 1:$date_month='Janeiro';break;
			case 2:$date_month='Fevereiro';break;
			case 3:$date_month='Março';break;
			case 4:$date_month='Abril';break;
			case 5:$date_month='Maio';break;
			case 6:$date_month='Junho';break;
			case 7:$date_month='Julho';break;
			case 8:$date_month='Agosto';break;
			case 9:$date_month='Setembro';break;
			case 10:$date_month='Outubro';break;
			case 11:$date_month='Novembro';break;
			case 12:$date_month='Dezembro';break;
		}
		
		return $date_month.'/'.$dateFull[1];

	}

}

if ( ! function_exists('time_duration'))

{
	function time_duration($seconds, $use = null, $zeros = false)
	{
		$CI =& get_instance();
		
		// Define time periods
		$periods = array (
			'anos'     => 31556926,
			'Meses'    => 2629743,
			'Semanas'  => 604800,
			'dias'      => 86400,
			'horas'     => 3600,
			'minutos'   => 60,
			'segundos'   => 1
			);
	 
		// Break into periods
		$seconds = (float) $seconds;
		$segments = array();
		foreach ($periods as $period => $value) {
			if ($use && strpos($use, $period[0]) === false) {
				continue;
			}
			$count = floor($seconds / $value);
			if ($count == 0 && !$zeros) {
				continue;
			}
			$segments[strtolower($period)] = $count;
			$seconds = $seconds % $value;
		}
	 
		// Build the string
		$string = array();
		foreach ($segments as $key => $value) {
			$segment_name = substr($key, 0, -1);
			$segment = $value . ' ' . $segment_name;
			if ($value != 1) {
				$segment .= 's';
			}
			$string[] = $segment;
		}
	 
		return implode(', ', $string);
	}
}


// ------------------------------------------------------------------------



/* End of file MY_html_helper.php */

/* Location: ./application/helpers/MY_html_helper.php */