<?php

class Configurations extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
	//list empresas dashboard
    function listEmpresas()
    {	
		return $this->db->select('id,nome_fantasia')->order_by('nome_fantasia','ASC')->get('empresas')->result();
	}
	
	//list funcionarios dashboard
    function listFuncionarios($id_empresa=null)
    {	
		return $this->db->select('id')->where('id_empresa',$id_empresa)->limit(200)->get('funcionarios')->result();
	}
	
	//list socios dashboard
    function listSocios($id_empresa=null)
    {	
		return $this->db->select('id')->where('id_empresa',$id_empresa)->limit(200)->get('socios_empresas')->result();
	}
    
	//list count comments id logo
    function listDestaques()
    {	
		$this->db->select('id, media_url, DATE_FORMAT(data_entrada, "%d/%m às %H:%i") AS data_entrada, ativo', FALSE);
		$this->db->from('sites_dashboard_media');
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
	}
	
	//update status ativo sites dashboard
    function updateAtivo()
    {	
		extract($this->input->post());
		
		$this->sql = "UPDATE sites_dashboard_media SET ativo = IF(ativo>0,0,1) WHERE id = $id_ativo";
		$this->db->query($this->sql);
		
		die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
	}
	
	//update status ativo sites dashboard
    function remove()
    {	
		extract($this->input->post());
		
		if($this->db->delete('sites_dashboard_media', array('id' => $id_remove)))
		{
			if(is_file(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/configuration/sites/'.stripslashes(trim("$name_file"))))
			{
				unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/configuration/sites/'.stripslashes(trim("$name_file")));
			}
			return true;
		}
		
		return false;
	}
	
	//update status ativo sites dashboard
    function likeLayout()
    {	
		extract($this->input->post());
		
		//veririfo se usuario ainda nao curtiu
		$this->db->select('id');
		$this->db->from('likes');
		$this->db->where('id_elemento',$id_site_layout);
		$this->db->where('class','likeLayout');
		$this->db->where('id_usuario',$this->session->userdata('mw_user_id'));
		
		$query = $this->db->get();
		
		if($query->num_rows()<1)
		{
			$this->sql = " UPDATE sites_dashboard_media SET likes = likes+1 WHERE id = '$id_site_layout' ";
			$this->db->query($this->sql);
		
			if($this->db->affected_rows())
			{
				$id_usuario = $this->session->userdata('mw_user_id');
				$this->sql = " INSERT INTO likes VALUES('','$id_site_layout','likeLayout','$id_usuario',NOW()) ";
				$this->db->query($this->sql);
				
				if($this->db->affected_rows())
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	//get my like
    function getMyLike($id=null, $element=null)
    {	
		//veririfo se usuario ainda nao curtiu
		$this->db->select('id');
		$this->db->from('likes');
		$this->db->where('id_elemento',$id);
		$this->db->where('class',$element);
		$this->db->where('id_usuario',$this->session->userdata('mw_user_id'));
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
		
		return false;
	}
	
	//seleciono quantos sites estao para enviar e quantos estao em producao
    function getCountSites($user_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$where = '((data_envio < 1 OR data_visualizacao_admin > 0) AND data_finalizacao < 1)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites foram enviados
    function getSitesSendCount($user_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$where = '(data_envio > 0)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites foram finalizados
    function getSitesFinishCount($user_id=null,$unity_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_unidade',$unity_id);
		$where = '(data_envio > 0)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao para enviar
    function getCountSitesToSend($user_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$where = '(data_envio IS NULL OR data_envio < 1)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao para enviar e quantos estao em producao
    function getCountSitesToDo($user_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		//$this->db->where('id_usuario',$user_id);
		$where = '(data_envio > 0 AND data_visualizacao_admin < 1)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao em producao
    function getCountSitesInProd($user_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$where = '(data_visualizacao_admin > 0 AND data_finalizacao < 1)';
		$this->db->where($where);
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao em producao
    function getCountLogosToSend($user_id=null,$unity_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('logos');
		$this->db->where('id_usuario',$user_id);
		$this->db->where('id_unidade',$unity_id);
		$where = '(enviado > 0)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	// get user to edit
	function getUnitysLogin()
	{
		/*
		$this->db->select('id, nome_unidade');
		$this->db->from('unidades');
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
		*/
	}
	
	//seleciono quantos sites estao em producao
    function getCountSitesSend($user_id=null,$unity_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$this->db->where('id_unidade',$unity_id);
		$where = '(data_envio > 0 AND data_visualizacao_admin < 1)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao finalizados
    function getCountSitesFinish($user_id=null,$unity_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$this->db->where('id_unidade',$unity_id);
		$where = '(data_finalizacao > 0 AND data_envio_alteracao < 1)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao finalizados
    function getCountSitesAlter($user_id=null,$unity_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('sites');
		$this->db->where('id_usuario',$user_id);
		$this->db->where('id_unidade',$unity_id);
		$where = '(data_finalizacao > 0 AND data_envio_alteracao > 0)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao em manutencao
    function getCountSitesInSupport($user_id=null,$unity_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('manutencoes');
		$this->db->where('id_usuario',$user_id);
		$this->db->where('id_unidade',$unity_id);
		$where = '(data_finalizacao < 1)';
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos sites estao em producao
    function getCountRyalties($franquia_id=null)
    {	
        
                return false;
		$this->db->select('count(id) as reg');
		$this->db->from('royalties');
		if($this->session->userdata('ct_nivel_acesso')<4)
		{
			$this->db->where('franquia_id',$franquia_id);
		}
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
	//seleciono quantos registro tem cada mes
    function getCountListRoyalty($month=null,$year=null,$franquia_id=null)
    {	
		$this->db->select('count(id) as reg');
		$this->db->from('royalties');
		$this->db->where('MONTH(data_entrada)',$month);
		$this->db->where('YEAR(data_entrada)',$year);
		if($this->session->userdata('ct_nivel_acesso')<4)
		{
			$this->db->where('franquia_id',$franquia_id);
		}
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->reg;
		}
	}
	
}


/* End of file login.php */
/* Location: ./application/models/login.php */