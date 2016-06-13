<?php
class Contatos extends CI_Model {

	function __construct()
	{
		// Call the Model constructor

        parent::__construct();
	}
	public function inserirContato($cod_cliente,$tipo_contato,$contato,$falar_com = ''){
		$sql = 'INSERT INTO `contato`(`cod_cliente`,`tipo`,`contato`,`falar_com`)  values ("'.$cod_cliente.'","'.$tipo_contato.'","'.$contato.'","'.$falar_com.'")';
		$query = $this->db->query($sql);
		if(!$query){
			return false;
		}
		else{
			return true;
		}
	}
	public function selectContatoPorCliente($cod_cliente){
		$sql = 'SELECT * FROM contato where cod_cliente = '.$cod_cliente;
		$query = $this->db->query($sql);
		if(!$query){
			return false;
		}
		else{
			$retorno = array();
			foreach ($query->result() as $key => $value) {
				$retorno[] = (array)$value;
			}
			return $retorno;
		}
	}
}
/* End of file contatos.php */

/* Location: ./application/models/Cliente/contatos.php */