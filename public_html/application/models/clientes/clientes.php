<?php
class Clientes extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}
	private function inserirClienteFisico($cod_cliente,$dados_cliente){
		extract($dados_cliente);
		$sql = 'INSERT INTO `pessoa_fisica`(`cod_cliente`, `rg`, `estado_rg`, `cpf`, `sexo`, `data_nasc`)  values ("'.$cod_cliente.'","'.$rg.'","'.$estado_rg.'","'.$cpf.'","'.$sexo.'","'.$data_nasc.'")';
		$query = $this->db->query($sql);
		if(!$query){
			return false;
		}
		else{
			return true;
		}
	}
	private function inserirClienteJuridico($cod_cliente,$dados_cliente){
		extract($dados_cliente);
		$sql = 'INSERT INTO `pessoa_juridica`(`cod_cliente`, `cnpj`, `ra_social`)  values ("'.$cod_cliente.'","'.$cnpj.'","'.$ra_social.'")';
		$query = $this->db->query($sql);
		if(!$query){
			return false;
		}
		else{
			return true;
		}
	}
	public function inserirCliente($dados_cliente) {
		$dados = $dados_cliente;
		extract($dados);
		$sql = 'INSERT INTO `clientes`(
					`nome`,
					`tipo`,
					`cep`,
					`endereco`,
					`complemento`,
					`bairro`,
					`cidade`,
					`estado`,
					`data_cad`,
					`data_exp`,
					`ativo`,
					`last_mod`)
		 		values (
			 		"'.$nome.'",
					"'.$pessoa.'",
					"'.$Cep.'",
					"'.$endereco.'",
					"'.$complemento.'",
					"'.$bairro.'",
					"'.$cidade.'",
					"'.$estado.'",
					"'.date('Y-m-d h:i:s').'",
					"2020-'.date('m-d h:i:s').'",
					"0",
					"'.date('Y-m-d h:i:s').'
		 		")';
		$query = $this->db->query($sql);
		if(!$query){
			return false;
		}
		else{
			$last_id = $this->db->insert_id();
			$retorna = $last_id;
			if ($pessoa == 'F') {
				$inseriu_pessoa = $this->inserirClienteFisico($last_id,$dados_cliente);
			}
			else{
				$inseriu_pessoa = $this->inserirClienteJuridico($last_id,$dados_cliente);
			}
			if ($inseriu_pessoa === true) {
				return $retorna;
			}
			return false;
		}
	}

	public function getClienteFisico($cod_cliente) {
			//$this->db->select('rg,estado_rg,cpf,sexo,data_nasc');
			$this->db->select('*');
				$this->db->where('cod_cliente',$cod_cliente);
				$query = $this->db->get('pessoa_fisica');
				if($query->num_rows()>0){
					$result = (array)$query->result();
					foreach($result as $key=>$value){
						$result[$key] = (array)$result[$key];
					}
					//die(print_r($result));
					return $result;
				}
				else{
					return array(array('rg'=>'','estado_rg'=>'','cpf'=>'' ,'sexo'=>'M' ,'data_nasc'=>''));
				}
				
			return $retorno;
	}

	public function getClienteByUser($user_id) {
		$this->db->select('a.cod_cliente,a.nome,a.tipo');
		$this->db->from('clientes a');
		$this->db->join('cliente_adm b','a.cod_cliente = b.cod_cliente','inner');
		$this->db->where('b.user_id',$user_id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$result = (array)$query->result();
			foreach($result as $key=>$value){
				$result[$key] = (array)$result[$key];
			}
			//die(print_r($result));
			return $result;
		}
		else{
			return array();
			//return array(array( 'cod_cliente' => '' ,'nome' => '' ) );
		}
		return $retorno;
	}

	public function getAllfisicos() {
		$sql = "select * from clientes a inner join pessoa_fisica b on (a.cod_cliente = b.cod_cliente)";
		$sql = mysql_query($sql);
		$resultaf = array();
		while($line = mysql_fetch_array($sql)){
		$resultaf[] = $line;
		}
			return $resultaf;
	}

	public function getAllJuridicos() {
		$sql = "select * from clientes a inner join pessoa_juridica b on (a.cod_cliente = b.cod_cliente)";
		$sql = mysql_query($sql);
		$resulta = array();
		while($line = mysql_fetch_array($sql)){
		$resulta[] = $line;
		}
			return $resulta;
	}
	public function getClienteJuridico($cod_cliente) {
			//$this->db->select('rg,estado_rg,cpf,sexo,data_nasc');
			$this->db->select('*');
				$this->db->where('cod_cliente',$cod_cliente);
				$query = $this->db->get('pessoa_juridica');
				if($query->num_rows()>0){
					$result = (array)$query->result();
					foreach($result as $key=>$value){
						$result[$key] = (array)$result[$key];
					}
					//die(print_r($result));
					return $result;
				}
				else{
					return array(array('cnpj'=>'','ra_social'=>''));
				}
				
			return $retorno;
	}
	public function getAllClients() {
			$this->db->select('cod_cliente,nome,cep,tipo,data_cad,data_exp');
			$this->db->order_by("cod_cliente", "desc"); 
				$query = $this->db->get('clientes');
				if($query->num_rows()>0){
					$result = (array)$query->result();
					foreach($result as $key=>$value){
						$result[$key] = (array)$result[$key];
					}
					//die(print_r($this->dbselect));
					return $result;
				}
				else{
					return array(0 =>array('cod_cliente' =>'', 'nome' =>'', 'cep' =>'', 'tipo' =>'', 'data_cad' =>'', 'data_exp' =>'') );
				}
				
			return $retorno;
	}

	public function getClientbyCod($cod_cliente,$tipo) {
		$this->db->select('a.*,b.*');
		if(trim($tipo) == 'F'){
			$this->db->join('pessoa_fisica b','a.cod_cliente = b.cod_cliente','inner');
		}
		else if(trim($tipo) == 'J'){
			$this->db->join('pessoa_juridica b','a.cod_cliente = b.cod_cliente','inner');
		}
		else{
			return false;
		}
			$this->db->where('a.cod_cliente',$cod_cliente);
			$query = $this->db->get('clientes a');
			if($query->num_rows()>0){
				$result = (array)$query->result();
				foreach($result as $key=>$value){
					$result[$key] = (array)$result[$key];
				}
				//die(print_r($this->dbselect));
				return $result;
			}
			else{
				//return array(0 =>array('cod_cliente' =>'', 'nome' =>'', 'cep' =>'', 'tipo' =>'', 'data_cad' =>'', 'data_exp' =>'') );
				return array();
			}
			
		return $retorno;
	}

	public function getAllClientsAdvanced($cod_cliente) {
	
		
				$this->db->select('*');
				$this->db->where('cod_cliente',$cod_cliente);
				$query = $this->db->get('clientes');
				if($query->num_rows()>0){
					$result = (array)$query->result();
					foreach($result as $key=>$value){
						$result[$key] = (array)$result[$key];
					}
					//die(print_r($result));
					return $result;
				}
				else{
					return false;
				}
				
			return $retorno;
		
	}

	public function updateClient($cod_cliente,$nome,$cep,$tipo,$data_cad,$data_exp){
		
		$query = 'update clientes set nome = "'.$nome.'", cep = "'.$cep.'", tipo = "'.$tipo.'", data_cad = "'.$data_cad.'", data_exp = "'.$data_exp.'"  where cod_cliente = '.$cod_cliente;
		//die($query);
		if(mysql_query($query)){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function updateClientAvancado($cod_cliente,$nome,$cep,$endereco,$complemento,$bairro,$cidade,$estado,$last_mod_by,$ativo = null){
		if($ativo == null){
			$ativo = 1;
		}	 		 	 	 	 	 	 	
		$query = 'update clientes set nome = "'.$nome.'", cep = "'.$cep.'", endereco = "'.$endereco.'", complemento = "'.$complemento.'", bairro = "'.$bairro.'", cidade = "'.$cidade.'", estado = "'.$estado.'",last_mod = "'.date('Y-m-d h:i:s').'", last_mod_by = "'.$last_mod_by.'" , ativo = "'.$ativo.'" where cod_cliente = '.$cod_cliente;
		//die($query);
		if(mysql_query($query)){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function updateClientAvancadoFisico($cod_cliente,$rg,$estado_rg,$cpf,$sexo,$data_nasc){
  	 	$this->db->select('cod_cliente');
  	 	$this->db->where('cod_cliente', $cod_cliente);
		$query = $this->db->get('pessoa_fisica');
			if($query->num_rows()>0){
				$query = 'update pessoa_fisica set rg = "'.$rg.'", estado_rg = "'.$estado_rg.'", cpf = "'.$cpf.'", sexo = "'.$sexo.'", data_nasc = "'.$data_nasc.'"  where cod_cliente = '.$cod_cliente;
			}
			else{
				$query = 'INSERT INTO `pessoa_fisica`(`cod_cliente`, `rg`, `estado_rg`, `cpf`, `sexo`, `data_nasc`)  values ("'.$cod_cliente.'","'.$rg.'","'.$estado_rg.'","'.$cpf.'","'.$sexo.'","'.$data_nasc.'")';
			}
		
		if(mysql_query($query)){
			//die($query);
			return 'true';
		}
		else{
			//die($query);
			return 'false';
		}

	}

	public function updateClientAvancadoJuridico($cod_cliente,$cnpj,$ra_social){
  	 	$this->db->select('cod_cliente');
  	 	$this->db->where('cod_cliente', $cod_cliente);
		$query = $this->db->get('pessoa_fisica');
			if($query->num_rows()>0){
				$query = 'UPDATE pessoa_juridica set cnpj = "'.$cnpj.'", ra_social = "'.$ra_social.'" where cod_cliente = '.$cod_cliente;
			}
			else{
				$query = 'INSERT INTO `pessoa_juridica`(`cod_cliente`, `cnpj`, `ra_social`)  values ("'.$cod_cliente.'","'.$cnpj.'","'.$ra_social.'")';
			}
		
		if(mysql_query($query)){
			//die($query);
			return 'true';
		}
		else{
			//die($query);
			return 'false';
		}

	}

	public function addClient($nome,$tipo){
		
		$query = 'insert into clientes (nome,tipo,data_cad,ativo) values ("'.$nome.'","'.$tipo.'",CURDATE(),1)';
		//die($query);
		if(mysql_query($query)){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function updateUserAvancado($dados){
		//die(print_r($dados));
		$this->db->where('cod_cliente', $dados['cod_cliente']);
		if($this->db->update('clientes', $dados)){
			return 'true';
		} 
		else{
			return 'false';
		}

	}
}





/* End of file searsh.php */

/* Location: ./application/models/categoria/Search.php */