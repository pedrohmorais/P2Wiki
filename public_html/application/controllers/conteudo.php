<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Conteudo extends CI_Controller {
	private $sg_clientes;
	private $sg_user_id;
	private $erroinserircomentario;
	private $menu;
	public function getMenu(){
		return $this->menu;
	}

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('categoria/Categorias');
		$this->load->model('usuarios/Usuarios');
		$this->load->model('clientes/Clientes');

		//$session['sg_clientes'] = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id')); 
		$this->sg_user_id = $this->session->userdata('sg_user_id');
		$this->sg_clientes = $this->Clientes->getClienteByUser($this->sg_user_id); 
		$group = $this->Usuarios->getUserGroup($this->sg_user_id);
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));

		$this->menu['menu']['categorias_base'] = $dados;
		//$this->session->set_userdata($session);
    }

    private function getImageUser($user_id){
    	// le todos os arquivos da pasta
		$pasta = $_SERVER['DOCUMENT_ROOT'].'/application/third_party/usuarios_fotos/'.$user_id.'/';
		$link_site = site_url().'application/third_party/usuarios_fotos/'.$user_id.'/';
		if(file_exists($pasta)){
	   		$diretorio = dir($pasta);
	   	}
	   	else{
	   		$diretorio = false;
	   	}
	    if(is_object($diretorio)){
		  	while($arquivo = $diretorio -> read()){
		  		if($arquivo !== '..' && $arquivo !== '.'){
			  		//$arquivos[]['nome'] = $arquivo;
			  		//$arquivos[]['tamanho'] = filesize($pasta.$arquivo);
			  		switch (strtoupper (substr($arquivo, strlen($arquivo)-3, 3) ) ) {
			  			case 'JPEG':
			  			case 'JPG':
			  			case 'GIF':
			  			case 'PNG':
			  				$imagem  = $link_site.$arquivo;
			  				break;
			  			default:
			  				$imagem  = site_url('images/LogoCursive150.png');
			  				break;
			  		}
			  	}
		   	}
		   	//print_r($imagens);
		   	//print_r($arquivos);
		   	//die();
		   	//seria legal colocar um filtro por imagens e depois colocar em um painel de imagens especifico
		   	$diretorio -> close();
	    }
	    else{
	    	$imagem  = site_url('images/LogoCursive150.png');
	    }
	    if(!isset($imagem)){
	    	$imagem  = site_url('images/LogoCursive150.png');
	    }
	    return $imagem;
    }

	public function nomeUsuario($id_usuario){
		$this->load->model('access/Users');
		$nome = mysql_fetch_array($this->users->getUserById($id_usuario));
		return $nome;
	}

	

	function conteudo($cod_conteudo){
		$this->load->model('categoria/Categorias');
		$data['conteudo'] = (array)$this->Categorias->getConteudoById($cod_conteudo);
		//die(print_r($data['conteudo']));
		//$data['cod_topico_pagina'] = $cod_topico;
		$group = $this->Usuarios->getUserGroup($this->sg_user_id);
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		$this->session->set_userdata($session);
		$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
		$this->load->view('templates/main/header'); 
		if($this->sg_user_id)
		{
			$this->load->view('templates/main/navbar_adm');
		}
		else
		{
			$this->load->view('templates/main/navbar');
		}
		$this->load->view('pages/modal/editar_conteudo.php',$data); 
		$this->load->view('templates/main/scripts'); 
		$this->load->view('templates/main/footer'); 
	}

	function updateTopico(){
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['retorno'] = (array)$this->Categorias->updateTopico($cod_topico,$nome_topico,$link_topico,$cod_categoria,$ordem);
			echo json_encode($retorno['retorno']);
		} 
	}
		
	function updateConteudo(){
		$this->load->model('categoria/Categorias');
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['retorno'] = (array)$this->Categorias->updateConteudo($cod_conteudo,$nome_conteudo,$ordem);
			echo json_encode($retorno['retorno']);
		} 
	}

	function updateConteudoCKEDITOR(){
		
		if(isset($_POST)){
			extract($_POST);
			$this->load->model('categoria/Categorias');
			$retorno = array();
			$retorno['retorno'] = (array)$this->Categorias->updateConteudoCKEDITOR($cod_conteudo,$conteudo);
			redirect('/categoria/conteudo/'.$cod_conteudo, 'redirect');	
		}
	}
	//Faz as alterações e inserções dos tópicos
	//Se tiver cod_topico quer dizer que a função irá para a edição
	function topicos($cod_categoria,$edit=null,$cod_topico=null){
		//Se tiver cod_topico quer dizer que a função irá para a edição
		if(isset($cod_topico)&&isset($edit)){
			if($edit=="edit")
			{
				$this->load->model('categoria/Categorias');
				$data['topicos'] = (array)$this->Categorias->getTopicoById($cod_topico);
				$data['conteudos'] = (array)$this->Categorias->getConteudos($cod_topico);
				$data['cod_topico_pagina'] = $cod_topico;
				$group = $this->Usuarios->getUserGroup($this->sg_user_id);
				$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
				$this->menu['menu']['categorias_base'] = $dados;
				$this->session->set_userdata($session);
				$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
				$this->load->view('templates/main/header'); 
				if($this->sg_user_id)
				{
					$this->load->view('templates/main/navbar_adm');
				}
				else
				{
					$this->load->view('templates/main/navbar');
				}
				$this->load->view('pages/modal/editar_topico.php',$data); 
				$this->load->view('templates/main/scripts'); 
				$this->load->view('templates/main/footer'); 
			}
		}
		else
		{
			$this->load->model('categoria/Categorias');
			$data['topicos'] = (array)$this->Categorias->getTopicos($cod_categoria);
			$data['cod_categoria_pagina'] = $cod_categoria;
			$group = $this->Usuarios->getUserGroup($this->sg_user_id);
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;
			$this->session->set_userdata($session);
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			if($this->sg_user_id)
			{
				$this->load->view('templates/main/navbar_adm');
			}
			else
			{
				$this->load->view('templates/main/navbar');
			}
			$this->load->view('pages/modal/topico.php',$data); 
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
		}		
	}

	
	function updateCategoria(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['retorno'] = (array)$this->Categorias->updateCategoria($cod_categoria,$nome_categoria,$categoria_pai,$glyphicon);
			echo json_encode($retorno['retorno']);
		} 
	}
	//Edita as propriedades de uma categoria
	function editar_categoria($cod_categoria){
		$this->load->model('categoria/Categorias');
		$data['categoria'] = (array)$this->Categorias->getCategoriaById($cod_categoria);
		$data['cod_categoria_pagina'] = $cod_categoria;
		$group = $this->Usuarios->getUserGroup($this->sg_user_id);
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		$this->session->set_userdata($session);
		
		$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
		$this->load->view('templates/main/header'); 
		if($this->sg_user_id)
		{
			$this->load->view('templates/main/navbar_adm');
		}
		else
		{
			$this->load->view('templates/main/navbar');
		}
		$this->load->view('pages/modal/edit_categoria.php',$data); 
		$this->load->view('templates/main/scripts'); 
		$this->load->view('templates/main/footer'); 
	}
	//Exclui os tópicos do iframe
	function deleteTopicos(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['cod_topico'] =$cod_topico;
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->deleteTopicos($cod_topico);
			echo json_encode($retorno);
		} 
	}
	//Exclui as categorias
	function deleteCategoria(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['cod_categoria'] = $cod_categoria;
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->deleteCategoria($cod_categoria);
			echo json_encode($retorno);
		} 
	}
	//Exclui um conteudo
	function deleteConteudo(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['cod_conteudo'] = $cod_conteudo;
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->deleteConteudo($cod_conteudo);
			echo json_encode($retorno['retorno']);
		} 
	}
	//Insere os tópicos no modal
	function insertTopicos(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['nome_topico'] =$nome_topico;
			$retorno['link_topico'] =$link_topico;
			$retorno['cod_categoria'] =$cod_categoria;
			
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->insertTopicos($nome_topico,$link_topico,$cod_categoria);
			//die(print_r($retorno['retorno']['cod_topico']));
			echo json_encode($retorno['retorno']['cod_topico']);
			return true;
		} 
	}
	function insertConteudos(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['cod_topico'] =$cod_topico;
			$retorno['nome_conteudo'] =$nome_conteudo;
			$retorno['ordem'] =$ordem;
			
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->insertConteudos($cod_topico,$nome_conteudo,$ordem);
			
			echo json_encode($retorno['retorno']['cod_conteudo']);
			return true;
		} 
	}
	//Insere categorias
	function insertCategorias(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['nome_categoria'] =$nome_categoria;
			$retorno['categoria_pai'] =$categoria_pai;
			$retorno['glyphicon'] =$glyphicon;
			
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->insertCategorias($nome_categoria,$categoria_pai,$glyphicon);
			//die(print_r($retorno['retorno']['cod_topico']));
			echo json_encode($retorno['retorno']['cod_categoria']);
			return true;
		} 
	}
	//carrega as tags
	function loadTags($cod_topico){
		
		$this->load->model('categoria/Categorias');
		$retorno['tags'] = (array)$this->Categorias->getTagsTopico($cod_topico);

		foreach($retorno['tags'] as $key=>$value)
		{
			$retorno['tags'][$key] = (array)$retorno['tags'][$key];
		}
		//die(print_r($retorno['tags']));
		return $retorno['tags'];
		
	}
	function visualizar($cod_topico=null){
		$this->load->model('categoria/Categorias');
		$data['conteudos'] = (array)$this->Categorias->getConteudos($cod_topico);
		foreach ($data['conteudos'] as $key => $value) {
			$data['conteudos'][$key] = (array)$value;
			$data['conteudos'][$key]['referencias'] = $this->Categorias->getReferenciasByConteudo($value->cod_conteudo);
			if(is_array($data['conteudos'][$key]['referencias']) && count($data['conteudos'][$key]['referencias']) > 0){
				foreach ($data['conteudos'][$key]['referencias'] as $chave => $referencia) {
					$data['conteudos'][$key]['referencias'][$chave] = $this->Categorias->getTopicoById($referencia->cod_topico);					
				}
				$data['conteudos'][$key] = (object)$data['conteudos'][$key];
			}
		}
		$data['cod_topico'] = $cod_topico;
		$data['tags'] = $this->loadTags($cod_topico);
		//die(print_r($data['conteudos']));
		//$data['cod_topico_pagina'] = $cod_topico;
		$data['infoTopico'] = $this->Categorias->getTopicoById($cod_topico);
		//insere incremento de acesso
		$data['acessos'] = $this->Categorias->insertAcesso($cod_topico);
		$data['topicosRelacionados'] = $this->Categorias->getTopicosRelacionados($data['tags']);
		$group = $this->Usuarios->getUserGroup($this->sg_user_id);
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));

		$comentarios = $this->Categorias->listComentarios($cod_topico);
		if(count($comentarios)>0 && is_array($comentarios)){
			foreach ($comentarios as $key=>$comentario) {
				$comentarios[$key]['link_foto'] = $this->getImageUser($comentario['user_id']);
				$user_temp = $this->Usuarios->getAllUsersAdvanced($comentario['user_id']);
				$comentarios[$key]['nome_usuario'] = $user_temp[0]['nome'];
			}
		}
		$data['comentarios_controller'] = $comentarios;
		//$this->menu['menu']['categorias_base'] = $dados;
		//$this->session->set_userdata($session);
		$this->session->set_userdata('sg_clientes',$this->sg_clientes);
		$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
		$this->load->view('templates/main/header'); 
		if($this->sg_user_id)
		{
			$this->load->view('templates/main/navbar_adm');
		}
		else
		{
			$this->load->view('templates/main/navbar');
		}
		$this->load->view('pages/main/conteudo.php',$data); 
		$this->load->view('templates/main/scripts'); 
		$this->load->view('templates/main/footer'); 
	}
	function inserirAcessoExterno(){
		if(is_numeric(trim($_POST['cod_topico'] ) ) ){
			$this->load->model('categoria/Categorias');
			print_r($this->Categorias->insertAcesso( trim($_POST['cod_topico'] )  ));
		}
		else{
			echo 'false';
		}
		
	}

	public function inserirComentario($cod_topico){
		if(!$this->sg_user_id){
			redirect('/dashboard','redirect');
			return false;
		}
		if( !isset($_POST['comentario']) || $_POST['comentario']==null ){
			$this->erroinserircomentario =  array('erro'=>'false','mensagem'=>'Comentário inválido !');
			redirect('/conteudo/visualizar/'.$cod_topico,'refresh');
		}
		if(!$this->Categorias->inserirComentario($cod_topico,$this->sg_user_id,$_POST['comentario'])){
			$this->erroinserircomentario =  array('erro'=>'false','mensagem'=>'Erro ao fazer comentário !');
		}
		else{
			$this->erroinserircomentario =  array('erro'=>'true','mensagem'=>'Comentário feito com sucesso !');
		}
		//var_dump($this->session->userdata('erroinserircomentario'));
		$callback = '/conteudo/visualizar/'.$cod_topico;
		$this->load->library('../controllers/dashboard');
		$this->dashboard->criaFlashdata(array('erroinserircomentario'=>$this->erroinserircomentario));
		redirect($callback,'redirect');
	}

	public function deleteComentario(){
		if(!$this->sg_user_id){
			echo json_encode(array('erro'=>'false','mensagem'=>'Usuário inválido !') );
			die();
		}
		if( !isset($_POST['cod_comentario']) || trim($_POST['cod_comentario'])<= 0 ){
			echo json_encode(array('erro'=>'false','mensagem'=>' Código de comentário inválido !') );
			die();
		}
		$deletou = $this->Categorias->deleteComentario(trim($_POST['cod_comentario']),$this->sg_user_id);
		if($deletou){
			echo json_encode(array('erro'=>'true','mensagem'=>'Comentário excluido com sucesso !') );
			die();
		}
		else{
			echo json_encode(array('erro'=>'false','mensagem'=>'Não foi possível excluir comentário !') );
			die();
		}

	}
	//Função que inicia a página wiki/conteudo
	public function index()
	{
	/*
		if($this->sg_user_id <> 1054)
		{
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Temporariamente fora do ar para manutenção</span>');
			
			redirect('/', 'redirect');				
		}
		*/
			
			$this->load->model('categoria/Categorias');
		
			$group = $this->Usuarios->getUserGroup($this->sg_user_id);
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;
			$this->session->set_userdata($session);
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			if($this->sg_user_id)
			{
				$this->load->view('templates/main/navbar_adm');
			}
			else
			{
				$this->load->view('templates/main/navbar');
			}
			$this->load->view('pages/main/conteudo');
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		
	}
	
}

/* End of file categoria.php */
/* Location: ./application/controllers/categoria.php */