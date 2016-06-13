<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
	
    }
    private $menu;
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
	//Função que inicia a página wiki/conteudo
	public function index()
	{
		if($_GET['q'])
		{		
			$q = str_replace("'", '"', trim($_GET['q']));
			$data['pesquisa'] = $q;
			$this->load->model('categoria/Categorias');
			$this->load->model('usuarios/Usuarios');
			$this->load->model('search/Searchs');

			$group = $this->Usuarios->getUserGroup($this->session->userdata('sg_user_id'));
			$dados = $this->Categorias->getCategoriasBase($group,$this->session->userdata('sg_nivel_acesso'));
			$this->menu['menu']['categorias_base'] = $dados;	
			
			//$this->session->set_userdata($session);
			
					//Não esqueça de mudar o limit da query getTopicosBusca !
						//Que deve ser igual à quantidade de itens pagina !
						//Edite aqui |
						//Edite aqui |
						//Edite aqui v
			$quantidadeItensPagina = 5;
			if(isset($_GET['itens'])){
				$quantidadeItensPagina = $_GET['itens'];
			}
			$data['qtdItensPage'] = $quantidadeItensPagina;
			if(isset($_GET['page'])){
				$page = $_GET['page'];
				$data['paginaAtual'] = $page;
				$topicosBuscados = $this->Searchs->getTopicosBuscaPorPage($q,$page,$quantidadeItensPagina);
			}
			else{
				$topicosBuscados = $this->Searchs->getTopicosBuscaPorPage($q,null,$quantidadeItensPagina);
				$data['paginaAtual'] = 0;
			}
			$data['qtdRegistros'] = $this->Searchs->getLinhasBusca($q);
			$data['qtdPaginas'] = ceil($data['qtdRegistros']/$quantidadeItensPagina);

			//filtra os resultados para permissao
			$disponiveis = $this->getTopicosDisponiveis();
			if(count($topicosBuscados)>0 && is_array($topicosBuscados))
			foreach ($topicosBuscados as $key => $value) {
				if(array_key_exists($value, $disponiveis)===false){
					unset($topicosBuscados[$key]);
				}
			}

			$data['conteudos'] = $this->Searchs->getInfoBusca($topicosBuscados,$q);
		}
		else{
			$q = '';
			$data['pesquisa'] = $q;
			$this->load->model('categoria/Categorias');
			
			$dados = $this->Categorias->getCategoriasBase();
			$this->menu['menu']['categorias_base'] = $dados;
			//$this->session->set_userdata($session);
			
			$this->load->model('search/Searchs');
					//Não esqueça de mudar o limit da query getTopicosBusca !
						//Que deve ser igual à quantidade de itens pagina !
						//Edite aqui |
						//Edite aqui |
						//Edite aqui v
			$quantidadeItensPagina = 3;
			if(isset($_GET['itens'])){
				$quantidadeItensPagina = $_GET['itens'];
			}
			$data['qtdItensPage'] = $quantidadeItensPagina;
			$topicosBuscados = $this->Searchs->getTopicosBuscaPorPage($q,null,$quantidadeItensPagina);
			$data['qtdRegistros'] = $this->Searchs->getLinhasBusca($q);
			$data['qtdPaginas'] = ceil($data['qtdRegistros']/$quantidadeItensPagina);
			$data['paginaAtual'] = 0;
			if(isset($_GET['page'])){
				$page = $_GET['page'];
				$data['paginaAtual'] = $page;
				
				$topicosBuscados = $this->Searchs->getTopicosBuscaPorPage($q,$page,$quantidadeItensPagina);
			}
			$disponiveis = $this->getTopicosDisponiveis();
			foreach ($topicosBuscados as $key => $value) {
				if(array_key_exists($value, $disponiveis)===false){
					unset($topicosBuscados[$key]);
				}
			}
			$disponiveis = $this->getTopicosDisponiveis();
			//filtra os resultados para permissao
			if(count($topicosBuscados)>0 && is_array($topicosBuscados))
			foreach ($topicosBuscados as $key => $value) {
				if(array_key_exists($value, $disponiveis)===false){
					unset($topicosBuscados[$key]);
				}
			}
			$data['conteudos'] = $this->Searchs->getInfoBusca($topicosBuscados,$q);
		}
			$this->load->vars(array("title"=> "Sistema de Gestão", "view" => "main/dashboard"));
			$this->load->view('templates/main/header'); 
			if($this->session->userdata('sg_user_id'))
			{
				$this->load->view('templates/main/navbar_adm');
			}
			else
			{
				$this->load->view('templates/main/navbar');
			}
			$this->load->view('pages/main/search',$data);
			$this->load->view('templates/main/scripts'); 
			$this->load->view('templates/main/footer'); 
		
		
	}
}

/* End of file categoria.php */
/* Location: ./application/controllers/categoria.php */