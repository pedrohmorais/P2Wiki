<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente extends CI_Controller {
	private $cliente_acessado;
	private $data_user;
	public $sg_clientes;
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
		$this->load->model('clientes/Contatos');
		$session['sg_clientes'] = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id')); 
		$this->sg_clientes = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id'));
		$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;

		$this->data_user =  $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));

		$this->menu['menu']['grupos'] = $this->Usuarios->getUserGroupInativos($this->session->userdata('sg_user_id'));
		//$this->menu = $ctBase;
		

		$this->session->set_userdata($session);
        $this->load->library('form_validation');
        $this->load->helper('utilitarios');
    }
    private function getCaptcha(){
		return $this->session->flashdata('captcha');
	}

	//Função que inicia a página wiki/conteudo
	public function index()
	{
		$this->load->model('usuarios/Usuarios'); 
		$this->load->model('categoria/Categorias');
		
		if($this->session->userdata('sg_user_id'))
		{	
		
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
							
			
		
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$this->data_user = $data_user;
			
			//$this->session->set_userdata($session);
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/cliente');
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}
	private function verificaCliente($cliente){
		foreach ($this->session->userdata('sg_clientes') as $clientes) {
			if($clientes['cod_cliente'] == $cliente){
				return $clientes;
			}
		}
		redirect('/', 'redirect');
	}
	//Função que inicia a página wiki/conteudo
	public function uploadGallery()
	{
		if($this->session->userdata('sg_user_id'))
		{	
			if($this->session->userdata('sg_clientes')){
				//verifica se o cliente informado tem permissão na session
				$dados_cliente_up = $this->verificaCliente(trim($_GET['cliente']));
				if ($_FILES['arquivo']['error'] != 0) {
					redirect('/cliente/painel/'.trim($_GET['cliente']), 'redirect');
				}
				//colocar aqui script para limitar tamanho maximo
				if ($_FILES['arquivo']['size'] > (64 * 1000 * 1000)) {
					redirect('/cliente/painel/'.trim($_GET['cliente']), 'redirect');  
				}
				//diretorio onde serao gravados
				$pasta = $_SERVER['DOCUMENT_ROOT'].'/application/third_party/cliente_galeria/'.trim($_GET['cliente']).'/';
				if(!file_exists ( $pasta )){
					mkdir($pasta);
				}
				if(move_uploaded_file($_FILES['arquivo']['tmp_name'],  $pasta.$_FILES['arquivo']['name']) ){
					/* le todos os arquivos da pasta
				   	$diretorio = dir($pasta);
				    $arquivos = array();
				  	while($arquivo = $diretorio -> read()){
				  		if($arquivo !== '..' && $arquivo !== '.'){
					  		$arquivos[] = $arquivo;
					  	}
				   	}
				   	$diretorio -> close();
				   	print_r($arquivos);die();
				   	*/
				}
				
			}
			redirect('/cliente/painel/'.trim($_GET['cliente']), 'redirect');
		}
		else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	//Função que faz o gerenciamento do painel de usuarios
	public function painel($cod_cliente)
	{
		$autorized = array();
		$this->session->set_userdata('ultimo_cliente_acessado', $cod_cliente);
        $this->cliente_acessado = $cod_cliente;
		$session['sg_clientes'] = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id'));
		$this->session->set_userdata($session);
		foreach($session['sg_clientes'] as $auth){
			$autorized[] = $auth['cod_cliente'];
		}
		// le todos os arquivos da pasta
		$pasta = $_SERVER['DOCUMENT_ROOT'].'/application/third_party/cliente_galeria/'.$cod_cliente.'/';
		$link_site = site_url().'application/third_party/cliente_galeria/'.$cod_cliente.'/';
		if(file_exists($pasta)){
	   		$diretorio = dir($pasta);
	   	}
	   	else{
	   		$diretorio = false;
	   	}
	    $arquivos = array();
	    $imagens = array();
	    $tamanho = 0;
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
			  				$imagens[] = array('nome' => $arquivo,'tamanho' => filesize($pasta.$arquivo), 'link' => $link_site.$arquivo );
			  				break;
			  			default:
			  				$arquivos[] = array('nome' => $arquivo,'tamanho' => filesize($pasta.$arquivo), 'link' => $link_site.$arquivo );
			  				break;
			  		}
			  		$tamanho += filesize($pasta.$arquivo);
			  	}
		   	}
		   	//print_r($imagens);
		   	//print_r($arquivos);
		   	//die();
		   	//seria legal colocar um filtro por imagens e depois colocar em um painel de imagens especifico
		   	$diretorio -> close();
	    }
	    else{
	    	$tamanho = 0;
	    }
	   
	   	$data['tamanho_utilizado'] = $tamanho;
	   	$data['lista_arquivos'] = $arquivos;
	   	$data['lista_imagens'] = $imagens;
	   	//criar função de update.
	   	//$this->Clientes->updateGaleriaTamanho($cod_cliente);


		$position_array = array_search($cod_cliente, $autorized );
		if(count($this->session->userdata('sg_clientes')) > 0 && isset($cod_cliente) && $position_array !== false)
		{	
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			
			$data['tipo_cliente'] = $session['sg_clientes'][$position_array]['tipo'];
			$data['cliente'] = $this->Clientes->getClientbyCod($cod_cliente,$data['tipo_cliente']);

			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			if($data['tipo_cliente']=='F'){
				$data['cliente_fisico'] = $this->Clientes->getClienteFisico($cod_cliente);
				$this->load->view('pages/main/painel_cliente_fisico',$data);
			}
			else{
				$data['cliente_juridico'] = $this->Clientes->getClienteJuridico($cod_cliente);
				$this->load->view('pages/main/painel_cliente_juridico',$data);
			}
			$data_contato['contatos_cliente'] = $this->Contatos->selectContatoPorCliente($cod_cliente);
			$this->load->view('pages/main/contatos_cliente',$data_contato);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer');  
			
		}else{
			redirect('/', 'redirect');
		}
	}

	//Função que faz o gerenciamento de usuarios
	public function gerenciar()
	{
		$this->load->model('categoria/Categorias');
		if($this->session->userdata('sg_nivel_acesso')==4)
		{	
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			$this->load->model('usuarios/Usuarios');
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;				
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$this->data_user  = $data_user;
			//$this->session->set_userdata($session);



			$this->load->model('clientes/Clientes');
			$data['cliente'] = $this->Clientes->getAllClients();
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/gerenciar_cliente',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	//Função que faz o gerenciamento avançado de usuarios
	public function avancado($cod_cliente,$tipo)
	{
		//die($cod_cliente.'     '.$tipo);
        $this->session->set_userdata('ultimo_cliente_acessado', $cod_cliente);
        $this->cliente_acessado = $cod_cliente;
		if(!isset($tipo))
			$tipo = 'F';

		$this->load->model('categoria/Categorias');
		$this->load->model('usuarios/Usuarios');
		$this->load->model('clientes/Clientes');
		if($this->session->userdata('sg_nivel_acesso')==4)
		{	
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$this->data_user  = $data_user;
			//$this->session->set_userdata($session);

			$data['cliente'] = $this->Clientes->getAllClientsAdvanced($cod_cliente);
			/*
			$data['lista_clientes'] = $this->Clientes->getAllClients();
			$data['criado_por'] = $this->Usuarios->getAllUsersAdvanced($data['cliente'][0]['created_by']);
			$data['criado_por'] = $data['criado_por'][0]['nome'];
			$data['last_mod_by_name'] = $this->Usuarios->getAllUsersAdvanced($data['usuario'][0]['last_mod_by']);
			$data['last_mod_by_name'] = $data['last_mod_by_name'][0]['nome'];
			*/
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			if($data['cliente'][0]['tipo']=='F'){
				$data['cliente_fisico'] = $this->Clientes->getClienteFisico($cod_cliente);
				$this->load->view('pages/main/gerenciar_cliente_fisico',$data);

			}
			else{
				$data['cliente_juridico'] = $this->Clientes->getClienteJuridico($cod_cliente);
				$this->load->view('pages/main/gerenciar_cliente_juridico',$data);
			}
			
			$data_contato['contatos_cliente'] = $this->Contatos->selectContatoPorCliente($cod_cliente);
			$this->load->view('pages/main/contatos_cliente',$data_contato);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	public function inserirContato(){
		$clientes_que_adm = array();
		foreach ($this->session->userdata('sg_clientes') as $cliadm) {
			$clientes_que_adm[] = $cliadm['cod_cliente'];
		}
		$cod_cliente = isset($_POST['cod_cliente'])?$_POST['cod_cliente']:'';
		if(!isset($_POST) && !array_search($cod_cliente, $clientes_que_adm) && $this->session->userdata('sg_nivel_acesso') < 4){
			echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você não possui permissão para acesar esta função ou dados foram enviados incorretamente !")));
			return false;
		}
		$this->session->set_userdata('ultimo_cliente_acessado', $cod_cliente);
		if(isset($cod_cliente) && isset($_POST['contato']) && isset($_POST['falar_com']) && isset($_POST['tipo_contato']));
		$insereContato = $this->Contatos->inserirContato($cod_cliente,$_POST['tipo_contato'],$_POST['contato'],$_POST['falar_com'] );
		if($insereContato){
			echo json_encode(array('retorno'=>"true",'erro'=>htmlspecialchars('Contato inserido com sucesso !') ) );
			return true;
		}
		else{
			echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars('Erro ao inserir contato !') ) );
			return false;
		}
		print_r($this->Usuarios->selectClientsByAdm($this->session->userdata('sg_user_id')));
		return false;
	}

	public function registerClient()
	{
		if($_POST){
			 foreach($_POST as $key => $value){
                set_value($key, $this->input->post($key));
            }
			//print_r($_POST);
			//die();
			$this->form_validation->set_rules('pessoa','Pessoa','required');
			if ($_POST['pessoa'] != 'F' && $_POST['pessoa'] != 'J') {
			echo 'Tipo de pessoa inválido !';
			die();
			}
			//valida os campos do form
            $this->form_validation->set_rules('captcha','Captcha','required');
			$this->form_validation->set_rules('nome','Nome','required');
			$this->form_validation->set_rules('Cep','Cep','required|exact_length[9]');
			if (validarCep($_POST['Cep'])===false) {
				echo 'CEP inválido !';
				die();
			}
			$this->form_validation->set_rules('email','Email','required');
			$this->form_validation->set_rules('telefone','Telefone','required');
			$this->form_validation->set_rules('celular','Celular','required');
			$this->form_validation->set_rules('mensagem','Mensagem','required|max_length[500]');
			$this->form_validation->set_rules('endereco','Endereco','required');
			$this->form_validation->set_rules('complemento','Complemento','max_length[100]');
			$this->form_validation->set_rules('bairro','Bairro','required');
			$this->form_validation->set_rules('cidade','Cidade','required');
			$this->form_validation->set_rules('estado','Estado','required');
			$this->form_validation->set_rules('pessoa','Pessoa','required');
			if ($_POST['pessoa'] == 'F') {
				$this->form_validation->set_rules('rg','Rg','required|max_length[12]');
				if ($_POST['sexo'] != 'F' && $_POST['sexo'] != 'M') {
					echo 'Sexo inválido !';
					die();
				}
				switch ($_POST['estado']) {
					case 'AC':
					case 'AL':
					case 'AM':
					case 'AP':
					case 'BA':
					case 'CE':
					case 'DF':
					case 'ES':
					case 'GO':
					case 'MA':
					case 'MT':
					case 'MS':
					case 'MG':
					case 'PA':
					case 'PB':
					case 'PR':
					case 'PE':
					case 'PI':
					case 'RJ':
					case 'RN':
					case 'RO':
					case 'RS':
					case 'RR':
					case 'SC':
					case 'SE':
					case 'SP':
					case 'TO':
						
						break;
					
					default:
						echo 'Estado inválido !';
						die();
						break;
				}
				switch ($_POST['estado_rg']) {
					case 'AC':
					case 'AL':
					case 'AM':
					case 'AP':
					case 'BA':
					case 'CE':
					case 'DF':
					case 'ES':
					case 'GO':
					case 'MA':
					case 'MT':
					case 'MS':
					case 'MG':
					case 'PA':
					case 'PB':
					case 'PR':
					case 'PE':
					case 'PI':
					case 'RJ':
					case 'RN':
					case 'RO':
					case 'RS':
					case 'RR':
					case 'SC':
					case 'SE':
					case 'SP':
					case 'TO':
						
						break;
					
					default:
						echo 'Estado do RG inválido !';
						die();
						break;
				}
				$this->form_validation->set_rules('estado_rg','Estado do rg','required');
				$this->form_validation->set_rules('data_nasc','Data nasc','required');
				$this->form_validation->set_rules('cpf','Cpf','required|exact_length[14]');
				if (validaCPF($_POST['cpf'])===false) {
					echo 'CPF inválido !';
					die();
				}
				$this->form_validation->set_rules('sexo','Sexo','required');
			}
			else{
				$this->form_validation->set_rules('ra_social','Razão Social','required');
				$this->form_validation->set_rules('cnpj','Cnpj','required');
				if (valida_cnpj($_POST['cnpj'])===false) {
					echo 'CNPJ inválido !';
					die();
				}
			}
			
			if($this->form_validation->run()===false){
				$mensagem = str_replace('<p>', '', validation_errors());
                $mensagem = substr($mensagem, 0, strpos($mensagem, '</p>'));
                echo $mensagem;
			}
			else{
				//$_POST['cpf'] = preg_replace('#[^0-9]#','',strip_tags($_POST['cpf']));
				//$_POST['rg'] = preg_replace('#[^0-9]#','',strip_tags($_POST['rg']));
				//$_POST['cnpj'] = preg_replace('#[^0-9]#','',strip_tags($_POST['cnpj']));
				//somente numeros no cpf
				$_POST['cpf'] = preg_replace("/[^0-9]/", "",$_POST['cpf']);
				$_POST['Cep'] = preg_replace('#[^0-9]#','',strip_tags($_POST['Cep']));
				$insere = $this->Clientes->inserirCliente($_POST);
				$inseriu = false;
				if($insere !== false){
					$insereEmail = $this->Contatos->inserirContato($insere,'EM',$_POST['email'],$_POST['nome'] );
					$insereTel = $this->Contatos->inserirContato($insere,'TF',$_POST['telefone'],$_POST['nome'] );
					$insereCel = $this->Contatos->inserirContato($insere,'TM',$_POST['celular'],$_POST['nome'] );
					if ($insereEmail !== false && $insereTel !== false && $insereCel !== false) {
						$inseriu = true;
						$link = site_url('cliente/avancado/'.trim($insere).'/'.trim($_POST['pessoa']).'/');
						$this->enviarEmail_requisicao($insere,$_POST['nome'],$_POST['mensagem'],$link);
						sleep(3);
						$this->enviarEmail_cliente(trim($_POST['email']),$_POST['nome'],$_POST['mensagem']);
					}
				}

				echo $inseriu === true?'success':'fail';
			}
			/*
            $this->form_validation->set_rules('titulo', 'Título', 'required|max_length[100]');
            $this->form_validation->set_rules('titulo_generico', 'Título Genérico', 'required|max_length[100]');
            $this->form_validation->set_rules('resenha', 'Resenha', 'required|max_length[200]');
            $this->form_validation->set_rules('descricao', 'Descrição', 'required');
            $this->form_validation->set_rules('preco', 'Preço', 'required');
            $this->form_validation->set_rules('preco_de', 'Preço de', 'required');
            $this->form_validation->set_rules('frete_gratis', 'Frete Grátis', 'required');
            $this->form_validation->set_rules('id_tipo', 'ID Tipo', 'is_natural');
            $this->form_validation->set_rules('tipo_cartao', 'Tipo do Cartão', 'required');
            $this->form_validation->set_rules('estoque', '', 'required');
            $this->form_validation->set_rules('id_fabricante', 'Fabricante', 'required|is_natural');
            $this->form_validation->set_rules('id_categoria', 'Categoria', 'required|is_natural');
            $this->form_validation->set_rules('id_subcategoria', 'Subcategoria', 'is_natural');
            $this->form_validation->set_rules('id_fornecedor', 'Fornecedor', 'required|is_natural');
            $this->form_validation->set_rules('id_departamento', 'Departamento', 'required|is_natural');
            $this->form_validation->set_rules('id_voltagem', 'Voltagem', 'required|is_natural');
            $this->form_validation->set_rules('id_cor', 'Cor', 'required|is_natural');
            $this->form_validation->set_rules('tabela', 'Tabela', 'required');

            nome
			Cep
			endereco
			complemento
			bairro
			cidade
			estado
			pessoa
			rg
			estado_rg
			data_nasc
			cpf
			sexo
			ra_social
			cnpj
			captcha*/
		}
	}

	private function enviarEmail_cliente($email_destinatario,$nome,$motivo){
		$this->load->helper('mail');
		/*
		$enviado    =   $this->enviarEmail ( $emailPresenteado, $nomePresenteado,   $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
		$to, $nome, $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password 
		$confirmacao    =   '';
        $sender         =   'rudney@dshop.com.br';
        $userName       =   'rudney@dshop.com.br';
        $from           =   'rudney@dshop.com.br';
        $replyTo        =   'rudney@dshop.com.br';
        $fromName       =   'Pedidos Inseridos Por Planilha';
        $password       =   'rud@dshop@ney';
        
        $this->enviarEmail ( $_SESSION['emailUsuario'], $_SESSION["nomeUsuario"], utf8_encode ( 'Relatório de Pedidos Virtuais - Planilha' ), 
                utf8_encode ( $html ), $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
		*/
        $confirmacao    =   '';
        $sender         =   'administracao@p2wiki.com.br';
        $userName       =   'administracao@p2wiki.com.br';
        $from           =   'administracao@p2wiki.com.br';
        $replyTo        =   'administracao@p2wiki.com.br';
        $fromName       =   'P2Wiki';
        $password       =   'adm!@#p2wiki';
        $nome = htmlspecialchars($nome);
        $motivo = htmlspecialchars($motivo);
        $html 			= file_get_contents(site_url('application/third_party/templates_html/template_email_solicitacaoCliente.html'));
        $html  			= str_replace('##NOME##', $nome, $html);
        $html  			= str_replace('##MOTIVO##', $motivo, $html);
        //print_r($html);
		$enviouEmail = enviarEmail($email_destinatario,$nome ,'Sua requisição P2wiki ',$html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password);
		return $enviouEmail;
	}

	private function enviarEmail_requisicao($codigo,$nome,$motivo,$link){
		$this->load->helper('mail');
		/*
		$enviado    =   $this->enviarEmail ( $emailPresenteado, $nomePresenteado,   $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
		$to, $nome, $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password 
		$confirmacao    =   '';
        $sender         =   'rudney@dshop.com.br';
        $userName       =   'rudney@dshop.com.br';
        $from           =   'rudney@dshop.com.br';
        $replyTo        =   'rudney@dshop.com.br';
        $fromName       =   'Pedidos Inseridos Por Planilha';
        $password       =   'rud@dshop@ney';
        
        $this->enviarEmail ( $_SESSION['emailUsuario'], $_SESSION["nomeUsuario"], utf8_encode ( 'Relatório de Pedidos Virtuais - Planilha' ), 
                utf8_encode ( $html ), $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
		*/
        $confirmacao    =   '';
        $sender         =   'administracao@p2wiki.com.br';
        $userName       =   'administracao@p2wiki.com.br';
        $from           =   'administracao@p2wiki.com.br';
        $replyTo        =   'administracao@p2wiki.com.br';
        $fromName       =   'P2Wiki';
        $password       =   'adm!@#p2wiki';
        $nome = htmlspecialchars($nome);
        $motivo = htmlspecialchars($motivo);
        $html 			= file_get_contents(site_url('application/third_party/templates_html/template_email_solicitacao.html'));
        $html  			= str_replace('##NOME##', $nome, $html);
        $html  			= str_replace('##MOTIVO##', $motivo, $html);
        $html  			= str_replace('##CODIGO##', $codigo, $html);
        $html  			= str_replace('##LINK##', $link, $html);
        //print_r($html);
		$enviouEmail = enviarEmail('administracao@p2wiki.com.br',$nome ,'Requisição de acesso P2wiki ',$html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password);
		return $enviouEmail;
	}

	function updateClient(){
		if(isset($_POST)){
			extract($_POST);
			$this->load->model('clientes/Clientes');
			$retorna = $this->Clientes->updateClient($cod_cliente,$nome,$cep,$tipo,$data_cad,$data_exp);
			echo json_encode($retorna);
			return true;
		}
	}

	function addClient(){
		if(isset($_POST)){
			extract($_POST);
			$this->load->model('clientes/Clientes');
			$retorna = $this->Clientes->addClient($nome,$tipo);
			echo json_encode($retorna);
			return true;
		}
	}

	public function updateUserAvancado(){
		//die(print_r($_POST));
		if(isset($_POST)){
			$this->load->model('usuarios/Usuarios');
			$_POST['last_mod'] = date('o').'-'.date('m').'-'.date('d').' '.date('G').':'.date('i').':'.date('s');
			$retorna = $this->Usuarios->updateUserAvancado($_POST);
			redirect('/usuario/avancado/'.$_POST['user_id'], 'redirect');

			//die(print_r($retorna));
		}
	}

	function updateClientAvancadoFisico(){
		if(!$this->session->userdata('sg_user_id'))
		{
			echo "Você deve estar logado para acesar esta função !";
			die();
		}
		if(isset($_POST)){
			extract($_POST);
			if(isset($ativo)){
				$ativo = 1;
			}
			else{
				$ativo = 0;
			}
			$data_user = $this->data_user ;
			$data_user = $data_user[0];
			$this->load->model('clientes/Clientes');
			$this->Clientes->updateClientAvancado($cod_cliente,$nome,$cep,$endereco,$complemento,$bairro,$cidade,$estado,$this->session->userdata('sg_user_id'),$ativo );
			

			//cod_cliente 	nome 		cep 	endereco 	complemento 	bairro 	cidade 	estado 	last_mod_by 

			$this->Clientes->updateClientAvancadoFisico($cod_cliente,$rg,$estado_rg,$cpf,$sexo,$data_nasc);
			//die('aqui');
			//echo json_encode($retorna);
			redirect('/cliente/avancado/'.$cod_cliente.'/F', 'redirect');
		}
	}

	function updateClientAvancadoJuridico(){
		if(!$this->session->userdata('sg_user_id'))
		{
			echo "Você deve estar logado para acesar esta função !";
			die();
		}
		if(isset($_POST)){
			extract($_POST);
			if(isset($ativo)){
				$ativo = 1;
			}
			else{
				$ativo = 0;
			}
			$data_user = $this->data_user ;
			$data_user = $data_user[0];
			$this->load->model('clientes/Clientes');
			$this->Clientes->updateClientAvancado($cod_cliente,$nome,$cep,$endereco,$complemento,$bairro,$cidade,$estado,$this->session->userdata('sg_user_id'),$ativo );
			

			//cod_cliente 	nome 		cep 	endereco 	complemento 	bairro 	cidade 	estado 	last_mod_by 

			$this->Clientes->updateClientAvancadoJuridico($cod_cliente,$cnpj,$ra_social);
			//die('aqui');
			//echo json_encode($retorna);
			redirect('/cliente/avancado/'.$cod_cliente.'/J', 'redirect');
		}
	}

	function updateClientAvancadoFisicoPainel(){
		if(isset($_POST)){
			extract($_POST);
			$data_user = $this->data_user ;
			$data_user = $data_user[0];
			$this->load->model('clientes/Clientes');
			$this->Clientes->updateClientAvancado($cod_cliente,$nome,$cep,$endereco,$complemento,$bairro,$cidade,$estado,$this->session->userdata('sg_user_id') );
			

			//cod_cliente 	nome 		cep 	endereco 	complemento 	bairro 	cidade 	estado 	last_mod_by 

			$this->Clientes->updateClientAvancadoFisico($cod_cliente,$rg,$estado_rg,$cpf,$sexo,$data_nasc);
			//die('aqui');
			//echo json_encode($retorna);
			redirect('/cliente/painel/'.$cod_cliente, 'redirect');
		}
	}

	function updateClientAvancadoJuridicoPainel(){
		if(isset($_POST)){
			extract($_POST);
			$data_user = $this->data_user ;
			$data_user = $data_user[0];
			$this->load->model('clientes/Clientes');
			$this->Clientes->updateClientAvancado($cod_cliente,$nome,$cep,$endereco,$complemento,$bairro,$cidade,$estado,$this->session->userdata('sg_user_id') );
			

			//cod_cliente 	nome 		cep 	endereco 	complemento 	bairro 	cidade 	estado 	last_mod_by 

			$this->Clientes->updateClientAvancadoJuridico($cod_cliente,$cnpj,$ra_social);
			//die('aqui');
			//echo json_encode($retorna);
			redirect('/cliente/painel/'.$cod_cliente, 'redirect');
		}
	}
	public function insertUserAdm(){
		if(!$this->session->userdata('sg_user_id') || !$this->session->userdata('sg_nivel_acesso')  )
		{
			echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você deve estar logado para acesar esta função !")));
			die();
		}
		
		if(isset($_POST)){
			extract($_POST);
			if(isset($ativo)){
				$ativo = 1;
			}
			else{
				$ativo = 0;
			}
			//seta clientes que eu administro
			$clientes_que_adm = array();
			foreach ($this->session->userdata('sg_clientes') as $cliadm) {
				$clientes_que_adm[] = $cliadm['cod_cliente'];
			}
			if(array_search($cod_cliente, $clientes_que_adm) === false && $this->session->userdata('sg_nivel_acesso') < 4){
				echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você não possui permissão para acesar esta função !")));
				die();
			}
			$this->cliente_acessado = $cod_cliente;
			if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",$usersenha)<1) {
			    echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Sua senha deve conter pelo menos 8 caracteres com números e letras maiusculas !") ) );
			    return false;
			}
			$data_user = $this->data_user ;
			$data_user = $data_user[0];
			$this->load->model('usuarios/Usuarios');
			if($this->Usuarios->getUserByUsername($username)!=false){
				echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Username já existe, por favor escolha outro !")));
				die();
			}
			$addFirstUserResposta = $this->Usuarios->addUser($nomedousuario,$username,$usersenha,$useremail,2,$this->session->userdata('sg_user_id'));
			if($addFirstUserResposta == 'false'){
				echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Erro ao inserir cliente no banco de dados !")));
				die();
			}
			$enviouEmailUsuario = $this->enviarEmail_Novo_Usuario($useremail,$nomedousuario,$username,$usersenha);
			
			if($tipo_usuario=="COMUM"){
				echo json_encode(array('retorno'=>"success",'erro'=>htmlspecialchars("Operação realizada sem erros !")));
			die();
			}
			$addClienteFoi = $this->Usuarios->addUserAdm($cod_cliente,$addFirstUserResposta);
			if($addClienteFoi == 'false'){
				echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Erro ao setar usuário como administrador !")));
				die();
			}
			echo json_encode(array('retorno'=>"success",'erro'=>htmlspecialchars("Operação realizada sem erros !")));
			die();
		}
	}
	private function enviarEmail_Novo_Usuario($email_destinatario,$nome,$username,$senha){
		$this->load->helper('mail');
		/*
		$enviado    =   $this->enviarEmail ( $emailPresenteado, $nomePresenteado,   $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
		$to, $nome, $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password 
		$confirmacao    =   '';
        $sender         =   'rudney@dshop.com.br';
        $userName       =   'rudney@dshop.com.br';
        $from           =   'rudney@dshop.com.br';
        $replyTo        =   'rudney@dshop.com.br';
        $fromName       =   'Pedidos Inseridos Por Planilha';
        $password       =   'rud@dshop@ney';
        
        $this->enviarEmail ( $_SESSION['emailUsuario'], $_SESSION["nomeUsuario"], utf8_encode ( 'Relatório de Pedidos Virtuais - Planilha' ), 
                utf8_encode ( $html ), $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
		*/
        $confirmacao    =   '';
        $sender         =   'administracao@p2wiki.com.br';
        $userName       =   'administracao@p2wiki.com.br';
        $from           =   'administracao@p2wiki.com.br';
        $replyTo        =   'administracao@p2wiki.com.br';
        $fromName       =   'P2Wiki';
        $password       =   'adm!@#p2wiki';
        $nome = htmlspecialchars($nome);
        $html 			= file_get_contents(site_url('application/third_party/templates_html/template_email_novo_usuario.html'));
        $html  			= str_replace('##NOME##', $nome, $html);
        $html  			= str_replace('##USUARIO##', $username, $html);
        $html  			= str_replace('##SENHA##', $senha, $html);
        //print_r($html);
		$enviouEmail = enviarEmail($email_destinatario,$nome ,'Novo usuário P2wiki ',$html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password);
		return $enviouEmail;
	}

}

/* End of file categoria.php */
/* Location: ./application/controllers/categoria.php */