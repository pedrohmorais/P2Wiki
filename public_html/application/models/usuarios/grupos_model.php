<?php
class Grupos_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}
	
	public function getGroupsByUser($user_id) {
		$this->db->select('a.group_id,b.Descricao,(select count(user_id) from usuario_grupo where group_id = a.group_id ) as \'integrantes\',b.limite,b.ativo');
		$this->db->from('usuario_grupo a' );
		$this->db->join('grupo b', 'a.group_id = b.user_group','inner');
		$this->db->where('acesso_interno','3'); 
		$this->db->where('user_id',$user_id); 
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
				return false;
			}
			
		return $retorno;
	}

	public function getGroupsParticipante($user_id) {
		$this->db->select('a.group_id,b.Descricao,(select count(user_id) from usuario_grupo where group_id = a.group_id ) as \'integrantes\',b.limite,b.ativo,a.acesso_interno as \'acesso_interno\'');
		$this->db->from('usuario_grupo a' );
		$this->db->join('grupo b', 'a.group_id = b.user_group','inner');
		$this->db->where_in('acesso_interno',array('1','2'));
		$this->db->where('user_id',$user_id); 
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
				return false;
			}
			
		return $retorno;
	}

	public function getAllUsersInAGroup($group_id){
		//Query Exemplo
		//select a.nome as nome ,a.username as username,a.email as email ,b.acesso_interno as acesso_interno from usuarios a inner join usuario_grupo b  on a.user_id = b.user_id where b.group_id = 2 and a.ativo = 'S'
		$this->db->select('b.user_id as user_id,a.nome as nome ,a.username as username,a.email as email ,b.acesso_interno as acesso_interno');
		$this->db->from('usuarios a');
		$this->db->join('usuario_grupo b', 'a.user_id = b.user_id','inner');
		$this->db->where('a.ativo','S'); 
		$this->db->where('b.group_id',$group_id); 
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
				return false;
			}
			
		return $retorno;
	}

	public function getGroupById($group_id){
		//Query Exemplo
		//select a.descricao as descricao, count(b.user_id) as usuarios, a.limite as limite,a.ativo as ativo from grupo a inner join usuario_grupo b  on a.user_id = b.user_id where b.group_id = 2 
		$this->db->select('a.descricao as descricao, count(b.user_id) as usuarios, a.limite as limite,a.ativo as ativo ,c.nome as nome_cliente,c.cod_cliente as cod_cliente');
		$this->db->from('grupo a');
		$this->db->join('usuario_grupo b', 'a.user_group = b.group_id','inner');
		$this->db->join('clientes c', 'a.cod_cliente = c.cod_cliente','inner');
		$this->db->where('b.group_id',$group_id); 
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
				return false;
			}
			
		return $retorno;
	}

	public function ativarGrupo($user_group) {
		$this->db->where('user_group', $user_group);
		$dados = array('ativo' => '1');
		$x = $this->db->update('grupo', $dados);
		if( $x ){
			return 'true';
		} 
		else{
			return 'false';
		}
	}

	public function inativarGrupo($user_group) {
		$this->db->where('user_group', $user_group);
		$dados = array('ativo' => '0');
		$x = $this->db->update('grupo', $dados);
		if( $x ){
			return 'true';
		} 
		else{
			return 'false';
		}
	}

	public function updatePermition($group_id,$usuario,$permition){
		$this->db->where('group_id', $group_id);
		$this->db->where('user_id', $usuario);
		$dados = array('acesso_interno' => $permition);
		$x = $this->db->update('usuario_grupo', $dados);
		if( $x ){
			return 'true';
		} 
		else{
			return 'false';
		}
	}

	public function kickUsuario($group_id,$usuario){
		$this->db->where('group_id', $group_id);
		$this->db->where('user_id', $usuario);
		$x = $this->db->delete('usuario_grupo');
		if( $x ){
			return 'true';
		} 
		else{
			return 'false';
		}
	}
	public function inserir_grupo($nome_grupo,$cod_cliente){
		$sql = 'insert into grupo ( cod_cliente ,	Descricao 	,limite ,	ativo ) values("'.$cod_cliente.'","'.$nome_grupo.'",20,1)';
		$query = $this->db->query($sql);
		if(!$query){
			return false;
		}
		else{
			return $this->db->insert_id();
		}

	}
	public function inserir_usuario_grupo( $user_id,$group_id,$acesso_interno ){
		$sql = 'insert into usuario_grupo ( user_id ,	group_id 	,acesso_interno ) values("'.$user_id.'","'.$group_id.'","'.$acesso_interno.'")';
		$query = $this->db->query($sql);
		$existe = $this->verifica_usuario_grupo($user_id,$group_id);
		if ($existe===false) {
			return false;
		}
		if(!$query){
			return false;
		}
		else{
			return true;
		}
	}
	public function verifica_usuario_grupo($user_id,$group_id){
		$this->db->select('user_group');
		$this->db->from('usuario_grupo');
		$this->db->where('group_id',$group_id); 
		$this->db->where('user_id',$user_id); 
		$query = $this->db->get();
		if($query->num_rows()>0){
			return true;
		}
		else{
			return false;
		}
	}
/*
	public function getAllUsersAdvanced($user_id) {
	
		
				$this->db->select('*');
				$this->db->where('user_id',$user_id);
				$query = $this->db->get('usuarios');
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

	public function updateUser($user_id,$nome,$username,$senha,$email,$nivel_acesso){
		if($senha!="||vazio||")
		$senha=', senha = PASSWORD("'.$senha.'")';
		else
		$senha='';

		$query = 'update usuarios set nome = "'.$nome.'", username = "'.$username.'" '.$senha.', email = "'.$email.'",  nivel_acesso = "'.$nivel_acesso.'" where user_id = '.$user_id;
		//die($query);
		if(mysql_query($query)){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function addUser($nome,$username,$senha,$email,$nivel_acesso){
		if($senha!="||vazio||")
		$senha=' senha = PASSWORD("'.$senha.'")';
		else
		return 'false';

		$query = 'insert into usuarios (nome,username,senha,email,nivel_acesso) values ("'.$nome.'","'.$username.'", '.$senha.',"'.$email.'","'.$nivel_acesso.'")';
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
		$this->db->where('user_id', $dados['user_id']);
		if($this->db->update('usuarios', $dados)){
			return 'true';
		} 
		else{
			return 'false';
		}

	}

	public function updateClientAvancadoFisico($dados){
		//die(print_r($dados));
		$principal = array();
		//trara os dados para jogar na tabela principal
		$principal['rg'] = $dados['rg'];
		unset($dados['rg']);
		$principal['estado_rg'] = $dados['estado_rg'];
		unset($dados['estado_rg']);
		$principal['cpf'] = $dados['cpf'];
		unset($dados['cpf']);
		$principal['sexo'] = $dados['sexo'];
		unset($dados['sexo']);
		$principal['data_nasc'] = $dados['data_nasc'];
		unset($dados['data_nasc']);

		$this->db->where('cod_cliente', $dados['cod_cliente']);

		if($this->db->update('clientes', $dados)){
			//return 'true';
		} 
		else{
			return 'false';
		}
		//faz update no filho
		$this->db->where('cod_cliente', $dados['cod_cliente']);

		if($this->db->update('pessoa_fisica', $principal)){
			return 'true';
		} 
		else{
			return 'false';
		}

	}

	//funções de grupos de usuários ficam todas abaixo

	public function getUserGroup($user_id) {
		$this->db->select('group_id');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get('usuario_grupo');
		if($query->num_rows()>0){
			$result = (array)$query->result();
			foreach($result as $key=>$value){
				$result[$key] = (array)$result[$key];
				$result[$key] = $result[$key]['group_id'];
			}
			//die(print_r($result));
			return $result;
		}
		else{
			return null;
		}
	}
*/
}





/* End of file grupos.php */

/* Location: ./application/models/usuarios/grupos.php */