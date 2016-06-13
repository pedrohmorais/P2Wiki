<?php
class Categorias extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}
	
	public function getDataUser($user_id) {		
		
		$this->db->select('*')->where('user_id', $user_id);		
		$query = $this->db->get('usuarios');
		
		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}
		
	}
	
	public function setDataUser($dados, $user_id) {		
		
		//die(print_r($dados));
		if(!empty($dados['u_senha']) && !empty($dados['u_senha_nova'])){		
			
			$verifica = mysql_query("SELECT 1 FROM usuarios WHERE user_id = '$user_id' AND senha = PASSWORD('{$dados['u_senha']}') ");
			
			if(mysql_num_rows($verifica) < 1){
				$this->session->set_flashdata('trocousenha', "A senha digitada esta incorreta! \n Por favor, tente novamente.");
				return false;
			}
			
			if($dados['u_senha_nova'] !== $dados['u_senha_nova_repeat']){
				$this->session->set_flashdata('trocousenha', "A senhas nao conferem! \n Por favor, tente novamente.");
				return false;
			}
			
			mysql_query("UPDATE usuarios SET senha = PASSWORD('{$dados['u_senha_nova']}') WHERE user_id = '$user_id' ");
			$this->session->set_flashdata('trocousenha', "A senha foi alterada com sucesso!");
		} 
		
		
		
		$sql = "
			UPDATE usuarios SET 
				nome = '{$dados['u_nome']}',
				email = '{$dados['u_email']}'				
				WHERE user_id = '$user_id'
		";
		mysql_query($sql);
		
	}

	public function getCategoriasBase($grupos = null,$nivel_acesso = null)
	{
		$this->db->select('*');
		//$this->db->where('categoria_pai', null);
		$this->db->order_by("cod_categoria", "asc"); 
		if($nivel_acesso < 4){
			$this->db->or_where('publico','1');
			if($grupos!=null&&count($grupos)>0){

				foreach ($grupos as $key => $value) {
					$this->db->or_where('user_group',$value);
				}	
			}
		}	
		$query = $this->db->get('categorias');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}

	}

	public function getCategoriasBasePainel($grupos = null,$nivel_acesso = null)
	{
		$this->db->select('*');
		//$this->db->where('categoria_pai', null);
		$this->db->order_by("cod_categoria", "asc"); 
		if($nivel_acesso < 4){
			//$this->db->or_where('publico','1');
			if($grupos!=null&&count($grupos)>0){

				foreach ($grupos as $key => $value) {
					$this->db->or_where('user_group',$value);
				}	
			}
			else{
				$this->db->where('cod_categoria','nadaaaa');
			}
		}	
		$query = $this->db->get('categorias');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}

	}

	public function getCategoriaById($cod_categoria)
	{
		$this->db->select('*')
				 ->where('cod_categoria', $cod_categoria);
				
		$query = $this->db->get('categorias');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result[0];
		}
		else{
			return false;
		}

	}

	public function getTopicos($cod_categoria,$logado = null)
	{
		$this->db->select('*')
				 ->where('cod_categoria', $cod_categoria);
		if($logado == null){
			$this->db->where('publico', '1');
		}		 
		$this->db->order_by("ordem", "asc"); 
		
		$query = $this->db->get('topicos');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}

	}

	public function getTags($cod_categoria)
	{
		$this->db->select('*');

		$query = $this->db->get('tags');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}

	}

	public function getTagsTopico($cod_topico)
	{
		$this->db->select('*')
				 ->where('cod_topico', $cod_topico);

		$query = $this->db->get('tags_topico');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}

	}

	public function getConteudos($cod_topico)
	{
		$this->db->select('*')
				 ->where('cod_topico', $cod_topico);

		$this->db->order_by("ordem", "asc"); 
		
		$query = $this->db->get('conteudos');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result;
		}
		else{
			return false;
		}

	}

	public function getTopicoById($cod_topico)
	{
		$this->db->select('*')
				 ->where('cod_topico', $cod_topico);

			
		$query = $this->db->get('topicos');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result[0];
		}
		else{
			return false;
		}

	}

	public function getConteudoById($cod_conteudo)
	{
		$this->db->select('*')
				 ->where('cod_conteudo', $cod_conteudo);

			
		$query = $this->db->get('conteudos');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result[0];
		}
		else{
			return false;
		}

	}

	public function getCategoriaFromConteudo($cod_topico)
	{
		$this->db->select('cod_categoria')
				 ->where('cod_topico', $cod_topico);

			
		$query = $this->db->get('topicos');

		if($query->num_rows()>0){

			$result = $query->result();
			return $result[0];
		}
		else{
			return false;
		}

	}

	function getCategoriasFilhas($cod_categoria)
	{
		$this->db->select('*')
				 ->where('categoria_pai', $cod_categoria);
		
		$query = $this->db->get('categorias');

		$this->db->order_by("cod_categoria", "asc"); 

		if($query->num_rows()>0){

			$result = $query->result();
			return $result[0];
		}
		else{
			return false;
		}

	}

	function deleteTopicos($cod_topico)
	{
		
		if($this->db->delete('topicos', array('cod_topico' => $cod_topico))){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function deleteTags($tag_name,$cod_topico)
	{
		
		if($this->db->delete('tags_topico', array('tag_name' => $tag_name,'cod_topico' => $cod_topico ))){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function deleteCategoria($cod_categoria)
	{
		
		if($this->db->delete('categorias', array('cod_categoria' => $cod_categoria))){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function deleteConteudo($cod_conteudo)
	{
		
		if($this->db->delete('conteudos', array('cod_conteudo' => $cod_conteudo))){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function insertTopicos($nome_topico,$link_topico,$cod_categoria,$ordem)
	{
		//verifica ordem para ver se é nula
		if($ordem=='')
		{
			$this->db->select_max('ordem')->where('cod_categoria', $cod_categoria);
			$query = $this->db->get('topicos');
			if($query->num_rows()>0){
				$result = $query->result();
				$result = (array)$result;
				$result = $result[0]->ordem;
				$ordem = (int)$result + 1;
			}
			else{
				$ordem = 0;
			}
		}
		$data = array(
		   'nome_topico' => $nome_topico ,
		   'link_topico' => $link_topico,
		   'cod_categoria' => $cod_categoria,
		   'ordem' => $ordem,
		);
		if($this->db->insert('topicos', $data)){
			$this->db->select_max('cod_topico')->where('cod_categoria', $cod_categoria);
			$query = $this->db->get('topicos');

			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
			}
			else{
				return 'false';
			}
			
		}
		else{
			return 'false';
		}

	}

	function insertAcesso($cod_topico)
	{
		$this->db->set('acessos', 'acessos+1', FALSE);
		$this->db->where('cod_topico', $cod_topico);
		if($this->db->update('topicos')){
			return 'true';			
		}
		else{
			return 'false';
		}

	}

	function insertTags($tag_name,$cod_topico)
	{
		$this->db->select('*')->where('tag_name', $tag_name);
		
		$query = $this->db->get('tags');
		//se a tabela tag não tiver a tag_name, temos que inserir a tag name na tabela tag
		if($query->num_rows()<1)
		{
			if($this->db->insert('tags', array('tag_name' => $tag_name))){
				
			}
			else{
				return 'false';
			}
		}
		else{
			$this->db->select('*');
			$dataWhere = array('tag_name' => $tag_name,'cod_topico' => $cod_topico);
			
			$query = $this->db->get_where('tags_topico',$dataWhere);
			if($query->num_rows()>0)
			{
				return 'repetido';
			}
		}
		//Depois da verificacao inserimos a tag e o topico na tabela tags_topico
		$data = array(
		   'tag_name' => $tag_name ,
		   'cod_topico' => $cod_topico,
		);

		if($this->db->insert('tags_topico', $data)){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function insertConteudos($cod_topico,$nome_conteudo,$ordem)
	{
		$data = array(
		   'cod_topico' => $cod_topico ,
		   'nome_conteudo' => $nome_conteudo,
		   'ordem' => $ordem,
		);
		if($this->db->insert('conteudos', $data)){
			$this->db->select_max('cod_conteudo')->where('cod_topico', $cod_topico);
			$query = $this->db->get('conteudos');

			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
			}
			else{
				return 'false';
			}
			
		}
		else{
			return 'false';
		}

	}

	function insertCategorias($nome_categoria,$categoria_pai,$glyphicon,$user_group,$publico)
	{
		$data = array(
		   'nome_categoria' => $nome_categoria ,
		   'categoria_pai' => $categoria_pai,
		   'glyphicon' => $glyphicon,
		   'user_group' => $user_group,
		   'publico' => $publico
		);
		if($this->db->insert('categorias', $data)){
			$this->db->select_max('cod_categoria');
			$query = $this->db->get('categorias');

			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
			}
			else{
				return 'false';
			}
			
		}
		else{
			return 'false';
		}

	}

	private function recursiva_procura_categorias_filhas($cod_categoria_pai){
		$this->db->select('cod_categoria')->where('categoria_pai', $cod_categoria_pai);		
		$query = $this->db->get('categorias');
		
		if($query->num_rows()>0){

			$result = $query->result();
			foreach ($result as $value) {
				$this->db->query('CALL despublica_categorias_filhas_procedure("'.$cod_categoria_pai.'")');
				$this->recursiva_procura_categorias_filhas($value->cod_categoria);
			}
		}
		else{
			return true;
		}
	}

	function updateCategoria($cod_categoria,$nome_categoria,$categoria_pai,$glyphicon,$publico)
	{
		$data = array(
               'nome_categoria' => $nome_categoria,
               'categoria_pai' => $categoria_pai,
               'glyphicon' => $glyphicon,
               'publico' => $publico
            );

		$this->db->where('cod_categoria', $cod_categoria);
		

		if($this->db->update('categorias', $data)){
			if($publico == 0){
				if($this->recursiva_procura_categorias_filhas($cod_categoria)){
					return 'true';
				}
				else{
					return 'false';
				}
			}
			else{
				return 'true';
			}
		}
		else{
			return 'false';
		}

	}

	function validaCategoria($cod_categoria,$user_id,$validado){
		if($this->db->insert('valida_categoria', array('cod_categoria' => $cod_categoria,'user_id'=>$user_id,'data_validacao'=>date('Y-m-d H:i:s'),'validado'=>$validado ) ) ){
			return true;
		}
		else{
			return false;
		}
	}

	function aprovaTopico($cod_topico,$aprovado){
		$data = array(
	               'aprovado' => $aprovado,
	               'data_aprovacao' => $aprovado=='S'?date('Y-m-d H:i:s'):''
	            );

		$this->db->where('cod_topico', $cod_topico);

		if($this->db->update('topicos', $data)){
			return true;
		}
		else{
			return false;
		}
	}
	function updateTopico($cod_topico,$nome_topico,$link_topico,$cod_categoria,$ordem,$descricao,$publico)
	{
		$data = array(
               'nome_topico' => $nome_topico,
               'link_topico' => $link_topico,
               'cod_categoria' => $cod_categoria,
               'ordem' => $ordem,
               'descricao' => $descricao,
               'publico' => $publico
            );

		$this->db->where('cod_topico', $cod_topico);
		

		if($this->db->update('topicos', $data)){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function updateConteudo($cod_conteudo,$nome_conteudo,$ordem)
	{
		$data = array(
               'nome_conteudo' => $nome_conteudo,
               'ordem' => $ordem,
            );

		$this->db->where('cod_conteudo', $cod_conteudo);
		

		if($this->db->update('conteudos', $data)){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function updateConteudoCKEDITOR($cod_conteudo,$conteudo)
	{
		$data = array(
               'cod_conteudo' => $cod_conteudo,
               'conteudo' => $conteudo,
            );

		$this->db->where('cod_conteudo', $cod_conteudo);
		

		if($this->db->update('conteudos', $data)){
			return 'true';
		}
		else{
			return 'false';
		}

	}

	function getTopicsByGroup($grupos){
		$this->db->select('cod_categoria');
		//$this->db->where('categoria_pai', null);
		$this->db->order_by("cod_categoria", "asc"); 
		foreach ($grupos as $key => $value) {
				$this->db->or_where('user_group',$value);
		}	
		$query = $this->db->get('categorias');
		if($query->num_rows()>0){
			$categoriasArray = $query->result();
		}
		else{
			return null;
		}
		$categorias = array();
		foreach($categoriasArray as $k=>$v){
			$categorias[]=$v->cod_categoria;
		}

		$this->db->select('cod_topico');
		//$this->db->where('categoria_pai', null);
		$this->db->order_by("cod_topico", "asc"); 
		foreach ($categorias as $key => $value) {
				$this->db->or_where('cod_categoria',$value);
		}	
		$query = $this->db->get('topicos');

		if($query->num_rows()>0){
			$qry = $query->result();
			$result = array();
			foreach ($qry as $k=>$v){
				$result[] = $v->cod_topico;
			}
			return $result;
		}
		else{
			return null;
		}
	}

	function getCategoriesByGroup($grupos){
		$this->db->select('cod_categoria');
		//$this->db->where('categoria_pai', null);
		$this->db->order_by("cod_categoria", "asc"); 
		foreach ($grupos as $key => $value) {
				$this->db->or_where('user_group',$value);
		}	
		$query = $this->db->get('categorias');
		if($query->num_rows()>0){
			$categoriasArray = $query->result();
		}
		else{
			return null;
		}
		$categorias = array();
		foreach($categoriasArray as $k=>$v){
			$categorias[]=$v->cod_categoria;
		}
		return $categorias;
	}

	function getCategoriesValidation($cod_categoria){
		$this->db->select('*');
		//$this->db->where('categoria_pai', null);
		$this->db->order_by("data_validacao", "desc"); 
		$this->db->where('cod_categoria',$cod_categoria);
		$this->db->limit(1);
		$query = $this->db->get('valida_categoria');
		if($query->num_rows()>0){
			$categoriasArray = $query->result();
		}
		else{
			return null;
		}
		$categorias = (array)$categoriasArray[0];
		
		return $categorias;
	}
	function inserirComentario($cod_topico,$user_id,$comentario)
	{
		$data = array(
		    'cod_topico' => $cod_topico,
			'user_id' => $user_id,
			'comentario' => $comentario,
			'likes' => 0,
			'deslikes' => 0
		);
		if($this->db->insert('comentarios', $data)){
			return true;			
		}
		else{
			return false;
		}
	}
	function deleteComentario($cod_comentario,$user_id)
	{
		$data = array(
		    'cod_comentario' => $cod_comentario,
			'user_id' => $user_id
		);
		$this->db->select('cod_comentario')->where($data);		
		$query = $this->db->get('comentarios');
		
		if($query->num_rows()>0){
			if($this->db->delete('comentarios', $data)){
				return true;			
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	function listComentarios($cod_topico)
	{
		$this->db->select('*')->where('cod_topico', $cod_topico);		
		$query = $this->db->get('comentarios');
		
		if($query->num_rows()>0){

			$result = $query->result();
			$result2 = array();
			foreach ($result as $key => $value) {
				$result2[$key] = (array)$value;
			}
			return $result2;
		}
		else{
			return false;
		}
	}
	function getCountTopicos()
	{
		$this->db->select('aprovado, count( cod_topico ) as "qtd" ')->group_by('aprovado')->order_by('aprovado','asc');		
		$query = $this->db->get('topicos');
		
		if($query->num_rows()>0){

			$result = $query->result();
			$result2 = array();
			foreach ($result as $key => $value) {
				$result2[$value->aprovado] = $value->qtd;
			}
			return $result2;
		}
		else{
			return false;
		}
	}
	//SELECT aprovado,count(cod_topico) FROM `topicos` group by aprovado order by aprovado
	function getTopicosOrderByAcessos()
	{
		$this->db->select('cod_topico,nome_topico,cod_categoria,aprovado,acessos')->order_by('acessos','desc');		
		$query = $this->db->get('topicos');
		
		if($query->num_rows()>0){

			$result = $query->result();
			$result2 = array();
			foreach ($result as $key => $value) {
				$result2[$key] = (array)$value;
			}
			return $result2;
		}
		else{
			return false;
		}
	}
	function getTopicosRelacionados($tags){
		//print_r($tags);
		if(count($tags) <= 0 ){
			return false;
		}
		$cont = 0;
		$sql = "SELECT b.cod_topico,b.nome_topico,b.link_topico FROM `tags_topico` a inner join topicos b on a.cod_topico = b.cod_topico where tag_name != '' and  tag_name in (";
		//$sql = "SELECT b.cod_topico,b.nome_topico,b.link_topico FROM `tags_topico` a inner join topicos b on a.cod_topico = b.cod_topico where tag_name in (select b.tag_name from topicos a inner join tags_topico b on a.cod_topico = b.cod_topico where a.cod_topico = 263) and tag_name != '' and b.aprovado = 'S' and tag_name in (";
		foreach ($tags as $tag) {
			if(is_array($tag)){
				if($tag['tag_name'] != ''){
					$sql .= "'".$tag['tag_name']."',";
					$cont ++;
				}
			}
			else if (is_object($tag)){
				if($tag->tag_name != ''){
					$sql .= "'".$tag->tag_name."',";
					$cont ++;
				}
			}
			else{
				return false;
			}
		}
		if($cont==0){
			return false;
		}
		$sql = substr($sql,0,-1);
		$sql .= ") group by a.cod_topico order by b.aprovado desc,b.acessos desc limit 5";
		//print_r($sql);
		//$tagsLib = "";
		$query = $this->db->query($sql);
		//print_r($query->result());
		//die();
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
	}
	function addReferencia($cod_topico,$cod_conteudo){
		$data = array(
		    'cod_topico' => $cod_topico,
			'cod_conteudo' => $cod_conteudo
		);
		if($this->db->insert('topico_referencia', $data)){
			return true;			
		}
		else{
			return false;
		}
	}
	function getReferenciasByConteudo($cod_conteudo){
		$this->db->select('*')->where('cod_conteudo', $cod_conteudo);		
		$query = $this->db->get('topico_referencia');
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
	}

}





/* End of file categorias.php */

/* Location: ./application/models/categoria/categorias.php */