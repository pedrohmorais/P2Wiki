<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Grupos extends CI_Controller {
	public $sg_clientes;
    public $grupos_participante;
    private $menu;
    public function getMenu(){
    	return $this->menu;
    }
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('utilitarios');
		$this->load->model('categoria/Categorias');
		$this->load->model('usuarios/Usuarios');
		$this->load->model('clientes/Clientes');
		$this->load->model('usuarios/Grupos_model');
		if($this->session->userdata('sg_user_id'))
		{
			$this->sg_clientes = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id')); 
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;
			$this->menu['menu']['grupos'] = $this->Usuarios->getUserGroupInativos($this->session->userdata('sg_user_id'));

			//cagado
			//$session['grupos_adm'] = $this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id'));
			$this->grupos_participante = 	$this->Grupos_model->getGroupsParticipante($this->session->userdata('sg_user_id'));
			//$this->session->set_userdata($session);
		}
    }

	//Função que inicia a página wiki/conteudo
	public function index()
	{
		$this->load->library('session');
   
		/*
		unset todas userdatas
	    foreach ($user_data as $key => $value) {
	        if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
	            $this->session->unset_userdata($key);
	        }
	    }
	    $this->session->set_flashdata('name', 'value');
	    print_r($this->session->flashdata('name'));die();
	    */
		//$this->load->model('categoria/Categorias');
		
		if($this->session->userdata('sg_user_id'))
		{	
			/*
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
						
			
		
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$session['data_user'] = $data_user;
			
			$this->session->set_userdata($session);
			*/
			//puxa as informacoes do grupo
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$this->menu['menu']['grupos'] = $this->Usuarios->getUserGroupInativos($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;	
			//$this->session->set_userdata($session);

			$data['info_grupos'] = $this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id'));

			//$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/grupos_meus',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	public function my_owns_config($id_grupo)
	{
		//$this->load->model('categoria/Categorias');
		if($this->session->userdata('sg_user_id'))
		{	
			
			if(array_key_exists('id_grupo_pdf', $this->session->all_userdata() ) ){
				$this->session->unset_userdata('id_grupo_pdf');
			}
			
				$this->session->set_userdata('id_grupo_pdf',$id_grupo);
			
			//print_r($this->session->userdata('id_grupo_pdf'));
			//die();
			/*
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			$this->load->model('usuarios/Usuarios');
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$session['data_user'] = $data_user;
			$this->session->set_userdata($session);
			*/
			//$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$adms = $this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id'));;
			$procura = array_search_in_array($id_grupo, $adms, 'group_id');
			$data['info_grupo'] =  $this->Grupos_model->getGroupById($adms[$procura]['group_id']);
			$data['info_grupo'][0]['group_id'] = $id_grupo;
			$data['integrantes'] = $this->Grupos_model->getAllUsersInAGroup($adms[$procura]['group_id']);
			//pega topicos por categoria
			$data['categorias_grupo'] = $this->Categorias->getCategoriesByGroup(array($id_grupo));
			if(count($data['categorias_grupo'])>0){
			foreach ($data['categorias_grupo'] as $key => $value) {
					$validacao_cat = null;
					$validacao_cat = $this->Categorias->getCategoriesValidation($value);
					if($validacao_cat != null){
						$validacao_cat['nome_usuario'] = $this->Usuarios->getAllUsersAdvanced($validacao_cat['user_id']);
						$validacao_cat['nome_usuario'] = $validacao_cat['nome_usuario'][0]['username'];
					}
					//print_r($validacao_cat);die();
					$data['categorias_grupo'][$key] = array('cod_categoria' => $value,'dados_categoria'=> $this->Categorias->getCategoriaById($value),'topicos' => $this->Categorias->getTopicos($value,'logado'),'validacao'=>$validacao_cat);
				}
			}
			//print_r($data['categorias_grupo']);die();
			$all_users = array();
			foreach ($this->Usuarios->getAllUsers()  as $usuariostodos) {
				$all_users[] = array('user_id'=>$usuariostodos['user_id'],'nome'=>$usuariostodos['nome'],'username'=>$usuariostodos['username']);
			}
			sort($all_users);			
			$data['all_users'] = array('all_users'=> $all_users);
			if($procura !== false){
				$this->load->view('templates/main/header'); 
				$this->load->view('templates/main/navbar_adm');
				$this->load->view('pages/main/grupos_own_config',$data);
				$this->load->view('templates/main/scripts'); 
				$this->load->view('templates/main/footer'); 
			}
			else{
				$this->session->unset_userdata('sg_user_id');
				$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
				redirect('/', 'redirect');
			}
			
		}else{
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			redirect('/', 'redirect');
		}
	}
	// igual ao my_own_config, mas esse aqui mostra de drupos que voce nao é adm
	public function group_stats($id_grupo)
	{
		//$this->load->model('categoria/Categorias');
		if($this->session->userdata('sg_user_id'))
		{	
			/*
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			$this->load->model('usuarios/Usuarios');
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$session['data_user'] = $data_user;
			$this->session->set_userdata($session);
			*/
			//$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			

			//$adms = $this->session->userdata('grupos_adm');
			$parts = $this->grupos_participante;
			$procura = array_search_in_array($id_grupo, $parts, 'group_id');
			
			
			$data['info_grupo'] =  $this->Grupos_model->getGroupById($parts[$procura]['group_id']);
			$data['integrantes'] = $this->Grupos_model->getAllUsersInAGroup($parts[$procura]['group_id']);
			if($procura !== false){
				$this->load->view('templates/main/header'); 
				$this->load->view('templates/main/navbar_adm');
				$this->load->view('pages/main/grupos_stats',$data);
				$this->load->view('templates/main/scripts'); 
				$this->load->view('templates/main/footer'); 
			}
			else{
				$this->session->unset_userdata('sg_user_id');
				$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
				redirect('/', 'redirect');
			} 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	public function participante()
	{
	
		//$this->load->model('categoria/Categorias');
		
		if($this->session->userdata('sg_user_id'))
		{	
			/*
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
						
			
		
			$this->load->model('usuarios/Usuarios');
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$session['data_user'] = $data_user;
			
			$this->session->set_userdata($session);
			*/
			$data['info_grupos'] = $this->Grupos_model->getGroupsParticipante($this->session->userdata('sg_user_id'));
			
			//$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/grupos_participante',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}
	function ativarGrupo()
	{
		if($_POST['group_id']){
			if (array_search($_POST['group_id'], $this->menu['menu']['grupos'] ) !== false) { 
				$this->load->model('usuarios/Grupos_model');
				echo json_encode(array('retorno' => $this->Grupos_model->ativarGrupo($_POST['group_id']) ));
			}
			else{
				echo json_encode(array('retorno' => 'Você não é adm deste grupo !'));
			}	
		}
	}
	function inativarGrupo()
	{

		if($_POST['group_id']){
			if (array_search($_POST['group_id'], $this->menu['menu']['grupos'] ) !== false) { 
				$this->load->model('usuarios/Grupos_model');
				echo json_encode(array('retorno' => $this->Grupos_model->inativarGrupo($_POST['group_id']) ));
			}
			else{
				echo json_encode(array('retorno' => 'Você não é adm deste grupo !'));
			}	
		}
	}
	function updatePermition()
	{

		if($_POST['group_id']){
			if (array_search($_POST['group_id'], $this->menu['menu']['grupos'] ) !== false) { 
				$this->load->model('usuarios/Grupos_model');
				echo json_encode(array('retorno' => $this->Grupos_model->updatePermition($_POST['group_id'],$_POST['usuario'],$_POST['permition']) ));
			}
			else{
				echo json_encode(array('retorno' => 'Você não é adm deste grupo !'));
			}	
		}
	}
	function kickUsuario()
	{

		if($_POST['group_id']){
			if (array_search($_POST['group_id'], $this->menu['menu']['grupos'] ) !== false) { 
				$this->load->model('usuarios/Grupos_model');
				echo json_encode(array('retorno' => $this->Grupos_model->kickUsuario($_POST['group_id'],$_POST['user_id']) ));
			}
			else{
				echo json_encode(array('retorno' => 'Você não é adm deste grupo !'));
			}	
		}
	}
	public function inserir_grupo(){
		if($this->session->userdata('sg_user_id'))
		{
			$clientes_que_adm = array();
			foreach ($this->sg_clientes as $cliadm) {
				$clientes_que_adm[] = $cliadm['cod_cliente'];
			}
			$cod_cliente = isset($_POST['cod_cliente'])?$_POST['cod_cliente']:'';
			$nome_grupo = isset($_POST['nome_grupo'])?$_POST['nome_grupo']:'';
			if(array_search($cod_cliente, $clientes_que_adm)===false && $this->session->userdata('sg_nivel_acesso') < 4){
				$this->session->set_flashdata('sg_erro_insert_grupo','Você não possui permissão para acesar esta função ou dados foram enviados incorretamente !');
				redirect('/grupos', 'redirect');
				return false;
			}
			$inseriu_grupo = $this->Grupos_model->inserir_grupo($nome_grupo,$cod_cliente);
			if($inseriu_grupo!==false){
				$inseriu_usuario_grupo = $this->Grupos_model->inserir_usuario_grupo($this->session->userdata('sg_user_id'),$inseriu_grupo,3);
				if($inseriu_usuario_grupo === true){
					$this->session->set_flashdata('sg_insert_grupo','Grupo '.$nome_grupo.' incluido com sucesso !');
					redirect('/grupos', 'redirect');
					return true;
				}
				else{
					$this->session->set_flashdata('sg_erro_insert_grupo',"Erro associar usuário ao grupo !");
					redirect('/grupos', 'redirect');
					return false;
				}
			}
			else{
				$this->session->set_flashdata('sg_erro_insert_grupo',"Erro ao incluir grupo !");
				redirect('/grupos', 'redirect');
				return false;
			}
		}
		else{
			$this->session->set_flashdata('sg_erro_insert_grupo','Você deve estar logado para fazer esta ação !');
			redirect('/grupos', 'redirect');
			return false;
		}
	}
	public function inserir_usuario_grupo(){

		$group_id = isset($_POST['group_id'])?$_POST['group_id']:'';
		if($this->session->userdata('sg_user_id'))
		{
			$clientes_que_adm = array();
			foreach ($this->sg_clientes as $cliadm) {
				$clientes_que_adm[] = $cliadm['cod_cliente'];
			}
			$cod_cliente = isset($_POST['cod_cliente'])?$_POST['cod_cliente']:'';
			$user_id = isset($_POST['user_id'])?$_POST['user_id']:'';
			$acesso_interno = isset($_POST['acesso_interno'])?$_POST['acesso_interno']:'';
			if(array_search($cod_cliente, $clientes_que_adm)===false && $this->session->userdata('sg_nivel_acesso') < 4){
				$this->session->set_flashdata('sg_erro_insert_usuario_grupo','Você não possui permissão para acesar esta função ou dados foram enviados incorretamente !');
				redirect('/grupos/my_owns_config/'.$group_id, 'redirect');
				return false;
			}
			$usuarioexiste = $this->Grupos_model->verifica_usuario_grupo($user_id,$group_id);
			if($usuarioexiste){
				$this->session->set_flashdata('sg_erro_insert_usuario_grupo',"Usuário já existe no grupo !");
				redirect('/grupos/my_owns_config/'.$group_id, 'redirect');
				return false;
			}
			$inseriu_usuario_grupo = $this->Grupos_model->inserir_usuario_grupo($user_id,$group_id,$acesso_interno);
			if($inseriu_usuario_grupo === true){
				$this->session->set_flashdata('sg_insert_usuario_grupo','Usuário incluido ao grupo com sucesso !');
   	    		//print_r($this->session->flashdata('sg_insert_usuario_grupo'));die();
				redirect('/grupos/my_owns_config/'.$group_id, 'redirect');
				return true;
			}
			else{
				$this->session->set_flashdata('sg_erro_insert_usuario_grupo',"Erro inserir usuário ao grupo !");
				redirect('/grupos/my_owns_config/'.$group_id, 'redirect');
				return false;
			}
			
		}
		else{
			$this->session->set_flashdata('sg_erro_insert_usuario_grupo','Você deve estar logado para fazer esta ação !');
			redirect('/grupos/my_owns_config/'.$group_id, 'redirect');
			return false;
		}
	}
}
