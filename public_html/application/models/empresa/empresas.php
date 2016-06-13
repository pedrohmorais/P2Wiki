<?php

class Empresas extends CI_Model {

	public $table = 'empresas';
	public $table_conta = 'empresas_contas';
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
    
	//lista empresas
    function listAllEmpresas($sLimit=null,$offset=null,$sWhere=null)
    {	
		$where = "e.id > 0";
		if(!empty($sWhere))
		{
			$where = "e.id = '$sWhere' OR e.nome_fantasia REGEXP '$sWhere' OR e.cnpj REGEXP '$sWhere' OR e.email REGEXP '$sWhere' ";
		}
		return $this->db
				->select('e.id, e.nome_fantasia, e.cnpj, e.email, e.alias, em.url_media')
				->from($this->table.' e')
				->join('empresas_media em','em.id_empresa = e.id','LEFT')
				->where($where)
				->order_by('e.nome_fantasia','ASC')
				->limit($offset,$sLimit)
				->get()
				->result();
	}
	
	function listAllContas()
    {	
		return $this->db->order_by('nome_referencia','ASC')->get($this->table_conta)->result();
	}
	
	//edita empresas
    function edita($data=null,$id=null)
    {	
		if(!$id)
		{
			//cria
			$prepare_query = prepareQuery($data);
			
			$name_equal = $this->db->like('nome_fantasia', $data['nome_fantasia'],'before')->get($this->table)->row();

			if(empty($name_equal->nome_fantasia))
			{
				if($this->db->set('data_cadastro', 'NOW()', FALSE)->insert($this->table, $prepare_query))
				{
					$result = $this->db->select('alias')->where('id',$this->db->insert_id())->limit(1)->get($this->table)->row();
					return $result->alias;
				}
			}else{
				return false;
			}
		}else{
			//atualiza
			$prepare_query = prepareQuery($data);
			if($this->db->where('id', $id)->update($this->table, $prepare_query))
			{
				$result = $this->db->select('alias')->where('id',$id)->limit(1)->get($this->table)->row();
				return $result->alias;
			}
		}
		return false;
	}
	
	//edita conta
    function edita_conta($data=null,$id=null)
    {	
		if(!$id)
		{
			//cria
			$prepare_query = prepareQuery($data);
			$this->db->insert($this->table_conta, $prepare_query);
			return $this->db->insert_id();
		}else{
			//atualiza
			$prepare_query = prepareQuery($data);
			if($this->db->where('id', $id)->update($this->table_conta, $prepare_query))return $id;
		}
		return false;
	}
	
	//remove conta
    function remove_conta($id=null)
    {	
		$this->db->where('id', $id)->delete($this->table_conta);
        return $this->db->affected_rows();
	}
	
	//remove empresa
    function remove_empresa($id=null)
    {	
		$this->db
			->select('id, url_media')
			->from('empresas_media')
			->where('id_empresa', $id); 
		
		$query = $this->db->get(); 
		
		//possui registro - apenas altera a foto e remove a antiga do server
		if($query->num_rows()>0)
		{
			$result = $query->result();
			
			if($result[0]->url_media <> 'icon-default.png')
			{
				//apago a imagem se ela existe e nao for default
				if(is_file(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/company/original/'.stripslashes(trim($result[0]->url_media))))
				{
					unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/company/original/'.stripslashes(trim($result[0]->url_media)));
					unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/company/thumb/'.stripslashes(trim($result[0]->url_media)));
				}
			}
			
			$this->db->where('id', $id)->delete($this->table);
			if($this->db->affected_rows())
			{
				$this->db->where('id_empresa', $id)->delete('empresas_media');
				return true;
			}
		}
		$this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
	}
	
	//resgata dados
    function getdados($id=null,$table=null,$order=null)
    {	
		$table = !empty($table) ? $table : $this->table;
		if($id)
		{
			$result = '';
			$dados = $this->db
						->select('e.*,em.url_media',FALSE)
						->from($table.' e')
						->join('empresas_media em','em.id_empresa = e.id', 'LEFT')
						->where('e.id', $id)
						->limit(1)
						->get()
						->row();
			
			foreach($dados AS $k=>$v)
			{
				if(!empty($v))
				{
					//identifica e trata data
					if(substr_count($v, '-')>1)
					{
						$v = explode('-',$v);
						if(isset($v[0]) AND strlen($v[0])==4)$v = $v[2].'/'.$v[1].'/'.$v[0];
					} 
					
					$result[$k] = "$v";
				}
				else 
					$result[$k] = "";
			}		
			return $result;
		}
		return false;
	}
	
	//seleciona socios da empresa atraves do ID empresa
	function get_atribuicoes_sociedade($id_empresa=null)
	{
		$array=null;
		$data = $this->db->select('id_socio')->where('id_empresa',$id_empresa)->get('socios_empresas')->result();
		foreach($data AS $k=>$v)
		{
			$socio = $this->db->select('nome')->where('id',$v->id_socio)->get('socios')->row();
			$array[]=strtoupper($socio->nome);
		}
		if(is_array($array))return join(',',$array);
	}
	
	//seleciona ID atraves do alias
	function selectID_BY_ALIAS($alias=null)
	{
		$result = $this->db->select('id')->like('alias',$alias,'before')->limit(1)->get($this->table)->row();
		return $result->id;
	}
}


/* End of file empresas.php */
/* Location: ./application/models/login.php */