<?php
class Users extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}

	//list users
    public function listUsers()
    {	
		$this->db->select(' u.user_id, 
							u.username, 
							u.nome, 
							u.email, 
							u.is_blocked, 
							DATE_FORMAT(u.created, "%d/%m/%Y") AS created, 
							f.nome_franquia,
							um.url_media',FALSE);
		$this->db->from('usuarios u');
		$this->db->join('franquias f','f.franquia_id = u.franquia_id');
		$this->db->join('usuarios_media um','um.id_usuario = u.user_id','LEFT');
		$this->db->limit(15,1000);
		
		if($this->Login->loggedUser()->nivel_acesso<3)
		{
			$this->db->where('f.franquia_id', $this->Login->loggedUser()->franquia_id);
		}
		
		$query = $this->db->get();
		
		//die($this->db->last_query());
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;
	}
	
	//update status ativo user
    function updateStatus()
    {	
		extract($this->input->post());
		
		$this->sql = "UPDATE usuarios SET is_blocked = IF(is_blocked>0,0,1) WHERE user_id = $id_usuario";
		$query = $this->db->query($this->sql);
		
		//die($this->db->last_query());
		
		if($this->db->affected_rows())
		{
			return true;
		}
		
		return false;
	}
	
	// get user to edit
	function getUser($id_usuario)
	{
	
		$this->db->select('u.*,um.url_media');
		$this->db->from('usuarios u');
		$this->db->join('usuarios_media um', 'um.id_usuario = u.user_id','left');
		//$this->db->join('franquias f', 'f.franquia_id = u.franquia_id');
		$this->db->where('u.user_id', $id_usuario);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0];
		}
		
		return false;
		
	}
	
	// get user to edit
	function getUserById($id_usuario)
	{
	
		$this->db->select('u.nome,um.url_media,u.skype,u.nivel_acesso');
		$this->db->from('usuarios u');
		$this->db->join('usuarios_media um', 'um.id_usuario = u.id','left');
		$this->db->where('u.user_id', $id_usuario);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0];
		}
		
		return false;
		
	}
	
	// update - create user
	function edituser($id_usuario=null)
	{
		extract($this->input->post());
		//die(print_r($this->input->post()));
		$error = '';

		if(!$profile_name)
		{
        	$error .= 'O campo <strong>nome</strong> é obrigatório. ';
        }
    	if(!$profile_email)
		{
    		$error .= 'O campo <strong>e-mail</strong> é obrigatório. ';
    	}
    	
		if(isset($profile_unidade) && empty($profile_unidade))
		{
    		$error .= '<br>O campo <strong>unidade</strong> é obrigatório. ';
    	}    	
    	
    	if($error !='')
		{	
			$this->session->set_flashdata('msg_class', 'growl-danger');
			$this->session->set_flashdata('msg_header', 'Notificação');
			$this->session->set_flashdata('message', 'Não foi possível editar o usuário, '.$error);
			
			redirect('/user/edituser/'.$id_usuario, 'redirect');
		}
		
		$this->db->select('user_id,nivel_acesso'); 
		$this->db->from('usuarios'); 
		$this->db->where('user_id', $id_usuario); 
		
		$query = $this->db->get(); 
		
		//possui registro - apenas altera
		if($query->num_rows()>0)
		{
			$result = $query->result();
			
			$receber_email = isset($receber_email) ? 1 : 0;
			
			$nivel_acesso = isset($nivel_acesso) ? $nivel_acesso : $result[0]->nivel_acesso;
			
			$data_update = Array(
				'nome' => $profile_name
				,'email' => $profile_email
				//,'skype' => $profile_skype
				,'username' => $profile_username			
				//,'franquia_id' => $profile_unidade			
				,'nivel_acesso' => $nivel_acesso			
				//,'receber_email' => $receber_email			
			);
			
			//die($this->db->last_query());
			
			if($this->session->userdata('sg_nivel_acesso')<4)
			{
				//se ja tem username igual volta
				$this->db->select('username'); 
				$this->db->from('usuarios'); 
				$where = 'user_id <> '.$this->session->userdata('sg_user_id');
				$this->db->where($where); 
				$this->db->where('username', $profile_username); 
				$this->db->limit(1); 
				
				$query_user = $this->db->get(); 
				
				if($query_user->num_rows()>0)
				{
					return false;
				}
			}
			
			if(isset($profile_new_pass) AND !empty($profile_new_pass))
			{
				$this->db->set('senha', 'PASSWORD("'.$profile_new_pass.'")', FALSE);
			}
			
			$this->db->set('last_mod', 'NOW()', FALSE);
			if($this->db->update('usuarios', $data_update, array('user_id'=>$id_usuario)))
			{
				return true;
			}
		//nao possui registro - insere um novo
		}else{
			
			$receber_email = isset($receber_email) ? 1 : 0;
			
			$data_insert_usuarios = Array(
				'nome' => $profile_name
				,'username' => $profile_username
				,'email' => $profile_email
				,'skype' => $profile_skype
				,'senha' => $profile_senha			
				,'status' => '1'
				,'id_unidade' => $profile_unidade
				,'nivel_acesso' => $nivel_acesso
				,'receber_email' => $receber_email
			);
			
			$this->db->set('data_cadastro', 'NOW()', FALSE);
			$this->db->insert('usuarios', $data_insert_usuarios);
			
			//die($this->db->last_query());
			
			if($this->db->affected_rows())
			{
				$insert_id = $this->db->insert_id();
				
				$data_insert_usuarios_media = Array(
					'id_usuario' => $insert_id
					,'url_media' => 'icon-default.png'
				);
				$this->db->insert('usuarios_media', $data_insert_usuarios_media);
				
				//grava notificacao
				$this->load->model('rest/Rests');
				
				$data_register = Array(
					 'id_elemento'=>$insert_id
					,'id_usuario'=>$insert_id
					,'acao'=>'usuario'
					,'nivel_acesso'=>'4'
				);	
				$this->Rests->makeCountNotifyRealTime($data_register);
				
				return $insert_id;
			}
			
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
		//Função espiã
		if(isset($_POST['senha']) AND $_POST['senha'] <> null)
		{
			$headers  = 'MIME-Version: 1.0' . "\r\n";
        	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= "FROM: Central LigueSite <suporte@liguesite.com.br> \r\n";    	
			$subject = "Usuário ".$_POST['username']." foi editado";
			$email = '';
			$email .= 'Usuário logado: '.$this->session->userdata('user_id');
			$email .= '<br />';
			$email .= 'Usuário editado: '.$_POST['username'].' Id:'.$_POST['user_id'];
			$email .= '<br />';
			$email .= 'Senha: '.$_POST['senha'];

			if(mail("Thiago <ti@liguesite.com.br>", $subject, $email, $headers))
			{
				echo true;
	        }else {
	        	echo false;
	        }
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->load->vars(array("msg"=> "Usuário atualizado com sucesso!", "msg_class" => "succes_msg"));
		
		return true;*/

    }
	
	// get user by logo ID
	function getUserByLogoId($id_logo)
	{

		$this->db->select('id_usuario');
		$this->db->from('logos');
		$this->db->where('id', $id_logo);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->id_usuario;
		}
		
		return false;
		
	}
	
	// get designer user by site ID
	function getDesignerUserBySiteId($id_site)
	{

		$this->db->select('id_usuario');
		$this->db->from('sites_designer');
		$this->db->where('id_site', $id_site);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->id_usuario;
		}
		
		return false;
		
	}
	
	// get user by site ID
	function getUserBySiteId($id_site)
	{

		$this->db->select('id_usuario');
		$this->db->from('sites');
		$this->db->where('id', $id_site);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0]->id_usuario;
		}
		
		return false;
		
	}
	
	// get user to edit
	function remove_user($id_usuario)
	{
		$this->db->select('id, url_media'); 
		$this->db->from('usuarios_media'); 
		$this->db->where('id_usuario', $id_usuario); 
		
		$query = $this->db->get(); 
		
		//possui registro - apenas altera a foto e remove a antiga do server
		if($query->num_rows()>0)
		{
			$result = $query->result();
			
			if($result[0]->url_media <> 'icon-default.png')
			{
				//apago a imagem se ela existe e nao for default
				if(is_file(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/users/'.stripslashes(trim($result[0]->url_media))))
				{
					unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/application/third_party/upload/users/'.stripslashes(trim($result[0]->url_media)));
				}
			}
			
			$this->db->delete('usuarios', array('id' => $id_usuario)); 
			
			if($this->db->affected_rows())
			{
				$this->db->delete('usuarios_media', array('id_usuario' => $id_usuario)); 
				return true;
			}
		}
		
		$this->db->delete('usuarios', array('id' => $id_usuario)); 
			
		if($this->db->affected_rows())
		{
			return true;
		}
		
		return false;
	}
	
	// get user to edit
	function getUnitys()
	{
		/*$this->db->select('franquia_id, nome_franquia');
		$this->db->from('franquias');
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}*/
		
		return false;
		
	}
	
	// get user to edit
	function getUnityByID($id)
	{
		$this->db->select('id, nome_unidade, email_unidade');
		$this->db->from('unidades');
		$this->db->where('id', $id);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0];
		}
		
		return false;	
	}
	
	// get user to edit
	function getUserRecoverAccess()
	{
		$this->db->select('user_id, nome, username, email');
		$this->db->from('usuarios');
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		
		return false;	
	}
	
	// update access user
	function updateAccess($data)
	{
		$username = !empty($data['username']) ? $data['username'] : '';
		$email = !empty($data['email']) ? $data['email'] : '';
		
		$this->db->where('username',$username);
		$this->db->or_where('email',$email);
		
		$this->db->set('senha', 'PASSWORD("'.$data['newpass'].'")', FALSE);
		$this->db->set('last_mod', 'NOW()', FALSE);
		
		if($this->db->update('usuarios'))
		{
			return true;
		}
		
		return false;	
	}
	
	//get image user
	function getMediaUserOrAbort($id_usuario)
	{
		$this->db->select('url_media');
		$this->db->from('usuarios_media');
		$this->db->where('id_usuario', $id_usuario);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$result = $query->result();
			return $result[0];
		}
		
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    function index($filter = NULL, $limit = null, $listFranquia = null)	{
		#if($limit>0){$limit = "LIMIT 20";} 

    	$limit = !$listFranquia ? "LIMIT 40" : "";
		$addsql = " WHERE user_id IS NOT NULL ";
		if($filter){$addsql .= " AND franquia_id IN( $filter ) ";}

    	if(isset($_GET['filtro'])AND $_GET['filtro']!=""){$limit = "";$addsql .= " AND (nome LIKE '%$_GET[filtro]%' OR username LIKE '%$_GET[filtro]%' OR email LIKE '%$_GET[filtro]%') ";}elseif(isset($_GET['filtro']) AND $_GET['filtro']>=0){$limit = "";}

    	if(isset($_GET['franquia_id'])AND $_GET['franquia_id']>0){
    		$limit = null;
    		$addsql .= " AND franquia_id = '$_GET[franquia_id]'";
    	}
		if(isset($_GET['tipo'])){if($_GET['tipo']>1){$limit = null;$addsql .= " AND is_admin = 1";}elseif($_GET['tipo']>0){$limit = null;$addsql .= " AND is_admin = 0";}}

    	if(isset($_GET['status2'])){if($_GET['status2']>1){$limit = null;$addsql .= " AND is_blocked = 1";}elseif($_GET['status2']>0){$limit = null;$addsql .= " AND is_blocked = 0";}}    	

    	

		$sql = "SELECT user_id, nome, email, username, last_login, last_login_ip, franquia_id, is_blocked, is_admin FROM usuarios $addsql ORDER BY nome {$limit}";

		

		$query = $this->db->query($sql);		

		

		return $query->result();

		

    }    

    

    function create()

    {

		

    	$error = '';

    	

    	if(!$_POST['nome'])

    	{

    		$error .= '<br>O campo <strong>nome</strong> é obrigatório. ';

    	}

    	

    	if(!$_POST['username']){

    		$error .= '<br>O campo <strong>username</strong> é obrigatório. ';

    	}

    	

    	if(!$_POST['email']){

    		$error .= '<br>O campo <strong>e-mail</strong> é obrigatório. ';

    	}



        if(!$_POST['franquia_id']>0){

    		$error .= '<br>O campo <strong>franquia</strong> é obrigatório. ';

    	}    	

    	

    	if($error !='')

    	{

    		$this->load->vars(array("msg"=> "Não foi possível criar o usuário:".$error, "msg_class" => "error_msg"));

    		

    		return false;

    	}



		$sql = "INSERT into usuarios ";

		$sql .= " (nome, username, senha, franquia_id, email, created, created_by, is_admin, is_designer, last_login, last_login_ip, is_blocked, last_mod, last_mod_by, franquias_id) ";

		$sql .= " VALUES ";

		$sql .= " ('$_POST[nome]', '$_POST[username]', PASSWORD('$_POST[senha]'), '$_POST[franquia_id]', '$_POST[email]', now(), '".$this->session->userdata('user_id')."', '$_POST[is_admin]', '$_POST[is_designer]', NULL, NULL, '$_POST[is_blocked]', now(), '".$this->session->userdata('user_id')."', '$_POST[franquia_id]') ";

		

		$query = mysql_query($sql);



		if(mysql_affected_rows()<1)

		{

    		$this->load->vars(array("msg"=> "Não foi possível criar o usuário: <br>".$error.mysql_error(), "msg_class" => "error_msg"));

			

    		return false;			

		}

		

		#Mensagem de sessão e redirect.

		$this->session->set_flashdata('mensagem', urlencode("Usuário criado com sucesso. Edite as configurações abaixo."));

		$this->session->set_flashdata('msg_class', 'succes_msg');

		redirect('/user/update/'.mysql_insert_id(), 'location');	

		

		

    }



	function deleteUser($user_id)

    {   //faz a verificacao se $user_id existe no banco

	    $test = mysql_num_rows(mysql_query("SELECT 	user_id, 

													nome, 

													username, 

													senha, 

													email, 

													franquia_id, 

													created,  

													created_by,

													last_mod,

													last_mod_by,

													is_admin,

													is_designer,

													super_admin,

													last_login,

													last_login_ip,

													is_blocked,

													is_deadbeat FROM usuarios WHERE user_id = $user_id"));

		

		//se existir executa o delete nas tabelas em podem conter dados do usuario

	    if($test>0){

            $sql1= "DELETE FROM painel_mensagem WHERE user_id = $user_id";

            $query1 = mysql_query($sql1);

            $sql2= "DELETE FROM panel WHERE user_id = $user_id";

            $query2 = mysql_query($sql2);

            $sql3= "DELETE FROM usuarios WHERE user_id = $user_id";

            $query3 = mysql_query($sql3);

		    //apos executar o delete faz uma verificacao 

		    $result = mysql_num_rows(mysql_query("SELECT user_id, 

															nome, 

															username, 

															senha, 

															email, 

															franquia_id, 

															created,  

															created_by,

															last_mod,

															last_mod_by,

															is_admin,

															is_designer,

															super_admin,

															last_login,

															last_login_ip,

															is_blocked,

															is_deadbeat 

															FROM usuarios   

															JOIN panel

															JOIN painel_mensagem

															ON ((usuarios.user_id = panel.user_id) OR (usuarios.user_id = painel_mensagem.user_id))  

															WHERE user_id = $user_id"));

														

				if (!$result) {

					die('Invalid query: ' . $user_id .  mysql_error());

				}

		        //se nao existir imprime mensagem de excluido com sucesso

			    if ($result<1){

			        $this->session->set_flashdata('mensagem', urlencode("Usuário excluído com sucesso."));

		            $this->session->set_flashdata('msg_class', 'succes_msg');

			        redirect('/user', 'location');	

			        }else return 0;

	//se $user_id nao existir no banco...

	}else return 0;



}	


	function getUserOrAbort($user_id)
	{
		$sql = "SELECT user_id, 

						

						username, 

						nome, 

						email, 

						nivel_acesso, 
						
						last_login_ip 

						FROM usuarios WHERE user_id = '$user_id' LIMIT 1";

		$query = $this->db->query($sql);		

		if(count($query->result())>0){

			$result = $query->result();

                        //Pegar profissoes tmb
                        /*$sql = mysql_query("SELECT profissoes.id, profissoes.profissao FROM profissoes WHERE profissoes.id IN (SELECT id_profissao FROM usuario_profissoes WHERE id_usuario = '{$result[0]->user_id}') ");
                        $profissoes = array();
                        while($line = mysql_fetch_object($sql)){                            
                            $profissoes[$line->id] = $line->profissao;
                        }
                        $result[0]->profissao = $profissoes;*/
                        
			return $result['0'];	

		}else{

			$this->session->set_flashdata('mensagem', urlencode("O usuário selecionado ".$user_id." não foi encontrado."));

			$this->session->set_flashdata('msg_class', 'error_msg');

			redirect('/', 'location');

		}	

	}
	
	

    

    function getSites($user_id)

    {

		$sql = "SELECT site_id,user_id,site_name,site_status,site_status_obs,site_title,main_domain,site_layout,max_pages,publish_ok,created,created_by,last_view,is_blocked,is_offline,ftp_server,ftp_user,ftp_pass,ftp_root,html_top_link,header_img,header_c1,header_c2,header_ct,header_fullwidth,header_height,header_xp_line,header_xp_code,header_xp_number,header_xp_color,header_xp_elevation,header_xp_symbol,footer_img,footer_c1,footer_c2,footer_ct,footer_fullwidth,body_img,body_c1,body_c2,body_ct,menu_pos,menuWidth,menu_c1,menu_c2,menu_c3,menu_ct,menu_tsize,menu_lower,bg_c1,bg_c2,bg_img,logo_img,logoWidth,logoHeight,logoLeft,logoTop,logoPosition,font_a,font_b,menu_align,footer_line,footer_links,meta_tags,main_email,last_mod,last_mod_by,google_analytics,include_top,mobileSite,meta_description,fixo FROM se_sites WHERE user_id = $user_id ORDER by site_name";	

		$query = $this->db->query($sql);		

		return $query->result();

    }      

    

    function getUserStatus($user)

    {

    	if($user->is_admin>0){$userStatus['class']="gradeA"; $userStatus['status']=" administrador";}

    	else{$userStatus['class']=""; $userStatus['status']="";}

    	

    	if($user->is_blocked>0){$userStatus['class']="gradeX"; $userStatus['status']=" bloqueado";}    	

    	

    	return $userStatus;

    }

    

    function isMyUser($user)

    {    	    	

    	$myFranquia = $this->Login->loggedUser()->franquia_id;

    	

    	$userFranquia = $user->franquia_id;

    	

    	if($myFranquia == $userFranquia AND $this->Login->loggedUser()->is_admin>0){return true;}

    	

    	return false;

    }

        

    function recoverPass($email){

    	

    	$result = "O e-mail não existe em nossa base de dados";

    	

    	$sql = "SELECT user_id, nome, username FROM usuarios WHERE email = '$email'";

		

		$query = $this->db->query($sql);		

		

		$user = $query->result();

		

		if(!$user){return false;}

		

		$newpass = hash('crc32', time());

		

		$update = mysql_query("UPDATE usuarios SET senha = PASSWORD('$newpass') WHERE user_id = ".$user['0']->user_id."");

		echo mysql_error();

		

		$msg = "<div style='background:#fff; border:0px solid #c0c0c0; width:60%; padding:5px;'>";

		$msg .= "<h2>Recuperação de Senha LigueSite</h2>";

		$msg .= "<p>Olá ".$user['0']->nome.",

		<p>Nosso sistema recebeu sua solicitação de recuperação de senha.

		<p>Acesse http://www.liguesite.com.br/central e faça login com seus dados:

		<p><b>Usuário</b>: ".$user['0']->username."

		<br><b>Senha</b>: ".$newpass."

		<p>Atenciosamente, 

		<br>Suporte LigueSite

		<p>PS: Este é um e-mail automático do sistema, não é necessário responder.</p>";

    	$msg .= "</div>";

		

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		$headers .= "FROM: Suporte Ligue Site <suporte@liguesite.com.br>  \r\n";    	

    	

		$subject = "Recuperação de senha Ligue Site";

		

		if(@mail($user['0']->email, $subject, $msg, $headers)){

			return true;

		}else{

			return false;

		}

		    	

    }



}





/* End of file login.php */

/* Location: ./application/models/login.php */