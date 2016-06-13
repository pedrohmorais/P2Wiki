<?php
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
    for ( $i = 1; $i <= $quantidade_letras; $i++ )  {                   // atribui as letras a imagem
        imagettftext ( $imagem, $tamanho_fonte, rand ( -25, 25 ), ( $tamanho_fonte * $i ) - 10, $tamanho_fonte + 12, $preto, $fonte, substr ( $palavra, $i - 1, 1 ) ); 
    }
    imagejpeg ( $imagem );                                              // gera a imagem
    imagedestroy ( $imagem );                                           // limpa a imagem da memoria
?>