<?php

class Login extends CI_Model {

    var $user   = '';
    var $pass = '';
    var $auth    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    private function reset_acessos_by_user($username){
    	$sql = "update usuarios set tentativas = 0 where username = '".$username."'";
    	$this->db->query($sql);
    }
    private function increment_acessos_by_user($username){
    	$sql = "update usuarios set tentativas = tentativas + 1 where username = '".$username."'";
    	$this->db->query($sql);
    }
    private function trava_usuario($username){
    	$sql = "update usuarios set ativo = 'B' where username = '".$username."'";
    	$this->db->query($sql);
    }
    private function destrava_usuario($username){
    	$sql = "update usuarios set ativo = 'S' where username = '".$username."'";
    	$this->db->query($sql);
    }

    private function get_tentativas_by_user($username){
    	$sql = " SELECT u.tentativas				
					FROM usuarios u		
					WHERE u.username = '$username'";
		
		$query = $this->db->query($sql);
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->tentativas;
		}
    }
    function check($username=null, $senha=null)
    {	
    	
		$username = stripslashes(trim(str_replace(" ","",$username)));
		$senha = stripslashes(trim(str_replace(" ","",$senha)));
		
		$sql = " SELECT u.user_id, 
						u.username, 
						u.nome,						
						u.nivel_acesso,
						u.last_login_ip,
						u.ativo,
						DATE_FORMAT(last_login, '%Y-%m-%d %H:%i:%s') AS ultimo_acesso 						
					
					FROM usuarios u		
                                        
					WHERE u.username = '$username' 
					AND u.ativo != 'N'
					AND u.senha = PASSWORD('$senha')";
		
		$query = $this->db->query($sql);
	
        $retorno = false;
		if($query->num_rows()>0)
		{
			$result = $query->result();
			if($result[0]->user_id > 0)
			{
				
				$data = Array(
					'last_login_ip' => $_SERVER['REMOTE_ADDR']
				);
				$test = strtotime($result[0]->ultimo_acesso);
				$test2 = strtotime(date('Y-m-d H:i:s'));
  				//$diferenca = $test->date_diff();
  				/* Testes de data
  				print_r(date('Y-m-d H:i:s'));
  				echo '<br>';
  				print_r($result[0]->ultimo_acesso);
  				echo '<br>';
  				print_r(gmdate("H:i:s", ($test2 - $test)));
  				echo '<br>';
  				print_r(gmdate("H:i:s",gmmktime(1, 0, 0)));
  				var_dump(gmdate("H:i:s", ($test2 - $test)) > gmdate("H:i:s",gmmktime(1, 0, 0)));
  				die();
				*/
				if($result[0]->ativo == 'B' && gmdate("H:i:s", ($test2 - $test)) > gmdate("H:i:s",gmmktime(1, 0, 0)) ){
					$this->db->set('ativo',  "'S'", FALSE);
					$this->db->where('user_id', $result[0]->user_id);
					$this->db->update('usuarios'); 
					$this->reset_acessos_by_user($username);
					$result[0]->ativo = 'S';
				}
  				//print_r() );
				//$this->db->set('last_login', 'NOW()', FALSE);
				if($result[0]->ativo == 'S'){
					$this->db->set('last_login',  "'".date('Y-m-d H:i:s')."'", FALSE);
					$this->db->where('user_id', $result[0]->user_id);
					$this->db->update('usuarios',$data); 
				}

				
				
				//die($this->db->last_query());
				
				//return $query->result();
			}else{
				
				return 'error';
				
			}
                        
                        //Pegar profissoes
                        /*
                        $sql = mysql_query("SELECT profissoes.id, profissoes.profissao FROM profissoes WHERE profissoes.id IN (SELECT id_profissao FROM usuario_profissoes WHERE id_usuario = '{$result[0]->user_id}') ");
                        $profissoes = array();
                        while($line = mysql_fetch_object($sql)){                            
                            $profissoes[$line->id] = $line->profissao;
                        }
                        $result[0]->profissao = $profissoes;
                         */
                        
                        $retorno = $result;
                        
                        
		}
		else{
			$this->increment_acessos_by_user($username);
			if($this->get_tentativas_by_user($username) > 5){
				$this->trava_usuario($username);
			}
		}
		
		return $retorno;
	}
	
	function loggedUser()
	{
		$this->load->model("access/Users");
		$user = $this->Users->getUserOrAbort($this->session->userdata('sg_user_id'));
		return $user;
    }    
	
	function getMediaLoggedUser(){

    	$this->load->model("access/Users");
		$user = $this->Users->getMediaUserOrAbort($this->session->userdata('sg_user_id'));
		return $user;
    }  

	function getLastAccess()
	{
		$last_access = null;
		
		if($this->session->userdata('sg_user_id'))
		{
			$last_access = $this->session->userdata('sg_ultimo_acesso');
			$last_access = explode(' ',$last_access);
		}
	
		return $last_access;
    } 	
	
	function getLoginUser($username=null)
	{
	
		$this->db->select('u.nome,u.username,u.nivel_acesso,um.url_media');
		$this->db->from('usuarios u');
		$this->db->join('usuarios_media um', 'um.id_usuario = u.user_id','left');
		$this->db->where('u.username', $username);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0];
		}
		
		return false;
		
	}
    
}


/* End of file login.php */
/* Location: ./application/models/login.php */