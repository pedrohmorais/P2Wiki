<?php

class Basecentrals extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
	}
    
	//list registers sites
    public function getSitesDB2()
    {	
		$DB_2 = $this->load->database('DB_2', TRUE);
		$DB_2->select('site_name');
		$DB_2->from('se_sites');
		$DB_2->limit('5');
		$query = $DB_2->get();
		
		/*foreach($query->result() AS $val)
		{
			echo $val;
		}*/
		//return $query->result();
		//exit;
	}
	
	
}


/* End of file login.php */
/* Location: ./application/models/login.php */