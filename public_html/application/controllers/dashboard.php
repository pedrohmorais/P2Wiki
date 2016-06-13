<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
		if(!$this->session->userdata('sg_user_id'))
		{
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
		$this->load->model('clientes/Clientes');
    }

	public function nomeUsuario($id_usuario){
		$this->load->model('access/Users');
		$nome = mysql_fetch_array($this->users->getUserById($id_usuario));
		return $nome;
	}
	public function criaSession($sessao=null,$callback=null){
		if(isset($_GET['sessao']) && isset($_GET['callback'])){
			$sessao = $_GET['sessao'];
			$callback = $_GET['callback'];
		}
		if($this->session->userdata('sg_user_id')){
			foreach ($sessao as $key => $value) {
				$this->session->set_userdata($key, $value);
			}
			redirect($callback,'redirect');
		}

	}
	public function criaSessionnoredirect($sessao){
		
		if($this->session->userdata('sg_user_id')){
			foreach ($sessao as $key => $value) {
				$this->session->set_userdata($key, $value);
			}
		}
		return true;

	}
	public function criaFlashdata($sessao){
		
		if($this->session->userdata('sg_user_id')){
			foreach ($sessao as $key => $value) {
				$this->session->set_flashdata($key, $value);
			}
		}
		return true;

	}

	public function index()
	{
	/*
		if($this->session->userdata('sg_user_id') <> 1054)
		{
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Temporariamente fora do ar para manutenção</span>');
			
			redirect('/', 'redirect');				
		}
		*/
		
		if($this->session->userdata('sg_user_id'))
		{	
			$this->load->model('categoria/Categorias');
			$this->load->model('usuarios/Usuarios');
			$dados['clientes'] = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id')); 
			$session['sg_user_groups'] = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id')); 
			$dados = $this->Categorias->getCategoriasBase($this->session->userdata('sg_user_groups'),$this->session->userdata('sg_nivel_acesso'));
			 
			$session['menu']['categorias_base'] = $dados;
			//$this->session->set_userdata($session);
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/conteudo');
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}
	
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */