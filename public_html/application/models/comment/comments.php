<?php

class Comments extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
    
	//list count comments id logo
    function countLogos()
    {	
		$this->db->select('count(id) AS logos',FALSE);
		$this->db->from('logos');
		$this->db->where('id_usuario', $this->session->userdata('mw_user_id'));
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->logos;
		}
		
		return false;
	}
	
	//create comment logo
    function createLogoComment()
    {	
		extract($this->input->post());
		
		$resposta = (isset($resposta) AND $resposta>0) ? $resposta : '0';
		
		if(!empty($id_logos))
		{
			// cria tabela logos
			$data = array(
			   'id_logos' => $id_logos
			   ,'id_usuario' => $this->session->userdata('mw_user_id')
			   ,'mensagem' => "$logo_comment_msg"
			   ,'resposta' => "$resposta"
			);
			
			$this->db->set('data_entrada', 'NOW()', FALSE);
			$this->db->insert('logos_comment', $data); 
			
			if($this->db->affected_rows())
			{
				return true;
			}
		}
		
		return false;
	}
	
	//get data of logo comment id
	function getDataLogosComments($id_logos=null)
	{
		$this->db->select('id');
		$this->db->from('logos_comment');
		$super_query = $this->db->get();
		
		$s=$joins=null;
		foreach($super_query->result() AS $v)
		{
			$joins .= $s.$v->id;
			if(!$s)$s=',';
		}
		
		if(!empty($joins))
			$joins = "OR lc.id IN ($joins)";
		
		$this->db->select('lc.*, u.nome, um.url_media, DATE_FORMAT(lc.data_entrada, "%d/%m às %H:%iHs") AS data_entrada', FALSE);
		$this->db->from('logos_comment lc');
		$this->db->join('usuarios u', 'u.id = lc.id_usuario');
		$this->db->join('usuarios_media um', 'um.id_usuario = lc.id_usuario','left');
		$this->db->where('lc.id_logos', $id_logos);
		if($this->Login->loggedUser()->nivel_acesso<3)
		{
			//$this->db->where('lc.id_usuario', $this->session->userdata('mw_user_id'));
		}
		$where = "(resposta = 0 $joins)";
		$this->db->where($where);
		$this->db->order_by("lc.id", "desc"); 
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
    }  
	
	//list count comments id logo
    function listAllLogosComments($id_logos=null)
    {	
		$this->db->select('count(lc.id) AS comments, 
							lc.id_logos',FALSE);
		$this->db->from('logos_comment lc');
		$this->db->join('logos l','l.id = lc.id_logos');
		$this->db->where('l.id', $id_logos);
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->comments;
		}
		
		return false;
	}
	
	//remove comment logo
    function remove($id_comment)
    {	
		if(!empty($id_comment))
		{
			$this->db->where('id', $id_comment);
			if($this->db->delete('logos_comment'))
			{
				$this->db->where('resposta', $id_comment);
				if($this->db->delete('logos_comment'))
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	//create comment site
    function createSiteComment()
    {	
		extract($this->input->post());
		
		if(!empty($id_site))
		{
			// cria tabela logos
			$data = array(
			   'id_site' => $id_site
			   ,'id_usuario' => $this->session->userdata('mw_user_id')
			   ,'mensagem' => "$site_comment_msg"
			);
			
			$this->db->set('data_entrada', 'NOW()', FALSE);
			$this->db->insert('sites_comment', $data); 
			
			if($this->db->affected_rows())
			{
				return true;
			}
		}
		
		return false;
	}
	
	//get comment site
	function getDataSiteComments($id_site=null)
	{
		$this->db->select('id_usuario, mensagem, DATE_FORMAT(data_entrada, "%d/%m às %H:%iHs") AS data_entrada', FALSE);
		$this->db->from('sites_comment');
		$this->db->where('id_site',$id_site);
		$this->db->order_by('id', 'desc'); 
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
    }  
	
	//get last comment site
	function getDataLastSiteComments($id_site=null)
	{
		$this->db->select('id_usuario');
		$this->db->from('sites_comment');
		$this->db->where('id_site',$id_site);
		$this->db->order_by('id', 'DESC'); 
		$this->db->limit(1); 
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->id_usuario;
		}
		
		return false;
    }  
	
}


/* End of file login.php */
/* Location: ./application/models/login.php */