<?php
class Usuarios extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}
	
	public function getAllUsers() {
			$this->db->select('user_id,nome,username,senha,email,nivel_acesso');
			$this->db->order_by("nivel_acesso", "desc"); 
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
	public function getProdutividadeByGroup($group_id){
		/*
		SELECT a.user_id,a.nome,a.username, count( b.user_id ) * 2 AS qtd_comentarios, count( c.cod_criador ) * 3 AS qtd_topicos,(count( c.cod_criador ) * 3) +  (count( b.user_id ) * 2) as rank
		FROM usuarios a
		LEFT JOIN comentarios b ON a.user_id = b.user_id
		LEFT JOIN topicos c ON a.user_id = c.cod_criador
		LEFT JOIN usuario_conteudo d ON a.user_id = d.user_id
		where a.user_id in (select user_id from usuario_grupo where group_id = 37)
		GROUP BY user_id
		order by rank desc
		*/
		$resultado = array();
		$qtd_comentarios = array();	
		$qtd_conteudos = array();	
		$qtd_topicos  = array();	
		$sql = 'SELECT user_id,`qtd_comentarios` (
		user_id
		) AS `qtd_comentarios` 
		from usuarios
		where user_id in (select user_id from usuario_grupo where group_id = '.trim($group_id).') order by user_id asc
		';
		$query = $this->db->query($sql);
		$result = $query->result();
		if(count($result)>0)
		foreach($result as $key=>$value){
			$qtd_comentarios[$value->user_id] = $value->qtd_comentarios;  		
		}
		

		$sql = 'SELECT user_id,`qtd_conteudos` (
		user_id
		) AS `qtd_conteudos` 
		from usuarios
		where user_id in (select user_id from usuario_grupo where group_id = '.trim($group_id).') order by user_id asc
		';
		$query = $this->db->query($sql);
		$result = $query->result();
		if(count($result)>0)
		foreach($result as $key=>$value){
			$qtd_conteudos[$value->user_id] = $value->qtd_conteudos;  		
		}


		$sql = 'select a.user_id,count(b.cod_criador) as qtd_topicos from usuarios a left join topicos b on a.user_id = b.cod_criador 
		where a.user_id in (select user_id from usuario_grupo where group_id = '.trim($group_id).') group by user_id order by user_id asc
		';
		$query = $this->db->query($sql);
		$result = $query->result();
		if(count($result)>0)
		foreach($result as $key=>$value){
			$qtd_topicos[$value->user_id] = $value->qtd_topicos;  		
		}

		/*
		print_r($qtd_comentarios);
		print_r($qtd_conteudos);
		print_r($qtd_topicos);
		die();
		*/
		$sql = 'select a.user_id,a.nome,a.username from usuarios a
		where a.user_id in (select user_id from usuario_grupo where group_id = '.trim($group_id).') order by user_id asc
		';
		$query = $this->db->query($sql);
		$result = $query->result();
		if($query->num_rows() > 0){
			$result = (array)$query->result();
			$resultado = array();
			foreach($result as $key=>$value){
				$result[$key] = (array)$result[$key];
				$result[$key]['qtd_comentarios'] = array_key_exists($value->user_id, $qtd_comentarios)?$qtd_comentarios[$value->user_id]:0;
				$result[$key]['qtd_conteudos'] = array_key_exists($value->user_id, $qtd_conteudos)?$qtd_conteudos[$value->user_id]:0;
				$result[$key]['qtd_topicos'] = array_key_exists($value->user_id, $qtd_topicos)?$qtd_topicos[$value->user_id]:0;
				$result[$key]['produtividade'] = $result[$key]['qtd_comentarios'] + ($result[$key]['qtd_conteudos'] * 2) + ($result[$key]['qtd_topicos']*3);
				$resultado[$result[$key]['produtividade']] = $result[$key];
			}
			krsort($resultado);
			return $resultado;
		} 
		else{
			return false;
		}

	}
	public function getUserByUsername($username) {
	
		
				$this->db->select('user_id');
				$this->db->where('username',trim($username));
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

	public function updateUser($user_id,$nome,$username,$senha,$email,$nivel_acesso,$last_mod_by){
		if($senha!="||vazio||")
		$senha=', senha = PASSWORD("'.$senha.'")';
		else
		$senha='';

		$last_mod = date('Y-m-d h-i-s');
		$sql = 'update usuarios set nome = "'.$nome.'", username = "'.$username.'" '.$senha.', email = "'.$email.'",  nivel_acesso = "'.$nivel_acesso.'",  last_mod_by = "'.$last_mod_by.'",  last_mod = "'.$last_mod.'" where user_id = '.$user_id;
		//die($query);
		$query = $this->db->query($sql);
		if($query){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function novaSenha($user_id,$senha){
		$senha=' senha = PASSWORD("'.$senha.'")';
		$last_mod = date('Y-m-d h-i-s');
		$sql = 'update usuarios set '.$senha.', last_mod = "'.$last_mod.'" where user_id = '.$user_id;
		//die($query);
		$query = $this->db->query($sql);
		if($query){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function addUser($nome,$username,$senha,$email,$nivel_acesso,$created_by){
		if($senha!="||vazio||")
		$senha='PASSWORD("'.$senha.'")';
		else
		return 'false';

		$created = date('Y-m-d h-i-s');
		$sql = 'insert into usuarios (nome,username,senha,email,nivel_acesso,created_by,created) values ("'.$nome.'","'.$username.'", '.$senha.',"'.$email.'","'.$nivel_acesso.'","'.$created_by.'","'.$created.'")';
		//die($sql);
		$query = $this->db->query($sql);
		if($query){
			return $this->db->insert_id();
		}
		else{
			return 'false';
		}
	}
	/*
	public function addFirstUser($nome,$username,$senha,$email,$nivel_acesso,$creator_id){
		if($senha!="||vazio||")
		$senha=' senha = PASSWORD("'.$senha.'")';
		else
		return 'false';

		$sql = 'insert into usuarios (nome,username,senha,email,nivel_acesso,created,created_by) values ("'.$nome.'","'.$username.'", '.$senha.',"'.$email.'","'.$nivel_acesso.'","'.date('Y-m-d H:i:s').'","'.$creator_id.'")';
		$query = $this->db->query($sql);
		if($query){
			return 'true';
		}
		else{
			return 'false';
		}
	}
	*/
	public function addUserAdm($cod_cliente,$user_id){
		$sql = 'insert into cliente_adm (cod_cliente,user_id) values ("'.$cod_cliente.'","'.$user_id.'")';

		$query = $this->db->query($sql);
		if($query){
			return 'true';
		}
		else{
			return 'false';
		}
	}

	public function selectClientsByAdm($user_id) {
			$this->db->select('cod_cliente');
			$this->db->where("user_id", $user_id); 
				$query = $this->db->get('cliente_adm');
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
		$this->db->select('a.group_id');
		$this->db->from('usuario_grupo a' );
		$this->db->join('grupo b', 'a.group_id = b.user_group','inner');
		$this->db->where('a.user_id',$user_id);
		$this->db->where('b.ativo','1');
		$query = $this->db->get();
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

	public function getUserGroupInativos($user_id) {
		$this->db->select('a.group_id');
		$this->db->from('usuario_grupo a' );
		$this->db->join('grupo b', 'a.group_id = b.user_group','inner');
		$this->db->where('a.user_id',$user_id);
		$query = $this->db->get();
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

	public function getUserByEmail($email) {
		$this->db->select('user_id,nome,username,email');
		$this->db->from('usuarios' );
		$this->db->where('email',$email);
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
	}
	//SELECT * FROM `comentarios` WHERE cod_comentario = 1 or user_id = 1153
	//select * from comentarios a inner join topicos b on a.cod_topico = b.cod_topico inner join categorias c on b.cod_categoria = c.cod_categoria  inner join usuario_grupo d on c.user_group = d.user_group where a.user_id= 1161 and d.acesso_interno = 3
	public function delete_Comentario($cod_comentario,$user_id) {
		if($this->db->delete('comentarios', array('cod_comentario' => $cod_comentario,'user_id'=>$user_id))){
			return true;
		}
		else{
			return false;
		}
	}

	public function delete_Comentario_adm($cod_comentario) {
		if($this->db->delete('comentarios', array('cod_comentario' => $cod_comentario,'user_id'=>$user_id))){
			return true;
		}
		else{
			return false;
		}
	}
}





/* End of file usuario.php */

/* Location: ./application/models/usuarios/usuarios.php */