<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {

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
		$this->load->helper('utilitarios');
		$this->load->model('categoria/Categorias');
		$this->load->model('usuarios/Usuarios');
		$this->load->model('clientes/Clientes');
		$this->load->model('usuarios/Grupos_model');
    }

	public function nomeUsuario($id_usuario){
		$this->load->model('access/Users');
		$nome = mysql_fetch_array($this->users->getUserById($id_usuario));
		return $nome;
	}


	//Função que inicia a página wiki/pdf
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
		if($_GET['rel']){
			if($this->session->userdata('sg_user_id'))
			{	
				$this->load->model('categoria/Categorias');
			
				$dados = $this->Categorias->getCategoriasBase();
				$session['menu']['categorias_base'] = $dados;
				$this->session->set_userdata($session);
				$this->load->model('usuarios/Usuarios');
				//$this->load->model('relatorios/Relatorios');
				$data['usuario'] = $this->Usuarios->getAllUsers();

				$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
				$this->load->view('templates/main/header');
				switch ($_GET['rel']) {
					case 'usuarios':
						$this->load->view('pages/modal/pdf/usuarios',$data);
					break;
					case 'clientes':
						$this->load->model('clientes/Clientes');
						$data['usuario'] = $this->Clientes->getAllClients();
						$this->load->view('pages/modal/pdf/clientes',$data);
					break;
					case 'clientes_fisicos':
						$this->load->model('clientes/Clientes');
						$data['usuario_fisico'] = $this->Clientes->getAllFisicos();
						$this->load->view('pages/modal/pdf/clientes_fisicos',$data);
					break;
					case 'clientes_juridicos':
						$this->load->model('clientes/Clientes');
						$data['usuario_juridico'] = $this->Clientes->getAllJuridicos();
						$this->load->view('pages/modal/pdf/clientes_juridicos',$data);
					break;
					case 'validacoes':
						if(isset($_GET['g'])){
							$id_grupo = $_GET['g'];
						}
						else{
							echo utf8_decode("Grupo inválido !");
							die();
						}
						$adms = $this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id'));;
						$procura = array_search_in_array($id_grupo, $adms, 'group_id');
						if($procura===false){
							echo utf8_decode("Somente administradores deste grupo podem ver este relatório !");
							die();
						}
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
						$this->load->view('pages/modal/pdf/validacoes',$data);
					break;
					case 'rel_produtividade':
						if(isset($_GET['g'])){
							$id_grupo = $_GET['g'];
						}
						else{
							echo utf8_decode("Grupo inválido !");
							die();
						}
						$adms = $this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id'));;
						$procura = array_search_in_array($id_grupo, $adms, 'group_id');
						if($procura===false){
							echo utf8_decode("Somente administradores deste grupo podem ver este relatório !");
							die();
						}
						$data['usuario'] = $this->Usuarios->getProdutividadeByGroup($id_grupo);
						$data['info_grupo'] =  $this->Grupos_model->getGroupById($id_grupo);
						$data['info_grupo'] =  $data['info_grupo'][0];
						$data['info_grupo']['group_id'] = $id_grupo;
						$this->load->view('pages/modal/pdf/produtividade',$data);
					break;
					case 'acessos_topicos':
						$data['count_topicos'] = $this->Categorias->getCountTopicos();
						$data['topicos'] = $this->Categorias->getTopicosOrderByAcessos();
						$data['topicos']['S'] = isset($data['topicos']['S'])?$data['topicos']:0;
						$data['topicos']['N'] = isset($data['topicos']['N'])?$data['topicos']:0;
						$this->load->view('pages/modal/pdf/acessos_topicos',$data);
					break;
					default:
						$this->load->view('pages/modal/pdf/pdf',$data);
					break;
				}
				
			}else{
				
				$this->session->unset_userdata('sg_user_id');
				$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
				
				redirect('/', 'redirect');
			}
		}
		else{
			if($this->session->userdata('sg_user_id'))
			{	
				$this->load->model('categoria/Categorias');
			
				$dados = $this->Categorias->getCategoriasBase();
				$session['menu']['categorias_base'] = $dados;
				$this->session->set_userdata($session);
				$this->load->model('usuarios/Usuarios');
				$data['usuario'] = $this->Usuarios->getAllUsers();

				$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
				$this->load->view('templates/main/header');
				$this->load->view('pages/modal/pdf/pdf',$data);
				
			}else{
				
				$this->session->unset_userdata('sg_user_id');
				$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
				
				redirect('/', 'redirect');
			}
		}	
	}
	
}

/* End of file categoria.php */
/* Location: ./application/controllers/categoria.php */