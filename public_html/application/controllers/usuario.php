<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {

	private $sg_clientes;
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
		$this->sg_clientes = $this->Clientes->getClienteByUser($this->session->userdata('sg_user_id')); 
		$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
		$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
		$this->menu['menu']['categorias_base'] = $dados;
		//$this->session->set_userdata($session);
	
    }

	//Função que inicia a página wiki/conteudo
	public function index()
	{
	
		$this->load->model('categoria/Categorias');
		
		if($this->session->userdata('sg_user_id'))
		{	
		
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
						
			
		
			$this->load->model('usuarios/Usuarios');
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$this->data_user = $data_user;
			
			//$this->session->set_userdata($session);
			$data['data_user'] = $this->data_user;
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/usuario',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	//Função que faz o gerenciamento de usuarios
	public function gerenciar()
	{
		$this->load->model('categoria/Categorias');
		if($this->session->userdata('sg_nivel_acesso')==4 || $this->session->userdata('sg_nivel_acesso')==3)
		{	
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			
			$this->load->model('usuarios/Usuarios');
			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$this->data_user = $data_user;
			//$this->session->set_userdata($session);



			$this->load->model('usuarios/Usuarios');
			$data['usuario'] = $this->Usuarios->getAllUsers();
			
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/gerenciar_usuario',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			//$this->session->unset_userdata('sg_user_id');
			//$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	//Função que faz o gerenciamento avançado de usuarios
	public function avancado($user_id)
	{
		$this->load->model('categoria/Categorias');
		if($this->session->userdata('sg_nivel_acesso')==4 || $this->session->userdata('sg_nivel_acesso')==3)
		{	
			if($_POST && isset($_POST['u_nome'])){
				$this->Categorias->setDataUser($_POST, $this->session->userdata('sg_user_id'));
			}
			$dados = $this->Categorias->getCategoriasBase();
			$this->menu['menu']['categorias_base'] = $dados;			
			
			$data_user = $this->Categorias->getDataUser($this->session->userdata('sg_user_id'));
			$this->data_user = $data_user;
			//$this->session->set_userdata($session);

			$this->load->model('usuarios/Usuarios');

			$data['lista_usuarios'] = $this->Usuarios->getAllUsers();

			$data['usuario'] = $this->Usuarios->getAllUsersAdvanced($user_id);
			
			$data['criado_por'] = $this->Usuarios->getAllUsersAdvanced($data['usuario'][0]['created_by']);
			$data['criado_por'] = $data['criado_por'][0]['nome'];

			$data['last_mod_by_name'] = $this->Usuarios->getAllUsersAdvanced($data['usuario'][0]['last_mod_by']);

			//print_r($data['last_mod_by_name']);die();

			$data['last_mod_by_name'] = $data['last_mod_by_name'][0]['nome'];
			$data['last_mod_by'] = $data['usuario'][0]['last_mod_by'];


			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			$this->load->view('templates/main/navbar_adm');
			$this->load->view('pages/main/gerenciar_usuario_avancado',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
			
		}else{
			
			$this->session->unset_userdata('sg_user_id');
			$this->session->set_flashdata('session_expire', '<span>Sua sessão expirou, entre novamente</span>');
			
			redirect('/', 'redirect');
		}
	}

	function updateUser(){
		if($this->session->userdata('sg_nivel_acesso') && $this->session->userdata('sg_nivel_acesso')>= 2 && $this->session->userdata('sg_user_id'))
		{	
			if(isset($_POST)){
				extract($_POST);
				$last_mod_by = $this->session->userdata('sg_user_id');
				$this->load->model('usuarios/Usuarios');
				if($nivel_acesso > $this->session->userdata('sg_nivel_acesso')){
					echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você não tem permissão para dar este nível de acesso !")));
					return false;
				}
				$retorna = $this->Usuarios->updateUser($user_id,$nome,$username,$senha,$email,$nivel_acesso,$last_mod_by);
				echo json_encode($retorna);
				return true;
			}
		}
		else{
			echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você não tem permissão para adicionar usuários !")));
		}
	}

	function addUser(){
		if($this->session->userdata('sg_nivel_acesso') && $this->session->userdata('sg_nivel_acesso')>= 2 && $this->session->userdata('sg_user_id'))
		{	
			if(isset($_POST)){
				extract($_POST);
				//O exemplo abaixo verifica se a senha é "forte", ou seja, a senha deve
				//ter pelo menos 8 caracteres e deve conter pelo menos uma letra
				//minúscula, uma letra maiúscula e um algarismo:


				if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",
				$senha)) {
				    //echo "Sua senha é forte!";
				} else {
				    echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Sua senha deve conter pelo menos 8 caracteres com números e letras maiusculas !.")));
				    return false;
				}
					
				$created_by = $this->session->userdata('sg_user_id');	
				$this->load->model('usuarios/Usuarios');
				if($nivel_acesso > $this->session->userdata('sg_nivel_acesso')){
					echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você não tem permissão para dar este nível de acesso !")));
					return false;
				}
				$retorna = $this->Usuarios->addUser($nome,$username,$senha,$email,$nivel_acesso,$created_by);
				echo json_encode($retorna);
				return true;
			}
		}
		else{
			echo json_encode(array('retorno'=>"false",'erro'=>htmlspecialchars("Você não tem permissão para adicionar usuários !")));
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

	public function updateClientAvancadoFisico(){
		//die(print_r($_POST));
		if(isset($_POST)){
			$this->load->model('usuarios/Usuarios');
			$_POST['last_mod'] = date('o').'-'.date('m').'-'.date('d').' '.date('G').':'.date('i').':'.date('s');
			$retorna = $this->Usuarios->updateClientAvancadoFisico($_POST);
			redirect('/cliente/avancado/'.$_POST['cod_cliente'].'/F', 'redirect');

			//die(print_r($retorna));
		}
	}

	public function esqueciMinhaSenha(){
		if(!isset($_POST['email_recuperar']))
			$this->session->set_flashdata('msg_error', 'Email inválido !');
		if(isset($_POST['textCaptcha']  )){
			$captcha = $this->session->flashdata('captcha');
			if(strtoupper($_POST['textCaptcha']) == strtoupper($captcha)){
				$infos = $this->Usuarios->getUserByEmail($_POST['email_recuperar']);
				$infos = $infos[0];
				$infos['senha'] = $this->geraSenha();
				$foi = $this->Usuarios->novaSenha($infos['user_id'],$infos['senha']);
				if($foi == "true"){
					$this->enviarEmail_recsenha($infos['email'],$infos['nome'],$infos['username'],$infos['senha']);
				}
				$this->session->set_flashdata('msg_error', "Senha alterada com sucesso !");
				redirect('/','redirect');
				return;
			}
		}
		$this->session->set_flashdata('msg_error', 'false');
		echo 'errado';
		redirect('/','redirect');
	}

	private function enviarEmail_recsenha($email_destinatario,$nome,$usuario,$senha){
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
        $usuario = htmlspecialchars($usuario);
        $senha = htmlspecialchars($senha);
        $html 			= file_get_contents(site_url('application/third_party/templates_html/template_email_esqueci_senha.html'));
        $html  			= str_replace('##NOME##', $nome, $html);
        $html  			= str_replace('##USUARIO##', $usuario, $html);
        $html  			= str_replace('##SENHA##', $senha, $html);
        //print_r($html);
		$enviouEmail = enviarEmail($email_destinatario,$nome ,'Recuperar senha P2wiki ',$html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password);
		return $enviouEmail;
	}		

	private function geraSenha(){
		$str = array();
		$str[0] = "qwertyuiopasdfghjklçzxcvbnm";
		$str[1] = "QWERTYUIOPASDFGHJKLÇZXCVBNM";
		$str[2] = "123456789123456789123456789";
		$str[3] = "!@+-*/()[]!@+-*/()[]!@+-*/(";
		$senha = "";
		for($i=0;$i<=rand(8,12);$i++){
			$senha .= substr($str[rand(0,3)],rand(0,27),1);
		}
		return $senha;
	}

	public function uploadFotoPerfil(){
		if($this->session->userdata('sg_user_id')){
			if ($_FILES['arquivo']['error'] != 0) {
				$this->session->set_flashdata('uploadFotoUsuario', array('status'=>'erro','mensagem'=>htmlspecialchars("Erro ao subir arquivo !")));
				redirect('/usuario','redirect');
			}
			//colocar aqui script para limitar tamanho maximo
			if ($_FILES['arquivo']['size'] > (5 * 1000 * 1000)) {
				$this->session->set_flashdata('uploadFotoUsuario', array('status'=>'erro','mensagem'=>htmlspecialchars("O arquivo é muito grande !")));
				redirect('/usuario','redirect');
			}
			//diretorio onde serao gravados
			$pasta = $_SERVER['DOCUMENT_ROOT'].'/application/third_party/usuarios_fotos/'.trim($this->session->userdata('sg_user_id')).'/';
			
			// le todos os arquivos da pasta
			$link_site = site_url().'application/third_party/usuarios_fotos/'.$this->session->userdata('sg_user_id').'/';
			if(file_exists($pasta)){
		   		$diretorio = dir($pasta);
		   	}
		   	else{
		   		$diretorio = false;
		   	}
		    if(is_object($diretorio)){
			  	while($arquivo = $diretorio -> read()){
			  		if($arquivo !== '..' && $arquivo !== '.'){
				  				unlink($pasta.$arquivo);
				  		
				  	}
			   	}
			   	//print_r($imagens);
			   	//print_r($arquivos);
			   	//die();
			   	//seria legal colocar um filtro por imagens e depois colocar em um painel de imagens especifico
			   	$diretorio -> close();
		    }



			if(file_exists ( $pasta )){
				rmdir($pasta);
			}
			mkdir($pasta);
			$targ_w = $targ_h = 150;
		    $jpeg_quality = 90;
		 
		    $src = $_FILES['arquivo']['tmp_name'];
		    list($width_orig, $height_orig) = getimagesize($src);
		    $img_r = imagecreatefromjpeg($src);
		    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		    
		    if(isset($_POST['resizeW']) && isset($_POST['resizeH'])){
				$_POST['x'] *= ((float)$width_orig )/(float)$_POST['resizeW'];
				$_POST['y'] *= ((float)$height_orig )/(float)$_POST['resizeH'];
				$_POST['w'] *= ((float)$width_orig )/(float)$_POST['resizeW'];
				$_POST['h'] *= ((float)$height_orig )/(float)$_POST['resizeH'];
				
		    }
			
		    //imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
		    //$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		 	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
		    $targ_w,$targ_h,$_POST['w'],$_POST['h']);
		    
		    
			if(!imagejpeg($dst_r,$pasta.$_FILES['arquivo']['name'],$jpeg_quality) ){
				$this->session->set_flashdata('uploadFotoUsuario', array('status'=>'erro','mensagem'=>'Erro ao inserir imagem !'));
			}
			else{
				$this->session->set_flashdata('uploadFotoUsuario', array('status'=>'erro','mensagem'=>'Imagem inserida com sucesso !'));
			}
			redirect('/usuario','redirect');
			return true;
		}
	}

}

/* End of file categoria.php */
/* Location: ./application/controllers/categoria.php */