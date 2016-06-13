<?php
    function encripta($value){ 
            if(!$value){return false;}
            $text = $value;
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
            return trim($this->safe_b64encode($crypttext)); 
    }
    header ( "Content-type: image/jpeg" );
    $largura            =   200;                                        // recebe a largura
    $altura             =   20;                                         // recebe a altura
    $tamanho_fonte      =   15;                                         // recebe o tamanho da fonte
    $quantidade_letras  =   rand ( 4, 6 );                              // recebe a quantidade de letras que o captcha terá
    //$imagem =   imagecreate ( $largura, $altura );                      // define a largura e a altura da imagem
    $imagem = imagecreatefromjpeg('background.JPG');
    //imagecopyresized( $imagem, $imagem, 0, 0, 0, 0, $largura, $altura );
    $fonte  =   "arial.ttf";                                            // voce deve ter essa ou outra fonte de sua preferencia em sua pasta
    $branco =   imagecolorallocate ( $imagem, 255, 255, 255 );          // define a cor branca
    $preto  =   imagecolorallocate ( $imagem, 0, 0, 0 );                // define a cor preta
    // define a palavra conforme a quantidade de letras definidas no parametro $quantidade_letras
    $palavra=   strtolower ( substr ( str_shuffle ( "AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvYyXxWwZz23456789" ), 0, $quantidade_letras ) );
    //$_SESSION['palavra_plain']  =   $palavra;
    /*
    public  function encripta($value){ 
            if(!$value){return false;}
            $text = $value;
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
            return trim($this->safe_b64encode($crypttext)); 
    }
    */

    $session["palavra"]    =  encripta ( strtolower ( $palavra ) );          // atribui para a sessao a palavra gerada
    $this->session->set_userdata($session);
    for ( $i = 1; $i <= $quantidade_letras; $i++ )  {                   // atribui as letras a imagem
        imagettftext ( $imagem, $tamanho_fonte, rand ( -25, 25 ), ( $tamanho_fonte * $i ) - 10, $tamanho_fonte + 12, $preto, $fonte, substr ( $palavra, $i - 1, 1 ) ); 
    }
    imagejpeg ( $imagem );                                              // gera a imagem
    imagedestroy ( $imagem );                                           // limpa a imagem da memoria
?>