<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends CI_Controller {
	private $user_id;
	private $menu;
	public function getMenu(){
		return $this->menu;
	}
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
		$this->user_id = $this->session->userdata('sg_user_id');
		$this->load->model('categoria/Categorias');
		$this->load->model('usuarios/Usuarios');
		$this->load->model('clientes/Clientes');
		$this->load->model('usuarios/Grupos_model');
		$session['sg_clientes'] = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id')); 
		$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		$this->menu['menu']['grupos'] = $this->Usuarios->getUserGroupInativos($this->session->userdata('sg_user_id'));
		$session['sg_user_groups'] = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id')); 
		$this->session->set_userdata($session);
    }

	public function nomeUsuario($id_usuario){
		$this->load->model('access/Users');
		$nome = mysql_fetch_array($this->users->getUserById($id_usuario));
		return $nome;
	}
	//carrega as tags
	function loadTags($cod_topico){
		
		$this->load->model('categoria/Categorias');
		$retorno['tags'] = (array)$this->Categorias->getTagsTopico($cod_topico);
		foreach($retorno['tags'] as $key=>$value)
		{
			$retorno['tags'][$key] = (array)$retorno['tags'][$key];
		}
		return $retorno['tags'];
		
	}
	function editar_topico($cod_topico){
		$data['topico'] = (array)$this->Categorias->getTopicoById($cod_topico);
		$data['conteudos'] = (array)$this->Categorias->getConteudos($cod_topico);
		$data['tags'] = $this->loadTags($cod_topico);
		$data['cod_topico_pagina'] = $cod_topico;
		$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		$categorias_permitidas = array();
		foreach ($this->Categorias->getCategoriasBasePainel($group,$this->session->userdata('sg_nivel_acesso')) as $categoria) {
			$categorias_permitidas[] = $categoria->cod_categoria;
		}
		if(array_search($data['topico']['cod_categoria'], $categorias_permitidas)===false){
			$this->load->view('templates/main/bloqueado');
		}
		else{
			//$this->session->set_userdata($session);
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/modal/editar_topico.php',$data); 
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
		}
	}
	private function getTopicosDisponiveis(){
		$topicos_disponiveis = array();
		foreach($this->menu['menu']['categorias_base'] as $categorias_temp){
			$topico_temp = $this->Categorias->getTopicos($categorias_temp->cod_categoria,1);
			if(count($topico_temp)>0 && is_array($topico_temp))
			foreach ($topico_temp as $key => $value) {
				$topicos_disponiveis[$value->cod_topico] = $value->nome_topico;
			}
		}
		return $topicos_disponiveis;
	}
	function conteudo($cod_conteudo){
		$this->load->model('categoria/Categorias');
		//verifica se tem autorizacao pra fazer edicao
		$topicos_disponiveis = $this->getTopicosDisponiveis();

		$codigo_valido = false;
		$coteudos = array();
		foreach($topicos_disponiveis as $codigo_topico_disponivel => $topico_disponivel){
			$conteudos_disponiveis_temp = $this->Categorias->getConteudos($codigo_topico_disponivel);
			foreach ($conteudos_disponiveis_temp as $conteudo_disponivel) {
				if($conteudo_disponivel->cod_conteudo == $cod_conteudo){
					$codigo_valido = true;
				}
				$coteudos[] = $conteudo_disponivel;
			}
		}
		if (!$codigo_valido && $this->session->userdata('sg_nivel_acesso') < 3) {
			//print_r($conteudos_disponiveis_temp);
			//die();
			echo utf8_decode('Você não tem permissão para acessar este conteúdo !');
			die();
		}
		//comeca a operacao
		$data['conteudo'] = (array)$this->Categorias->getConteudoById($cod_conteudo);
		$data['cod_categoria_topico'] = (array)$this->Categorias->getCategoriaFromConteudo($data['conteudo']['cod_topico']);
		//die(print_r($data['conteudo']));
		//$data['cod_topico_pagina'] = $cod_topico;
		$this->load->model('usuarios/Usuarios');
		$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		//$this->session->set_userdata($session);
		$topicos_disponiveis = $this->getTopicosDisponiveis();
		$data['topicos_disponiveis'] = $topicos_disponiveis;
		$data['conteudo_referencias'] = array();
		$referenciasByConteudo = $this->Categorias->getReferenciasByConteudo($cod_conteudo);
		if(count($referenciasByConteudo) > 0 && is_array($referenciasByConteudo)){
			foreach($referenciasByConteudo as $pegar_topico_info){
				$data['conteudo_referencias'][]= $this->Categorias->getTopicoById($pegar_topico_info->cod_topico);
			}
		}
		//print_r($data['conteudo_referencias']);die();
		//$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
		$this->load->view('templates/main/header'); 
		$this->load->view('templates/main/navbar_adm');
		$this->load->view('pages/modal/editar_conteudo.php',$data); 
		$this->load->view('templates/main/scripts'); 
		$this->load->view('templates/main/footer'); 
	}

	function updateTopico(){
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['retorno'] = (array)$this->Categorias->updateTopico($cod_topico,$nome_topico,$link_topico,$cod_categoria,$ordem,$descricao,$publico);
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
				$this->load->model('usuarios/Usuarios');
				$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
				$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
				$this->menu['menu']['categorias_base'] = $dados;
				/*
				$categorias_permitidas = array();
				foreach ($this->menu['menu']['categorias_base'] as $categoria) {
					$categorias_permitidas[] = $categoria->cod_categoria;
				}
				print_r($data['topico']['cod_categoria']);
				print_r($categorias_permitidas);
				if(array_search($data['topico']['cod_categoria'], $categorias_permitidas)===false){
					$this->load->view('templates/main/bloqueado');
				}
				else{
				*/
					//$this->session->set_userdata($session);
					$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
					$this->load->view('templates/main/header'); 
					$this->load->view('templates/main/navbar_adm');
					$this->load->view('pages/modal/editar_topico.php',$data); 
					$this->load->view('templates/main/scripts'); 
					$this->load->view('templates/main/footer'); 
				/* } */
			}
		}
		else
		{
			//if(in_array($cod_categoria, $this->session->userdata('sg_categories_groups')) || (int)$this->session->userdata('sg_nivel_acesso') == 4){
			if($this->session->userdata('sg_user_groups') == true && count($this->session->userdata('sg_user_groups')) > 0 && in_array($cod_categoria, $this->session->userdata('sg_user_groups')) || (int)$this->session->userdata('sg_nivel_acesso') >= 2){
				$this->load->model('categoria/Categorias');
				if((int)$this->session->userdata('sg_nivel_acesso') == 4){
					$data['topicos'] = (array)$this->Categorias->getTopicos($cod_categoria,1);//simbolico, usado apenas para nao ser nulo
				}
				else{
					$data['topicos'] = (array)$this->Categorias->getTopicos($cod_categoria,$this->session->userdata('sg_user_groups'));
				}
				$data['cod_categoria_pagina'] = $cod_categoria;
				$data['categoria_info'] = $this->Categorias->getCategoriaById($cod_categoria);
				$data['categoria_validada'] = $this->Categorias->getCategoriesValidation($cod_categoria);
				if(isset($data['categoria_validada']['validado']) ){
					$data['categoria_validada'] = $data['categoria_validada']['validado'];
				}
				else{
					unset($data['categoria_validada']);
				}
				$this->load->model('usuarios/Usuarios');
				$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
				$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
				$this->menu['menu']['categorias_base'] = $dados;
				$categorias_permitidas = array();
				foreach ($this->Categorias->getCategoriasBasePainel($group,$this->session->userdata('sg_nivel_acesso')) as $categoria) {
					$categorias_permitidas[] = $categoria->cod_categoria;
				}
				if(array_search($cod_categoria, $categorias_permitidas)===false){
					$this->load->view('templates/main/bloqueado');
				}
				else{
					//$this->session->set_userdata($session);
					$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
					$this->load->view('templates/main/header'); 
					$this->load->view('templates/main/navbar_adm');
					$this->load->view('pages/modal/topico.php',$data); 
					$this->load->view('templates/main/scripts'); 
					$this->load->view('templates/main/footer'); 
				}
			}
			else{
				echo htmlspecialchars("Você não tem permissão para acessar esta página !");
			}
		}		
	}

	public function showList() {
	    $controllers = array();
	    $this->load->helper('file');

	    // Scan files in the /application/controllers directory
	    // Set the second param to TRUE or remove it if you 
	    // don't have controllers in sub directories
	    $files = get_dir_file_info(APPPATH.'controllers', FALSE);

	    // Loop through file names removing .php extension
	    foreach ( array_keys($files) as $file ) {
	        if ( $file != 'index.html' )
	            $controllers[] = str_replace('.php', '', $file);
	    }
	    $functions = array();
	    foreach ($controllers as $controller) {
	    	if ($controller != 'pdf' && $controller != 'key' && $controller != 'example') {
	    		try {
	    			$this->load->library('../controllers/'.$controller);
	    		} catch (Exception $e) {
	    			
	    		}
	    		$functions[$controller][] = get_class_methods($controller);
	    	}
	    }
	   	 
	    print_r($functions); 
	}
	function updateCategoria(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['retorno'] = (array)$this->Categorias->updateCategoria($cod_categoria,$nome_categoria,$categoria_pai,$glyphicon,$publico);
			echo json_encode($retorno['retorno']);
		} 
	}
	//Edita as propriedades de uma categoria
	function editar_categoria($cod_categoria){
		$this->load->model('categoria/Categorias');
		$data['categoria'] = (array)$this->Categorias->getCategoriaById($cod_categoria);
		$data['cod_categoria_pagina'] = $cod_categoria;
		$this->load->model('usuarios/Usuarios');
		$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		//$this->session->set_userdata($session);
		$data['categorias_base'] = $dados;
		$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
		$this->load->view('templates/main/header'); 
		$this->load->view('templates/main/navbar_adm');
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
	//Exclui as tags do tópico
	function deleteTags(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['cod_topico'] =$tag_name;
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->deleteTags($tag_name,$cod_topico);
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
			$descricao = '';
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->insertTopicos($nome_topico,$link_topico,$cod_categoria,$ordem,$this->user_id);
			if($link_topico=='' || $link_topico=='#')
			{
				$codigo_topico_novo = $retorno['retorno']['cod_topico'];
				$link_topico = "http://www.p2wiki.com.br/conteudo/visualizar/".$codigo_topico_novo;
				$descricao = '';
				$retorno['retorno'] = (array)$this->Categorias->updateTopico($codigo_topico_novo,$nome_topico,$link_topico,$cod_categoria,$ordem,$descricao,0);
				if($retorno['retorno']!='false')
				echo json_encode($codigo_topico_novo);
			}
			else
			{
				//die(print_r($retorno['retorno']['cod_topico']));
				echo json_encode($retorno['retorno']['cod_topico']);
			}
			return true;
		} 
	}
	//Insere as tags de cada tópico
	function insertTags(){
		
		$this->load->model('categoria/Categorias');
		
		if(isset($_POST)){
			extract($_POST);
			$retorno = array();
			$retorno['tag_name'] =$tag_name;
			$retorno['cod_topico'] =$cod_topico;
			
			$retorno['retorno'] = (array)$this->Categorias->insertTags($tag_name,$cod_topico);
			echo json_encode($retorno['retorno']);
			return true;
		} 
	}
	//insere os conteudos de cada tópico
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
			$retorno['publico'] = $publico;
			$retorno['user_group'] = $user_group;
			
			//$retorno['retorno'] = $this->form_dinamico->function($content_id,$nome,$nomeconteudo,$place,$largura,$SelectDesc,$MarginField,$Ordem);
			$retorno['retorno'] = (array)$this->Categorias->insertCategorias($nome_categoria,$categoria_pai,$glyphicon,$user_group,$publico);
			//die(print_r($retorno['retorno']['cod_topico']));
			echo json_encode($retorno['retorno']['cod_categoria']);
			return true;
		} 
	}
	public function validaCategoria(){
		if(!$this->session->userdata('sg_user_id')){
			echo json_encode(array('retorno'=>"Você não está logado !") );
			echo json_encode(array('resultado' => "false") );
			die();
		}
		if(!isset($_POST['cod_categoria']) && !isset($_POST['validado']) ){
			echo json_encode(array('retorno' => "Informações insulficientes !") );
			echo json_encode(array('resultado' => "false") );
			die();
		}
		//seta post
		$cod_categoria = trim($_POST['cod_categoria']);
		$validado = trim($_POST['validado']);

		$groups_adm = array();
		foreach ($this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id')) as $group_adm) {
			$groups_adm[] = $group_adm['group_id'];
		}
		$categories_info = $this->Categorias->getCategoriaById($cod_categoria);
		//verifica se tem permissao pra categoria
		if(in_array($categories_info->user_group, $groups_adm) ){
			$validou = $this->Categorias->validaCategoria($cod_categoria,$this->session->userdata('sg_user_id'),$validado);
			if($validou===true){
				$usuario = $this->Usuarios->getAllUsersAdvanced($this->session->userdata('sg_user_id') );
				echo $validado=='S'?json_encode(array('retorno' => "Categoria validada com sucesso !",'resultado' => "true",'data' => date('d-m-Y à\s H:i:s'),'usuario'=>$usuario[0]['username'] )):json_encode(array('retorno' => "Categoria invalidada com sucesso !",'resultado' => "true",'data' => date('d-m-Y à\s H:i:s'),'usuario'=>$usuario[0]['username'] ));
				die();
			}
		}
		else{
			echo json_encode(array('retorno' => "Você não tem permissão para acessar esta categoria !") );
			echo json_encode(array('resultado' => "false") );
			die();
		}
		//print_r($groups_adm);
		//print_r($categories_info);
		
	}

	public function aprovaTopico(){
		if(!$this->session->userdata('sg_user_id')){
			echo json_encode(array('retorno'=>"Você não está logado !") );
			echo json_encode(array('resultado' => "false") );
			die();
		}
		if(!isset($_POST['cod_topico']) && !isset($_POST['aprovado']) ){
			echo json_encode(array('retorno' => "Informações insulficientes !") );
			echo json_encode(array('resultado' => "false") );
			die();
		}
		//seta post
		$cod_topico = trim($_POST['cod_topico']);
		$aprovado = trim($_POST['aprovado']);
		$topicos_info = $this->Categorias->getTopicoById($cod_topico);
		$cod_categoria = $topicos_info->cod_categoria;
		$categories_info = $this->Categorias->getCategoriaById($cod_categoria);
		$groups_adm = array();
		foreach ($this->Grupos_model->getGroupsByUser($this->session->userdata('sg_user_id')) as $group_adm) {
			$groups_adm[] = $group_adm['group_id'];
		}
		//verifica se tem permissao pra categoria
		if(in_array($categories_info->user_group, $groups_adm) ){
			//print_r($topicos_info);
			//print_r($cod_categoria);
			//print_r($categories_info);
			//print_r($groups_adm);
			$aprovou = $this->Categorias->aprovaTopico($cod_topico,$aprovado);
			if($aprovou===true){
				$usuario = $this->Usuarios->getAllUsersAdvanced($this->session->userdata('sg_user_id') );
				echo $aprovado=='S'?json_encode(array('retorno' => "Tópico aprovado com sucesso !",'resultado' => "true",'data' => date('d-m-Y à\s H:i:s'),'usuario'=>$usuario[0]['username'] )):json_encode(array('retorno' => "Tópico desaprovado com sucesso !",'resultado' => "true",'data' => date('d-m-Y à\s H:i:s'),'usuario'=>$usuario[0]['username'] ));
				die();
			}
		}
		else{
			echo json_encode(array('retorno' => "Você não tem permissão para acessar este tópico !") );
			echo json_encode(array('resultado' => "false") );
			die();
		}
		//print_r($groups_adm);
		//print_r($categories_info);
		
	}

	public function addReferencia(){
		if(isset($_POST) && isset($_POST['cod_topico']) && isset($_POST['cod_conteudo'])){
			extract($_POST);
			$topicos_disponiveis = $this->getTopicosDisponiveis();
			if(array_key_exists($cod_topico, $topicos_disponiveis)===false){
				echo 'false';
				return true;
			}
			$codigo_valido = false;
			$coteudos = array();
			foreach($topicos_disponiveis as $codigo_topico_disponivel => $topico_disponivel){
				$conteudos_disponiveis_temp = $this->Categorias->getConteudos($codigo_topico_disponivel);
				foreach ($conteudos_disponiveis_temp as $conteudo_disponivel) {
					if($conteudo_disponivel->cod_conteudo == $cod_conteudo){
						$codigo_valido = true;
					}
					$coteudos[] = $conteudo_disponivel;
				}
			}
			if (!$codigo_valido) {
				//print_r($conteudos_disponiveis_temp);
				//die();
				echo json_encode(array('status'=>'false'));
				return true;
			}
			$retorno = $this->Categorias->addReferencia($cod_topico,$cod_conteudo);
			if($retorno){
				$topico = $this->Categorias->getTopicoById($cod_topico);
				echo json_encode(array('status'=>'true','link_topico'=>$topico->link_topico,'nome_topico'=>$topico->nome_topico));
				return true;
			}
			else{
				echo json_encode(array('status'=>'false'));
				return true;
			}
		}
		echo json_encode(array('status'=>'false'));
		return true;
	}
	public function getTopicosRelacionados($cod_topico){
		//if(isset($_POST) && $_POST['cod_topico']){
			$tags = $this->Categorias->getTagsTopico($cod_topico);
			print_r($tags);die();
			print_r($this->Categorias->getTopicosRelacionados((object)$tags));die();
		//}
	}
	//Função que inicia a página wiki/categoria
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
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));

			$data['categorias_base'] = $this->Categorias->getCategoriasBasePainel($group,$this->session->userdata('sg_nivel_acesso')); 
			if(!$data['categorias_base']){
				$data['categorias_base'] = (object)array();
			}
			$this->menu['menu']['categorias_base'] = $dados;
			//$this->session->set_userdata($session);
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/categoria',$data);
			$this->load->view('templates/main/scripts'); 
			//$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}
	
}

/* End of file categoria.php */
/* Location: ./application/controllers/categoria.php */