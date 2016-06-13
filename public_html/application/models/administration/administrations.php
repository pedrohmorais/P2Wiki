<?php

class Administrations extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
    
	//list count comments id logo
	
    function listUnidades()
    {	
		$this->db->select('u.id, u.nome_unidade,u.email_unidade, uv.ativo', FALSE);
		$this->db->from('unidades u');
		$this->db->join('unidades_verificacao uv', 'uv.id_unidade = u.id', 'LEFT');
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
	}
		
	//update status ativo unidades
    function updateStatusUnidade()
    {	
		extract($this->input->post());
		
		$this->db->select('1', FALSE);
		$this->db->from('unidades_verificacao');
		$this->db->where('id_unidade',$id_unidade);
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$this->db->where('id_unidade',$id_unidade);
			if($this->db->delete('unidades_verificacao'))
			{
				return true;
			}
		}else{
			
			$data = array(
			   'id_unidade' => $id_unidade
			   ,'codigo' => crc32(date('dmYHms'))
			   ,'ativo' => 1
			);
			
			$this->db->set('data_entrada', 'NOW()', FALSE);
			$this->db->insert('unidades_verificacao', $data); 
			
			if($this->db->affected_rows())
			{
				return true;
			}
		}
		
		$this->sql = "UPDATE unidades_verificacao SET ativo = IF(ativo>0,0,1) WHERE id_unidade = $id_unidade";
		$this->db->query($this->sql);
		
		die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
	}
	
	//create and edit unity
	public function updateUnity()
	{
		extract($this->input->post());
		
		$this->db->select('id');
		$this->db->from('unidades');
		$this->db->where('id',$id_unidade);
		$query = $this->db->get();
		
		if($query->num_rows < 1)
		{
			$data = Array(
				 'id'=>$id_unidade
				,'main_user_id'=>$main_user_id
				,'nome_unidade'=>$nome_unidade
				,'cod_unidade'=>$cod_unidade
				,'email_unidade'=>$email_unidade
			);
			$this->db->insert('unidades', $data);
			
			if($this->db->affected_rows())
			{
				return $this->db->insert_id();
			}else{
				return false;
			}
		}else{
			return false;
		}
	
	}
	
	//remove unity
	public function removeUnity()
	{
		extract($this->input->post());
		
		$this->db->where('id', $id_unidade);
		
		if($this->db->delete('unidades'))
		{
			//remove unidades_verificacao
			$this->db->where('id_unidade', $id_unidade);
			if($this->db->delete('unidades_verificacao'))
			{
				return true;
			}
		}else{
			return false;
		}
	
	}
	
	//list registers unitys
    public function getUnitysDB2($id_unidade=null)
    {	
		$DB_2 = $this->load->database('DB_2', TRUE);
		
		if($id_unidade)
		{
			$DB_2->select('franquia_id, main_user_id, nome_franquia, cod_franquia, f_email');
			$DB_2->from('franquias');
			$DB_2->where('franquia_id',$id_unidade);
		}else{
			$DB_2->select('franquia_id,nome_franquia');
			$DB_2->from('franquias');
		}
		
		$query = $DB_2->get();
		
		return $query->result();
	}
	
	public function getUserDB2($id_user=null)
    {	
		$DB_2 = $this->load->database('DB_2', TRUE);
		$DB_2->select('nome');
		$DB_2->from('usuarios');
		$DB_2->where('user_id',$id_user);
		$DB_2->limit('1');
		$query = $DB_2->get();
		
		$result = $query->result();
		return $result[0]->nome;
	}		
}


/* End of file login.php */
/* Location: ./application/models/login.php */