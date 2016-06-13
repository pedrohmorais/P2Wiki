<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Session extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('access/Users');
		
	}
	
	public function logout()
	{
		$this->session->unset_userdata('sg_user_id');
		$this->session->unset_userdata('sg_nivel_acesso');
		$this->session->sess_destroy();
		redirect('/','location');
	}
	public function login()
	{
		$username = $auth = $data = null; 
		$alert = 'Informe seus dados para acessar';
		
		#VERIFICANDO SE OS DADOS FORAM PASSADOS	PARA AJUSTAR O FOCO INICIAL
			
		if($this->input->post('username'))		
		{		
			if($this->input->post('lembrar_login'))
			{
				setcookie('_COOKIEut',$this->input->post('username'), time()+3600); // COOKIE EXPIRA EM 1 HORA
				setcookie('_COOKIEpw',$this->input->post('senha'), time()+3600); // COOKIE EXPIRA EM 1 HORA
			}else{
				setcookie('_COOKIEut',''); 
				setcookie('_COOKIEpw',''); 
			}
			
			# CARREGA MODULO PARA CHECAR
			$this->load->model('access/Login');
			
			$auth = $this->Login->check($this->input->post('username'), $this->input->post('senha'));
			
            if($auth AND $auth<>'error')			
			{
				$user = $auth['0'];
                
				//salva user_id na sessão
                $session['sg_user_id'] = $user->user_id;
				//$session['sg_id_unidade'] = $user->franquia_id;
				$session['sg_nivel_acesso'] = $user->nivel_acesso;
				$session['sg_ip_acesso'] = $user->last_login_ip;
				$session['sg_username'] = $user->username;
				$session['sg_nome'] = $user->nome;
				$session['sg_ultimo_acesso'] = $user->ultimo_acesso;
                
                $this->session->set_userdata($session);
				
				#REDIRECIONAR PARA O PAINEL
				$this->session->set_flashdata('first_login', $user->nome);	
				//Se o usuario estiver inativo ele é redirecionado
				if($user->ativo != 'S'){
					$data['msg_error'] = "<span>Sua conta está inativa, entre em contato com o suporte.</span>";	
					$this->session->set_flashdata('msg_error',  $data['msg_error']);
					$this->session->unset_userdata('sg_user_id');
					$this->session->unset_userdata('sg_nivel_acesso');
					redirect('/','location');
				}
				//if($this->session->userdata('sg_nivel_acesso')==4 OR $this->session->userdata('sg_nivel_acesso')==3)
				//{
					redirect('/dashboard', 'redirect');	
				//}else{
					#NÃO AUTENTICOU EXIBE MSG DE ERRO
				//	$data['msg_error'] = "<span>Este usuário não possui permissão para acessar este painel</span>";	
				//}				
			}elseif($auth=='error'){
				$this->session->unset_userdata('sg_user_id');
				
				#NÃO AUTENTICOU EXIBE MSG DE ERRO
				$data['msg_error'] = "<span>Sua unidade está bloqueada no sistema, contate o financeiro da Central</span>";	
			
			}else{
				$this->session->unset_userdata('sg_user_id');
				
				#NÃO AUTENTICOU EXIBE MSG DE ERRO
				$data['msg_error'] = "<span>Os dados informados não estão corretos</span>";	
			}
			
		}elseif($this->input->post()){
			#NÃO AUTENTICOU EXIBE MSG DE ERRO
			$data['msg_error'] = "<span>Informe os dados corretamente</span>";	
		}
		
		if(ISSET($_COOKIE['_COOKIEut']))
		{
			$data['cookie_ut'] = $_COOKIE['_COOKIEut'];
			$data['cookie_pw'] = $_COOKIE['_COOKIEpw'];
		}
		$this->session->set_flashdata('msg_error',  $data['msg_error']);	
		redirect('/','redirect');
	}
	  
	public function index()
	{	
		$this->load->model('categoria/Categorias');
		$dados = $this->Categorias->getCategoriasBase();
		$session['menu']['categorias_base'] = $dados;
		$this->session->set_userdata($session);		
		if($this->session->userdata('sg_user_id'))
		{
			
			redirect('/dashboard','redirect');	
		}
		else
		{
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar');
			if($this->session->flashdata('msg_error') ){
				$data['msg_error'] = $this->session->flashdata('msg_error');
				$this->load->view('templates/main/msg_error',$data);
			}
			$this->load->view('pages/main/conteudo');
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			return true;
		}
	}
	//função search não usada
	public function search()
	{
		//die('aqui'); nao dava erro com o die
		if($this->session->userdata('sg_user_id'))
		{	
			$this->load->model('categoria/Categorias');
		
			$dados = $this->Categorias->getCategoriasBase();
			$session['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$session['data_user'] = $data_user;
			
			$this->session->set_userdata($session);
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			//ADM
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/usuario');
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			$this->load->model('categoria/Categorias');
		
			$dados = $this->Categorias->getCategoriasBase();
			$session['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$session['data_user'] = $data_user;
			
			$this->session->set_userdata($session);
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar');
			$this->load->view('pages/main/usuario');
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
		}
	}
	
	//enviar solicitacao para receber codigo
	public function requestCode()
	{
		if($this->input->post())
		{
			$unity = $this->Users->getUnityByID($this->input->post('code_unidade'));
			
			$email_unidade = (isset($unity->email_unidade) AND !empty($unity->email_unidade)) ? $unity->email_unidade : '';
			
			//para franqueado
			$data_templateFOO = Array(
				'nome'=>$this->input->post('code_name')
				,'header'=>'Olá '.$this->input->post('code_name').'.<br/> A solicitação de código para sua unidade '.ucfirst($unity->nome_unidade).', realizada em <b>'.date('d/m').'</b> às <b>'.date('H:i').' Hs</b> foi recebida com sucesso'
				,'principal'=>'Aguarde, em breve nossa equipe entrará em contato para prosseguir com seu pedido'
			);
			$mensagem_franqueado = $this->Mail->getTemplate($data_templateFOO);
			
			$data_franqueado = Array(
				 'nome'=>$this->input->post('code_name')
				,'fromname'=>'Solicitação código Meu Web'
				,'email'=>$email_unidade
				//,'BCC'=>'ti@liguesite.com.br'
				,'assunto'=>'Solicitação de código para acesso ao painel Meu Web'
				,'mensagem'=>$mensagem_franqueado
			);
			
			//para franqueadora
			$data_templateFAA = Array(
				'nome'=>$this->input->post('code_name')
				,'header'=>'Olá Ligue Site<br/> A unidade '.ucfirst($unity->nome_unidade).', solicitou em <b>'.date('d/m').'</b> às <b>'.date('H:i').' Hs</b> o código para acesso ao Painel Meu Web'
				,'principal'=>'Entre em contato através do e-mail <b>'.$email_unidade.'</b> para prosseguir com o pedido'
			);
			$mensagem_franqueadora = $this->Mail->getTemplate($data_templateFAA);
			
			$data_franqueadora = Array(
				 'nome'=>$this->input->post('code_name')
				,'fromname'=>'Solicitação código Meu Web'
				,'email'=>'financeiro@valedosilicio.com.br'
				,'BCC'=>'ti@liguesite.com.br'
				,'assunto'=>'Solicitação de código para acesso ao painel Meu Web'
				,'mensagem'=>$mensagem_franqueadora
			);
			
			if($this->Mail->sendMail($data_franqueado))
			{
				$this->Mail->sendMail($data_franqueadora);
				
				$this->session->set_flashdata('notify','1');
				$this->session->set_flashdata('window','alert-success');
				$this->session->set_flashdata('icon','icon-checkmark3');
				$this->session->set_flashdata('message','Solicitação encaminhada com sucesso, uma confirmação foi enviada para o e-mail de administração da unidade selecionada');
				
				redirect('/', 'redirect');
			}else{
				echo '{"status":"error"}';
			}
		}
	}
	
	//recuperar senha
	public function recoverAccess()
	{
		if($this->input->post())
		{
			if(!$this->input->post('recover_name') OR !$this->input->post('recover_email'))
			{
				$this->session->set_flashdata('notify','1');
				$this->session->set_flashdata('window','alert-danger');
				$this->session->set_flashdata('icon','icon-warning');
				$this->session->set_flashdata('message','Informe os dados corretamente');

				redirect('/', 'redirect');
			}
			
			$users = $this->Users->getUserRecoverAccess();
			
			foreach($users AS $key=>$user)
			{
				if($user->username == stripslashes(trim($this->input->post('recover_name'))) OR $user->email == stripslashes(trim($this->input->post('recover_email'))))
				{
					$salt = $this->makeRandomSalt();
					
					$data = Array(
						 'username'=>$user->username
						,'email'=>$user->email
						,'newpass'=>$salt
					);
					
					if($this->Users->updateAccess($data))
					{
						//para franqueadora
						$data_templateFAA = Array(
							'nome'=>$this->input->post('recover_name')
							,'header'=>'Olá Ligue Site.<br/> O usuário '.ucfirst($user->nome).', solicitou em <b>'.date('d/m').'</b> às <b>'.date('H:i').' Hs</b> a recuperação de senha para acesso ao painel'
							,'principal'=>'Seu login de acesso é <b>'.$user->username.'</b>.<br/> A nova senha para acesso ao painel Meu Web é <b>'.$salt.'</b><br/> <a href="'.site_url().'" target="_blank">Clique aqui para acessar</a>'
						);
						$mensagem_franqueadora = $this->Mail->getTemplate($data_templateFAA);
						
						$data_franqueadora = Array(
							 'nome'=>$this->input->post('code_name')
							,'fromname'=>'Central Ligue Site'
							,'email'=>'ti@liguesite.com.br'
							,'assunto'=>'Recuperação de senha do painel'
							,'mensagem'=>$mensagem_franqueadora
						);
						
						$data_templateFOO = Array(
							'nome'=>$this->input->post('recover_name')
							,'header'=>'Olá '.ucfirst($user->nome).', nosso sistema registrou que em <b>'.date('d/m').'</b> às <b>'.date('H:i').' Hs</b> foi solicitada a recuperação de senha do Painel Ligue Site'
							,'principal'=>'Seu login de acesso é <b>'.$user->username.'</b>.<br/> A nova senha para acesso ao painel é <b>'.$salt.'</b><br/> <a href="'.site_url().'" target="_blank">Clique aqui para acessar</a>'
						);
						
						$mensagem_franqueado = $this->Mail->getTemplate($data_templateFOO);
						
						$data_franqueado = Array(
							 'nome'=>$this->input->post('recover_name')
							,'fromname'=>'Central Ligue Site'
							,'email'=>$user->email
							,'assunto'=>'Recuperação de senha do painel'
							,'mensagem'=>$mensagem_franqueado
						);
						
						if($this->Mail->sendMail($data_franqueado))
						{
							$this->Mail->sendMail($data_franqueadora);
							
							$this->session->set_flashdata('notify','1');
							$this->session->set_flashdata('window','alert-success');
							$this->session->set_flashdata('icon','icon-checkmark3');
							$this->session->set_flashdata('message','Recuperação solicitada com sucesso. Uma nova senha foi encaminhada para sua caixa de e-mail');
							
							redirect('/', 'redirect');
						}else{
							echo '{"status":"error"}';
						}
					}
				}
			}
			$this->session->set_flashdata('notify','1');
			$this->session->set_flashdata('window','alert-danger');
			$this->session->set_flashdata('icon','icon-warning');
			$this->session->set_flashdata('message','Usuário não encontrado no sistema');

			redirect('/', 'redirect');
		}
	}
	
	//troca usuario
	public function troca_usuario()
	{
		
		$data['cookie_ut'] = null;
		$data['cookie_pw'] = null;
		
		setcookie('_COOKIEut',''); 
		setcookie('_COOKIEpw',''); 
		
		$header_data = array('title'=>'Login');
		
		$this->load->view('templates/main/header', $header_data);
		$this->load->view('templates/init/login', $data);
		$this->load->view('templates/main/scripts'); 
		$this->load->view('templates/main/footer'); 
		
	}

	

	function makeRandomSalt($width = 10) {
		return substr(sha1(mt_rand()), 0, $width);  
	}

	public function getTopicos($cod_categoria)
	{
		$this->load->model('categoria/Categorias');
		$dados = $this->Categorias->getTopicos($cod_categoria);
		$session['menu']['topicos'][$cod_categoria] = $dados;
		$this->session->set_userdata($session);	
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */