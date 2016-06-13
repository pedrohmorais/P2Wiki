<?php
class Searchs extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}
	public function getLinhasBusca($busca){	
		//SELECT (LENGTH(conteudos.conteudo) - LENGTH(REPLACE(conteudos.conteudo, 'pdf', ''))) / LENGTH('pdf') AS cnt, topicos.cod_topico FROM topicos JOIN conteudos ON conteudos.cod_topico = topicos.cod_topico WHERE conteudos.conteudo LIKE '%pdf%' OR topicos.descricao LIKE '%pdf%' ORDER BY cnt DESC, topicos.acessos desc 
		$todasTags = explode(' ',$busca);
		$orTags = '';
		foreach($todasTags as $key=>$value){
			$orTags .= " OR tags_topico.tag_name LIKE '%".$todasTags[$key]."%'";
			//$orTags .= " OR (select count(tag_name) from tags where tag_name like '%".$todasTags[$key]."%') > 0";
		}
		$sql = "SELECT (LENGTH(conteudos.conteudo) - LENGTH(REPLACE(conteudos.conteudo, '%".$busca."%', ''))) / LENGTH('%".$busca."%') AS cnt,topicos.cod_topico FROM topicos JOIN conteudos ON conteudos.cod_topico = topicos.cod_topico JOIN tags_topico ON tags_topico.cod_topico = topicos.cod_topico WHERE conteudos.conteudo LIKE '%".$busca."%' OR topicos.nome_topico LIKE '%".$busca."%' OR topicos.descricao LIKE '%".$busca."%'".$orTags." ORDER BY cnt DESC, topicos.acessos desc" ;
		//Não esqueça que o limite é editável !
		$sql = mysql_query($sql);
		$result = array();
		while($line = mysql_fetch_array($sql)){
		$result[] = $line;
		}
		if(count($result)>0){
			foreach($result as $key=>$value){
				//aqui tira o cnt da array
				$result[$key] = $result[$key][1];
			}
			//tira os valores repetidos
			$result = array_unique($result);
			return count($result);
		}
		else{
			return false;
		}
	}
	
	public function getTopicosBuscaPorPage($busca,$pagina,$qtdItens){	
		//SELECT (LENGTH(conteudos.conteudo) - LENGTH(REPLACE(conteudos.conteudo, 'pdf', ''))) / LENGTH('pdf') AS cnt, topicos.cod_topico FROM topicos JOIN conteudos ON conteudos.cod_topico = topicos.cod_topico WHERE conteudos.conteudo LIKE '%pdf%' OR topicos.descricao LIKE '%pdf%' ORDER BY cnt DESC, topicos.acessos desc 
		
		$todasTags = explode(' ',$busca);
		$orTags = '';
		foreach($todasTags as $key=>$value){
			$orTags .= " OR tags_topico.tag_name LIKE '%".$todasTags[$key]."%'";
			//$orTags .= " OR (select count(tag_name) from tags where tag_name like '%".$todasTags[$key]."%') > 0";
		}
		$sql = "SELECT (LENGTH(conteudos.conteudo) - LENGTH(REPLACE(conteudos.conteudo, '%".$busca."%', '')) / LENGTH('%".$busca."%')) AS cnt,topicos.cod_topico FROM topicos JOIN conteudos ON conteudos.cod_topico = topicos.cod_topico JOIN tags_topico ON tags_topico.cod_topico = topicos.cod_topico WHERE conteudos.conteudo LIKE '%".$busca."%' OR topicos.nome_topico LIKE '%".$busca."%' OR topicos.descricao LIKE '%".$busca."%'".$orTags." ORDER BY cnt DESC, topicos.acessos desc";
		//print_r($sql);
		if($pagina>0 && $qtdItens>0){
			$aPartirDe = $pagina * $qtdItens;
			$sql .= " limit ".$aPartirDe.",".($qtdItens*999);
		}
		$sql = mysql_query($sql);
		$result = array();
		while($line = mysql_fetch_array($sql)){
		$result[] = $line;
		}
		if(count($result)>0){
			foreach($result as $key=>$value){
				//aqui tira o cnt da array
				$result[$key] = $result[$key][1];
			}
			//tira os valores repetidos
			$result = array_unique($result);
			//limita a quantidade de resultados
			$resultFiltrado = array();
			$i = 0;
			foreach($result as $key=>$value){
				if($i<$qtdItens && $i<count($result)){
					$resultFiltrado[$i]=$result[$key];
					$i++;
				}	
			}

			return $resultFiltrado;
		}
		else{
			return false;
		}
	}
	/*
	public function getTopicosBusca($busca,$quantidadeItensPagina){	
		//SELECT (LENGTH(conteudos.conteudo) - LENGTH(REPLACE(conteudos.conteudo, 'pdf', ''))) / LENGTH('pdf') AS cnt, topicos.cod_topico FROM topicos JOIN conteudos ON conteudos.cod_topico = topicos.cod_topico WHERE conteudos.conteudo LIKE '%pdf%' OR topicos.descricao LIKE '%pdf%' ORDER BY cnt DESC, topicos.acessos desc 
		$todasTags = explode(' ',$busca);
		$orTags = '';
		foreach($todasTags as $key=>$value){
			$orTags .= " OR tags_topico.tag_name LIKE '%".$todasTags[$key]."%'";
			//$orTags .= " OR (select count(tag_name) from tags where tag_name like '%".$todasTags[$key]."%') > 0";
		}
		$sql = "SELECT (LENGTH(conteudos.conteudo) - LENGTH(REPLACE(conteudos.conteudo, '%".$busca."%', '')) / LENGTH('%".$busca."%')) AS cnt,topicos.cod_topico FROM topicos JOIN conteudos ON conteudos.cod_topico = topicos.cod_topico JOIN tags_topico ON tags_topico.cod_topico = topicos.cod_topico WHERE conteudos.conteudo LIKE '%".$busca."%' OR topicos.nome_topico LIKE '%".$busca."%' OR topicos.descricao LIKE '%".$busca."%'".$orTags." ORDER BY cnt DESC, topicos.acessos desc";
		//Não esqueça que o limite é editável !
		//print_r($sql);
		$sql = mysql_query($sql);
		$result = array();
		while($line = mysql_fetch_array($sql)){
		$result[] = $line;
		}
		if(count($result)>0){
			foreach($result as $key=>$value){
				//aqui tira o cnt da array
				$result[$key] = $result[$key][1];
			}
			//tira os valores repetidos
			$result = array_unique($result);
			//limita a quantidade de resultados
			$resultFiltrado = array();
			$i=0;
			foreach($result as $key=>$value){
				if($i<$quantidadeItensPagina && $i<count($result)){
					$resultFiltrado[$i]=$result[$key];
					$i++;
				}	
			}
			return $resultFiltrado;
		}
		else{
			return false;
		}
	}
	*/

	public function getInfoBusca($topicosBuscados,$busca) {
		if(count($topicosBuscados)>0 && $topicosBuscados!=''){
			foreach($topicosBuscados as $key=>$value){
				$this->db->select('nome_topico,link_topico,descricao')->where('cod_topico',$topicosBuscados[$key]);
				$query = $this->db->get('topicos');
				if($query->num_rows()>0){
					$retorno[$key]['nome_topico'] = $query->result();
					$retorno[$key]['link_topico'] = (array)$retorno[$key]['nome_topico'][0]->link_topico;
					$retorno[$key]['descricao'] = (array)$retorno[$key]['nome_topico'][0]->descricao;
					$retorno[$key]['nome_topico'] = (array)$retorno[$key]['nome_topico'][0]->nome_topico;

					$retorno[$key]['nome_topico'] = $retorno[$key]['nome_topico'][0];
					$retorno[$key]['link_topico'] = $retorno[$key]['link_topico'][0];
					$retorno[$key]['descricao']   = $retorno[$key]['descricao'][0];
				}
				else{
					return false;
				}
			}	
			return $retorno;
		}
		else{
			return false;
		}
	}
}





/* End of file searsh.php */

/* Location: ./application/models/categoria/Search.php */