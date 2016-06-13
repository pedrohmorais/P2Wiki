<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Controller {
	
	public function index() {
	    
	    header ( "Content-type: image/jpeg" );
	    
	    $largura           = 200;
	    $altura            = 20;
	    $tamanho_fonte     = 15;
	    $quantidade_letras = rand ( 4, 6 );                              

	    //define a largura e a altura da imagem
	    //$imagem =   imagecreate ( $largura, $altura );
	     
	    $imagem = imagecreatefromjpeg('images/bg-captcha.jpg');
	    
	    // voce deve ter essa ou outra fonte de sua preferencia no caminho indicado
	    $fonte = "fonts/arial.ttf";

	    $branco    = imagecolorallocate ( $imagem, 255, 255, 255 );
	    $preto     = imagecolorallocate ( $imagem, 0, 0, 0 );
	    
	    // define a palavra conforme a quantidade de letras definidas no parametro $quantidade_letras	    
	    $palavra = strtolower ( substr ( str_shuffle ( "AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvYyXxWwZz23456789" ) , 0 , $quantidade_letras ) );

	    //$palavra_enc = $this->encrypt->encode ( $palavra );
	    $this->session->set_flashdata('captcha', $palavra);
	    
	    for ( $i = 1; $i <= $quantidade_letras; $i++ )  {      
	        imagettftext ( $imagem, $tamanho_fonte, rand ( -25, 25 ), ( $tamanho_fonte * $i ) - 10, $tamanho_fonte + 12, $preto, $fonte, substr ( $palavra, $i - 1, 1 ) );
	    }
	    
	    imagejpeg ( $imagem );
	    imagedestroy ( $imagem );		
	}
	public function valideCaptcha(){
		if(isset($_POST['textCaptcha']  )){
			$captcha = $this->session->flashdata('captcha');
			if(strtoupper($_POST['textCaptcha']) == strtoupper($captcha)){
				echo '<label id="respostaEnvioCaptcha">true</label>';
			}
			else{
				echo '<label id="respostaEnvioCaptcha">O texto não corresponde com a imagem.</label>';
			}
		}
	}
}

/* End of file captcha.php */
/* Location: ./application/controllers/captcha.php */