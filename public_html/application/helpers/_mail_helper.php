<?php

class Email {
    
    private $_cartao;
    
    private $_sender;
    
    private $_layout;

    private $_templates_email;
    
    private $_pedido;
    
    public function __construct ( ) {
        
        $this->_cartao  =   new Cartao ( );
        $this->_sender  =   new PHPMailer( );
        $this->_layout  =   new Layout ( );
        $this->_templates_email  =   new Templates_email( );
        $this->_pedido  =   new Pedido ( );
        
    }

    //public function aa(){
     //   print_r($this->_templates_email->getTemplatesByTipoCartao ( '31' ));
    //}

    public function solicitarFaltantes ( $faltantes )   {
        $produtos       =   array_keys ( $faltantes );
        $html   =   '<font size="5">Cartões solicitados </font><br><br>';
        foreach ( $produtos as  $produto )  {
            $nomeCartao =   $this->_cartao->buscarNomeTipo ( $produto );
            $html       .=  '<br>Tipo de Cartão: ' . $nomeCartao . ' (' . $produto . ').<br>Quantidade: ' . $faltantes[$produto] . '<br><br>'; 
        }
        /*
        $to             =   'rudney@dshop.com.br';
        $nome           =   'Rudney';
        $assunto        =   utf8_encode ( 'Falta de Cartões Virtuais' );
        $confirmacao    =   '';
        $sender         =   'rudney@dshop.com.br';
        $userName       =   'rudney@dshop.com.br';
        $from           =   'rudney@dshop.com.br';
        $replyTo        =   'rudney@dshop.com.br';
        $fromName       =   'Robot Buscar Liberados';
        $password       =   'rud@dshop@ney';
        */
        echo '<br><br>Faltantes: ' . $html . '<br><br>';
        $emails_array = array('juliana@dshop.com.br');
        $template_html = 'teste_02';
        $nm_subject = 'Falta de Cartões Virtuais allin';
        $nm_remetente = 'Robot Falta de Cartões Virtuais';
        $email_remetente = 'log@dshopdesenv.com';
        $nm_reply = 'log@dshopdesenv.com';
        $campos = 'mensagem';
        $valor = $html;
        $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);
        /*
        $this->enviarEmail ( $to, $nome, $assunto, utf8_encode ( $html ), $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        $this->enviarEmail ( 'juliana@dshop.com.br', 'Juliana', $assunto, utf8_encode ( $html ), $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        $this->enviarEmail ( 'diego@dshop.com.br', 'Juliana', $assunto, utf8_encode ( $html ), $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        */
    }

    public function montarEmail_All_In ( $dados )   {

        $envioEmail         =   $this->_pedido->buscarInfoEnvio ( $dados );
        
        $assunto            =   $envioEmail[  'assunto'  ];
        $sender             =   $envioEmail[  'username' ];
        $userName           =   $envioEmail[  'username' ];
        $from               =   $envioEmail[  'replyTo'  ];
        $replyTo            =   $envioEmail[  'replyTo'  ];
        $fromName           =   $envioEmail[  'fromName' ];
        $confirmacao        =   $envioEmail['confirmacao'];
        $password           =   $envioEmail[   'senha'   ];
        $dominio            =   $envioEmail[ 'urlCartao' ] . '?';
        
        $idPedidos          =   utf8_encode ( $dados['idVirtualPedidos'] );
        
        $nome               =   utf8_encode ( $dados[      'nome'      ] );
        $pedido             =   utf8_encode ( $dados[     'pedido'     ] );
        $nomeComprador      =   utf8_encode ( $dados[  'nomeComprador' ] );
        $emailComprador     =   utf8_encode ( $dados[ 'emailComprador' ] );
        $nomePresenteado    =   utf8_encode ( $dados[ 'nomePresenteado'] );
        $emailPresenteado   =   utf8_encode ( $dados['emailPresenteado'] );
        $titulo             =   utf8_encode ( $dados[     'titulo'     ] );
        $mensagem           =   utf8_encode ( $dados[    'mensagem'    ] );
        $formaEnvio         =   utf8_encode ( $dados[   'formaEnvio'   ] );
        $tipoCartao         =   utf8_encode ( $dados[   'tipoCartao'   ] );
        $codigoCartao       =   utf8_encode ( $dados[  'codigoCartao'  ] );
        $valor              =   utf8_encode ( $dados[      'valor'     ] );
        
        if ( trim ( $nomeComprador )    == '' && trim ( $nomePresenteado )  != '' ) $nomeComprador      =   $nomePresenteado;
        if ( trim ( $nomePresenteado )  == '' && trim ( $nomeComprador )    != '' ) $nomePresenteado    =   $nomeComprador;
        if ( trim ( $emailComprador )   == '' && trim ( $emailPresenteado ) != '' ) $emailComprador     =   $emailPresenteado;
        if ( trim ( $emailPresenteado ) == '' && trim ( $emailComprador )   != '' ) $emailPresenteado   =   $emailComprador;
        
        $codigo             =   md5 ( $idPedidos )  .   md5 ( $pedido ) . md5 ( $codigoCartao );
        
        $link               =   $dominio    .   $codigo;

        $html               =   $this->_layout->buscarLayoutEmail ( '31' );
        $html               =   str_replace ( '__IDVIRTUAL__',          $idPedidos,         $html );
        $html               =   str_replace ( '__ATENDIMENTO__',        $pedido,            $html );
        $html               =   str_replace ( '__NOMEPRESENTEADO__',    $nomePresenteado,   $html );
        $html               =   str_replace ( '__EMAILPRESENTEADO__',   $emailPresenteado,  $html );
        
        if ( ( trim ( $emailComprador ) != '' && trim ( $nomeComprador ) != '' ) && ( trim ( $nomeComprador ) != trim ( $nomePresenteado ) ) )   {
            $html           =   str_replace ( '__INICIOCOMPRADOR__',    '',                 $html );
            $html           =   str_replace ( '__FINALCOMPRADOR__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOCOMPRADOR__' );
            $final          =   strpos ( $html, '__FINALCOMPRADOR__' ) + 18;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        if ( trim ( $mensagem ) != '' || trim ( $titulo ) != '' )   {
            $html           =   str_replace ( '__INICIOMENSAGEM__',    '',                 $html );
            $html           =   str_replace ( '__FINALMENSAGEM__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOMENSAGEM__' );
            $final          =   strpos ( $html, '__FINALMENSAGEM__' ) + 17;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        $html               =   str_replace ( '__NOMECOMPRADOR__',      $nomeComprador,     $html );
        $html               =   str_replace ( '__EMAILCOMPRADOR__',     $emailComprador,    $html );
        
        
        $html               =   str_replace ( '__TITULO__',             $titulo,            $html );
        $html               =   str_replace ( '__MENSAGEM__',           $mensagem,          $html );
        $html               =   str_replace ( '__CODIGOCARTAO__',       $codigoCartao,      $html );
        $html               =   str_replace ( '__VALOR__',              $valor,             $html );
        $html               =   str_replace ( '__LINK__',               $link,              $html );
        
        $linkAjuda          =   $dominio    .   'ajuda&'    .   $codigo;
        $html               =   str_replace ( '__LINKAJUDA__',         $linkAjuda,         $html );
        
        ///IMAGENS DINAMICAS NO TOKPRESENTE///
        if($tipoCartao == 65) {
        
            $nome_imagem        = trim($dados['imgCartao']);
        
            if(!$nome_imagem) {
                $nome_imagem = 'email-img-tokstok-01.jpg';
            }
        
            $html           =   str_replace ( '[:nome_imagem:]',         $nome_imagem,         $html );
        }
        //////////////////////////////////////
        /*
        $t_array   =  array('CARTAOPRESENTE_CESTASMICHELLI_cestasmichelli_mod_01_14092301');    
        //$t_array[] = 'CARTAOPRESENTE_NETSHOES_cea_mod_02';  erro crip
        $t_array[] = 'CARTAOPRESENTE_FNAC_fnac_mod_01_14091801';                        
        $t_array[] = 'CARTAOPRESENTE_TOKSTOK_tokstok_mod_01';   
        //$t_array[] = 'CARTAOPRESENTE_CEA_layout_cea_troca';     
        $t_array[] = 'CARTAOPRESENTE_CEA_layout_cea';   
        $t_array[] = 'CARTAOPRESENTE_FLOT_flot_email_mod01_14072501';    
        $t_array[] = 'CARTAOPRESENTE_SARAIVA_saraiva_mod_01_140905';    
        $t_array[] = 'CARTAOPRESENTE_GIULIANAFLORES_giulianaflores_mod_01_14092301';    
        $t_array[] = 'CARTAOPRESENTE_AMERICANAS_americanas_mod_01_15012301';            
        $t_array[] = 'CARTAOPRESENTE_SHOPTIME_shoptime_mod_01_15012301';                
        $t_array[] = 'CARTAOPRESENTE_SUBMARINO_submarino_mod_01_15012301';              
        $t_array[] = 'CARTAOPRESENTE_HOTELURBANO_hotelurbano_mod_01_14091801';          
        $t_array[] = 'CARTAOPRESENTE_NETSHOES_netshoes_mod_01_140211'; 
        $t_array[] = 'CARTAOPRESENTE_WALLMART_walmart_email_mod01_14072801';    
        */
        //$t_array = array('CARTAOPRESENTE_HOTELURBANO_hotelurbano_mod_01_14091801');
        //foreach($t_array as $t_a){
            $email_a_enviar = $formaEnvio == 2 ? $emailComprador : $emailPresenteado;

            $dados_template = $this->_templates_email->getTemplatesByTipoCartao($tipoCartao);
            $emails_array = array($email_a_enviar);
            //$emails_array = array($email_a_enviar,'log@dshopdesenv.com');

            //$emails_array = $emailPresenteado;
            $template_html = $dados_template[0]['nome_template'];
            $nm_subject = $assunto;
            $nm_remetente = $fromName;
            $email_remetente = 'virtual@giftty.com.br';
            $nm_reply = 'virtual@giftty.com.br';
            $campos = 'IDVIRTUAL,ATENDIMENTO,NOMEPRESENTEADO,EMAILPRESENTEADO,NOMECOMPRADOR,EMAILCOMPRADOR,TITULO,MENSAGEM,CODIGOCARTAO,VALOR,LINK,LINKAJUDA';
            $valor =  $idPedidos.','.$pedido.','.strip_tags($nomePresenteado).','.$emailPresenteado.','.strip_tags($nomeComprador).','.$emailComprador.','.strip_tags($titulo).','.strip_tags($mensagem).','.$codigoCartao.','.$valor.','.$link.','.$linkAjuda;
            
            $enviado = $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);
        //}
            
        if ( $enviado ) {
            $this->_pedido->marcarEnviado ( $idPedidos, $pedido, $codigoCartao );
            return true;
        }
        
    }

    public function montarEmail_All_In_CEA ( $dados )   {

        $envioEmail         =   $this->_pedido->buscarInfoEnvio ( $dados );
        
        $assunto            =   $envioEmail[  'assunto'  ];
        $sender             =   $envioEmail[  'username' ];
        $userName           =   $envioEmail[  'username' ];
        $from               =   $envioEmail[  'replyTo'  ];
        $replyTo            =   $envioEmail[  'replyTo'  ];
        $fromName           =   $envioEmail[  'fromName' ];
        $confirmacao        =   $envioEmail['confirmacao'];
        $password           =   $envioEmail[   'senha'   ];
        $dominio            =   $envioEmail[ 'urlCartao' ] . '?';
        
        $idPedidos          =   utf8_encode ( $dados['idVirtualPedidos'] );
        
        $nome               =   utf8_encode ( $dados[      'nome'      ] );
        $pedido             =   utf8_encode ( $dados[     'pedido'     ] );
        $nomeComprador      =   utf8_encode ( $dados[  'nomeComprador' ] );
        $emailComprador     =   utf8_encode ( $dados[ 'emailComprador' ] );
        $nomePresenteado    =   utf8_encode ( $dados[ 'nomePresenteado'] );
        $emailPresenteado   =   utf8_encode ( $dados['emailPresenteado'] );
        $titulo             =   utf8_encode ( $dados[     'titulo'     ] );
        $mensagem           =   utf8_encode ( $dados[    'mensagem'    ] );
        $formaEnvio         =   utf8_encode ( $dados[   'formaEnvio'   ] );
        $tipoCartao         =   utf8_encode ( $dados[   'tipoCartao'   ] );
        $codigoCartao       =   utf8_encode ( $dados[  'codigoCartao'  ] );
        $valor              =   utf8_encode ( $dados[      'valor'     ] );
        
        if ( trim ( $nomeComprador )    == '' && trim ( $nomePresenteado )  != '' ) $nomeComprador      =   $nomePresenteado;
        if ( trim ( $nomePresenteado )  == '' && trim ( $nomeComprador )    != '' ) $nomePresenteado    =   $nomeComprador;
        if ( trim ( $emailComprador )   == '' && trim ( $emailPresenteado ) != '' ) $emailComprador     =   $emailPresenteado;
        if ( trim ( $emailPresenteado ) == '' && trim ( $emailComprador )   != '' ) $emailPresenteado   =   $emailComprador;
        
        $codigo             =   md5 ( $idPedidos )  .   md5 ( $pedido ) . md5 ( $codigoCartao );
        
        $link               =   $dominio    .   $codigo;

        $html               =   $this->_layout->buscarLayoutEmail ( '31' );
        $html               =   str_replace ( '__IDVIRTUAL__',          $idPedidos,         $html );
        $html               =   str_replace ( '__ATENDIMENTO__',        $pedido,            $html );
        $html               =   str_replace ( '__NOMEPRESENTEADO__',    $nomePresenteado,   $html );
        $html               =   str_replace ( '__EMAILPRESENTEADO__',   $emailPresenteado,  $html );
        
        if ( ( trim ( $emailComprador ) != '' && trim ( $nomeComprador ) != '' ) && ( trim ( $nomeComprador ) != trim ( $nomePresenteado ) ) )   {
            $html           =   str_replace ( '__INICIOCOMPRADOR__',    '',                 $html );
            $html           =   str_replace ( '__FINALCOMPRADOR__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOCOMPRADOR__' );
            $final          =   strpos ( $html, '__FINALCOMPRADOR__' ) + 18;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        if ( trim ( $mensagem ) != '' || trim ( $titulo ) != '' )   {
            $html           =   str_replace ( '__INICIOMENSAGEM__',    '',                 $html );
            $html           =   str_replace ( '__FINALMENSAGEM__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOMENSAGEM__' );
            $final          =   strpos ( $html, '__FINALMENSAGEM__' ) + 17;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        $html               =   str_replace ( '__NOMECOMPRADOR__',      $nomeComprador,     $html );
        $html               =   str_replace ( '__EMAILCOMPRADOR__',     $emailComprador,    $html );
        
        
        $html               =   str_replace ( '__TITULO__',             $titulo,            $html );
        $html               =   str_replace ( '__MENSAGEM__',           $mensagem,          $html );
        $html               =   str_replace ( '__CODIGOCARTAO__',       $codigoCartao,      $html );
        $html               =   str_replace ( '__VALOR__',              $valor,             $html );
        $html               =   str_replace ( '__LINK__',               $link,              $html );
        
        $linkAjuda          =   $dominio    .   'ajuda&'    .   $codigo;
        $html               =   str_replace ( '__LINKAJUDA__',         $linkAjuda,         $html );
        
        ///IMAGENS DINAMICAS NO TOKPRESENTE///
        if($tipoCartao == 65) {
        
            $nome_imagem        = trim($dados['imgCartao']);
        
            if(!$nome_imagem) {
                $nome_imagem = 'email-img-tokstok-01.jpg';
            }
        
            $html           =   str_replace ( '[:nome_imagem:]',         $nome_imagem,         $html );
        }
        //////////////////////////////////////
        /*
        $t_array   =  array('CARTAOPRESENTE_CESTASMICHELLI_cestasmichelli_mod_01_14092301');    
        //$t_array[] = 'CARTAOPRESENTE_NETSHOES_cea_mod_02';  erro crip
        $t_array[] = 'CARTAOPRESENTE_FNAC_fnac_mod_01_14091801';                        
        $t_array[] = 'CARTAOPRESENTE_TOKSTOK_tokstok_mod_01';   
        //$t_array[] = 'CARTAOPRESENTE_CEA_layout_cea_troca';     
        $t_array[] = 'CARTAOPRESENTE_CEA_layout_cea';   
        $t_array[] = 'CARTAOPRESENTE_FLOT_flot_email_mod01_14072501';    
        $t_array[] = 'CARTAOPRESENTE_SARAIVA_saraiva_mod_01_140905';    
        $t_array[] = 'CARTAOPRESENTE_GIULIANAFLORES_giulianaflores_mod_01_14092301';    
        $t_array[] = 'CARTAOPRESENTE_AMERICANAS_americanas_mod_01_15012301';            
        $t_array[] = 'CARTAOPRESENTE_SHOPTIME_shoptime_mod_01_15012301';                
        $t_array[] = 'CARTAOPRESENTE_SUBMARINO_submarino_mod_01_15012301';              
        $t_array[] = 'CARTAOPRESENTE_HOTELURBANO_hotelurbano_mod_01_14091801';          
        $t_array[] = 'CARTAOPRESENTE_NETSHOES_netshoes_mod_01_140211'; 
        $t_array[] = 'CARTAOPRESENTE_WALLMART_walmart_email_mod01_14072801';    
        */
        //$t_array = array('CARTAOPRESENTE_HOTELURBANO_hotelurbano_mod_01_14091801');
        //foreach($t_array as $t_a){
            $email_a_enviar = $formaEnvio == 2 ? $emailComprador : $emailPresenteado;

            $dados_template = $this->_templates_email->getTemplatesByTipoCartao($tipoCartao);
            //$emails_array = array($email_a_enviar,'daniel@dshop.com.br','pedro.morais@dshop.com.br','william@dshop.com.br');
            $emails_array = array($email_a_enviar);
            //$emails_array = array($email_a_enviar,'log@dshopdesenv.com');

            //$emails_array = $emailPresenteado;
            $template_html = $dados_template[0]['nome_template'];
            $nm_subject = $assunto;
            $nm_remetente = $fromName;
            $email_remetente = 'cartao@virtualpresentecea.com.br';
            $nm_reply = 'cartao@virtualpresentecea.com.br';
            $campos = 'IDVIRTUAL,ATENDIMENTO,NOMEPRESENTEADO,EMAILPRESENTEADO,NOMECOMPRADOR,EMAILCOMPRADOR,TITULO,MENSAGEM,CODIGOCARTAO,VALOR,LINK,LINKAJUDA';
            $valor =  $idPedidos.','.$pedido.','.strip_tags($nomePresenteado).','.$emailPresenteado.','.strip_tags($nomeComprador).','.$emailComprador.','.strip_tags($titulo).','.strip_tags($mensagem).','.$codigoCartao.','.$valor.','.$link.','.$linkAjuda;
            
            $enviado = $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);
        //}
            
        if ( $enviado ) {
            $this->_pedido->marcarEnviado ( $idPedidos, $pedido, $codigoCartao );
            return true;
        }
        
    }

    public function montarEmail_All_In_testando ( $dados )   {

        $envioEmail         =   $this->_pedido->buscarInfoEnvio ( $dados );
        
        $assunto            =   $envioEmail[  'assunto'  ];
        $sender             =   $envioEmail[  'username' ];
        $userName           =   $envioEmail[  'username' ];
        $from               =   $envioEmail[  'replyTo'  ];
        $replyTo            =   $envioEmail[  'replyTo'  ];
        $fromName           =   $envioEmail[  'fromName' ];
        $confirmacao        =   $envioEmail['confirmacao'];
        $password           =   $envioEmail[   'senha'   ];
        $dominio            =   $envioEmail[ 'urlCartao' ] . '?';
        
        $idPedidos          =   utf8_encode ( $dados['idVirtualPedidos'] );
        
        $nome               =   utf8_encode ( $dados[      'nome'      ] );
        $pedido             =   utf8_encode ( $dados[     'pedido'     ] );
        $nomeComprador      =   utf8_encode ( $dados[  'nomeComprador' ] );
        $emailComprador     =   utf8_encode ( $dados[ 'emailComprador' ] );
        $nomePresenteado    =   utf8_encode ( $dados[ 'nomePresenteado'] );
        $emailPresenteado   =   utf8_encode ( $dados['emailPresenteado'] );
        $titulo             =   utf8_encode ( $dados[     'titulo'     ] );
        $mensagem           =   utf8_encode ( $dados[    'mensagem'    ] );
        $formaEnvio         =   utf8_encode ( $dados[   'formaEnvio'   ] );
        $tipoCartao         =   utf8_encode ( $dados[   'tipoCartao'   ] );
        $codigoCartao       =   utf8_encode ( $dados[  'codigoCartao'  ] );
        $valor              =   utf8_encode ( $dados[      'valor'     ] );
        
        if ( trim ( $nomeComprador )    == '' && trim ( $nomePresenteado )  != '' ) $nomeComprador      =   $nomePresenteado;
        if ( trim ( $nomePresenteado )  == '' && trim ( $nomeComprador )    != '' ) $nomePresenteado    =   $nomeComprador;
        if ( trim ( $emailComprador )   == '' && trim ( $emailPresenteado ) != '' ) $emailComprador     =   $emailPresenteado;
        if ( trim ( $emailPresenteado ) == '' && trim ( $emailComprador )   != '' ) $emailPresenteado   =   $emailComprador;
        
        $codigo             =   md5 ( $idPedidos )  .   md5 ( $pedido ) . md5 ( $codigoCartao );
        
        $link               =   $dominio    .   $codigo;

        $html               =   $this->_layout->buscarLayoutEmail ( '31' );
        $html               =   str_replace ( '__IDVIRTUAL__',          $idPedidos,         $html );
        $html               =   str_replace ( '__ATENDIMENTO__',        $pedido,            $html );
        $html               =   str_replace ( '__NOMEPRESENTEADO__',    $nomePresenteado,   $html );
        $html               =   str_replace ( '__EMAILPRESENTEADO__',   $emailPresenteado,  $html );
        
        if ( ( trim ( $emailComprador ) != '' && trim ( $nomeComprador ) != '' ) && ( trim ( $nomeComprador ) != trim ( $nomePresenteado ) ) )   {
            $html           =   str_replace ( '__INICIOCOMPRADOR__',    '',                 $html );
            $html           =   str_replace ( '__FINALCOMPRADOR__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOCOMPRADOR__' );
            $final          =   strpos ( $html, '__FINALCOMPRADOR__' ) + 18;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        if ( trim ( $mensagem ) != '' || trim ( $titulo ) != '' )   {
            $html           =   str_replace ( '__INICIOMENSAGEM__',    '',                 $html );
            $html           =   str_replace ( '__FINALMENSAGEM__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOMENSAGEM__' );
            $final          =   strpos ( $html, '__FINALMENSAGEM__' ) + 17;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        $html               =   str_replace ( '__NOMECOMPRADOR__',      $nomeComprador,     $html );
        $html               =   str_replace ( '__EMAILCOMPRADOR__',     $emailComprador,    $html );
        
        
        $html               =   str_replace ( '__TITULO__',             $titulo,            $html );
        $html               =   str_replace ( '__MENSAGEM__',           $mensagem,          $html );
        $html               =   str_replace ( '__CODIGOCARTAO__',       $codigoCartao,      $html );
        $html               =   str_replace ( '__VALOR__',              $valor,             $html );
        $html               =   str_replace ( '__LINK__',               $link,              $html );
        
        $linkAjuda          =   $dominio    .   'ajuda&'    .   $codigo;
        $html               =   str_replace ( '__LINKAJUDA__',         $linkAjuda,         $html );
        
        ///IMAGENS DINAMICAS NO TOKPRESENTE///
        if($tipoCartao == 65) {
        
            $nome_imagem        = trim($dados['imgCartao']);
        
            if(!$nome_imagem) {
                $nome_imagem = 'email-img-tokstok-01.jpg';
            }
        
            $html           =   str_replace ( '[:nome_imagem:]',         $nome_imagem,         $html );
        }
        //////////////////////////////////////
        /*
        $t_array   =  array('CARTAOPRESENTE_CESTASMICHELLI_cestasmichelli_mod_01_14092301');    
        //$t_array[] = 'CARTAOPRESENTE_NETSHOES_cea_mod_02';  erro crip
        $t_array[] = 'CARTAOPRESENTE_FNAC_fnac_mod_01_14091801';                        
        $t_array[] = 'CARTAOPRESENTE_TOKSTOK_tokstok_mod_01';   
        //$t_array[] = 'CARTAOPRESENTE_CEA_layout_cea_troca';     
        $t_array[] = 'CARTAOPRESENTE_CEA_layout_cea';   
        $t_array[] = 'CARTAOPRESENTE_FLOT_flot_email_mod01_14072501';    
        $t_array[] = 'CARTAOPRESENTE_SARAIVA_saraiva_mod_01_140905';    
        $t_array[] = 'CARTAOPRESENTE_GIULIANAFLORES_giulianaflores_mod_01_14092301';    
        $t_array[] = 'CARTAOPRESENTE_AMERICANAS_americanas_mod_01_15012301';            
        $t_array[] = 'CARTAOPRESENTE_SHOPTIME_shoptime_mod_01_15012301';                
        $t_array[] = 'CARTAOPRESENTE_SUBMARINO_submarino_mod_01_15012301';              
        $t_array[] = 'CARTAOPRESENTE_HOTELURBANO_hotelurbano_mod_01_14091801';          
        $t_array[] = 'CARTAOPRESENTE_NETSHOES_netshoes_mod_01_140211'; 
        $t_array[] = 'CARTAOPRESENTE_WALLMART_walmart_email_mod01_14072801';    
        */
        //$t_array = array('CARTAOPRESENTE_HOTELURBANO_hotelurbano_mod_01_14091801');
        //foreach($t_array as $t_a){
            $email_a_enviar = $formaEnvio == 2 ? $emailComprador : $emailPresenteado;

            if($tipoCartao == 31){
                $emails_array = array('pedro.morais@dshop.com.br','william@dshop.com.br','daniel@dshop.com.br');
            }
            else{
                $emails_array = array($email_a_enviar);
            }
            $dados_template = $this->_templates_email->getTemplatesByTipoCartao($tipoCartao);
            //$emails_array = array($email_a_enviar,'log@dshopdesenv.com');

            //$emails_array = $emailPresenteado;
            if($tipoCartao == 31){
                $template_html = 'CARTAOPRESENTE_CEA_cea_email_15073101';
            }
            else{
                $template_html = $dados_template[0]['nome_template'];
            }
            $nm_subject = $assunto;
            $nm_remetente = $fromName;
            $email_remetente = 'cartao@virtualpresentecea.com.br';
            $nm_reply = 'cartao@virtualpresentecea.com.br';
            $campos = 'IDVIRTUAL,ATENDIMENTO,NOMEPRESENTEADO,EMAILPRESENTEADO,NOMECOMPRADOR,EMAILCOMPRADOR,TITULO,MENSAGEM,CODIGOCARTAO,VALOR,LINK,LINKAJUDA';
            $valor =  $idPedidos.','.$pedido.','.strip_tags($nomePresenteado).','.$emailPresenteado.','.strip_tags($nomeComprador).','.$emailComprador.','.strip_tags($titulo).','.strip_tags($mensagem).','.$codigoCartao.','.$valor.','.$link.','.$linkAjuda;
            
            $enviado = $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);
        //}
            
        if ( $enviado ) {
            //$this->_pedido->marcarEnviado ( $idPedidos, $pedido, $codigoCartao );
            return true;
        }
        
    }
    
    public function montarEmail ( $dados )   {
        
        $envioEmail         =   $this->_pedido->buscarInfoEnvio ( $dados );
        
        $assunto            =   $envioEmail[  'assunto'  ];
        $sender             =   $envioEmail[  'username' ];
        $userName           =   $envioEmail[  'username' ];
        $from               =   $envioEmail[  'replyTo' ];
        $replyTo            =   $envioEmail[  'replyTo'  ];
        $fromName           =   $envioEmail[  'fromName' ];
        $confirmacao        =   $envioEmail['confirmacao'];
        $password           =   $envioEmail[   'senha'   ];
        $dominio            =   $envioEmail[ 'urlCartao' ] . '?';
        
        $idPedidos          =   utf8_encode ( $dados['idVirtualPedidos'] );
        $nome               =   utf8_encode ( $dados[      'nome'      ] );
        $pedido             =   utf8_encode ( $dados[     'pedido'     ] );
        $nomeComprador      =   utf8_encode ( $dados[  'nomeComprador' ] );
        $emailComprador     =   utf8_encode ( $dados[ 'emailComprador' ] );
        $nomePresenteado    =   utf8_encode ( $dados[ 'nomePresenteado'] );
        $emailPresenteado   =   utf8_encode ( $dados['emailPresenteado'] );
        $titulo             =   utf8_encode ( $dados[     'titulo'     ] );
        $mensagem           =   utf8_encode ( $dados[    'mensagem'    ] );
        $formaEnvio         =   utf8_encode ( $dados[   'formaEnvio'   ] );
        $tipoCartao         =   utf8_encode ( $dados[   'tipoCartao'   ] );
        $codigoCartao       =   utf8_encode ( $dados[  'codigoCartao'  ] );
        $valor              =   utf8_encode ( $dados[      'valor'     ] );
        
        if ( trim ( $nomeComprador )    == '' && trim ( $nomePresenteado )  != '' ) $nomeComprador      =   $nomePresenteado;
        if ( trim ( $nomePresenteado )  == '' && trim ( $nomeComprador )    != '' ) $nomePresenteado    =   $nomeComprador;
        if ( trim ( $emailComprador )   == '' && trim ( $emailPresenteado ) != '' ) $emailComprador     =   $emailPresenteado;
        if ( trim ( $emailPresenteado ) == '' && trim ( $emailComprador )   != '' ) $emailPresenteado   =   $emailComprador;
        
        $codigo             =   md5 ( $idPedidos )  .   md5 ( $pedido ) . md5 ( $codigoCartao );
        
        $link               =   $dominio    .   $codigo;

        $html               =   $this->_layout->buscarLayoutEmail ( $tipoCartao );
        $html               =   str_replace ( '__IDVIRTUAL__',          $idPedidos,         $html );
        $html               =   str_replace ( '__ATENDIMENTO__',        $pedido,            $html );
        $html               =   str_replace ( '__NOMEPRESENTEADO__',    $nomePresenteado,   $html );
        $html               =   str_replace ( '__EMAILPRESENTEADO__',   $emailPresenteado,  $html );
        
        if ( ( trim ( $emailComprador ) != '' && trim ( $nomeComprador ) != '' ) && ( trim ( $nomeComprador ) != trim ( $nomePresenteado ) ) )   {
            $html           =   str_replace ( '__INICIOCOMPRADOR__',    '',                 $html );
            $html           =   str_replace ( '__FINALCOMPRADOR__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOCOMPRADOR__' );
            $final          =   strpos ( $html, '__FINALCOMPRADOR__' ) + 18;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        if ( trim ( $mensagem ) != '' || trim ( $titulo ) != '' )   {
            $html           =   str_replace ( '__INICIOMENSAGEM__',    '',                 $html );
            $html           =   str_replace ( '__FINALMENSAGEM__',     '',                 $html );
        }
        else    {
            $inicio         =   strpos ( $html, '__INICIOMENSAGEM__' );
            $final          =   strpos ( $html, '__FINALMENSAGEM__' ) + 17;
            $html           =   substr ( $html, 0, $inicio ) . ' ' . substr ( $html, $final );
        }
        
        $html               =   str_replace ( '__NOMECOMPRADOR__',      $nomeComprador,     $html );
        $html               =   str_replace ( '__EMAILCOMPRADOR__',     $emailComprador,    $html );
        
        
        $html               =   str_replace ( '__TITULO__',             $titulo,            $html );
        $html               =   str_replace ( '__MENSAGEM__',           $mensagem,          $html );
        $html               =   str_replace ( '__CODIGOCARTAO__',       $codigoCartao,      $html );
        $html               =   str_replace ( '__VALOR__',              $valor,             $html );
        $html               =   str_replace ( '__LINK__',               $link,              $html );
    
    $linkAjuda          =   $dominio    .   'ajuda&'    .   $codigo;
        $html               =   str_replace ( '__LINKAJUDA__',         $linkAjuda,         $html );
        
        ///IMAGENS DINAMICAS NO TOKPRESENTE///
        if($tipoCartao == 65) {
        
            $nome_imagem        = trim($dados['imgCartao']);
        
            if(!$nome_imagem) {
                $nome_imagem = 'email-img-tokstok-01.jpg';
            }
        
            $html           =   str_replace ( '[:nome_imagem:]',         $nome_imagem,         $html );
        }
        //////////////////////////////////////
        
        switch ( $formaEnvio )  {
            case '1':   //  Presenteado
                $enviado    =   $this->enviarEmail ( $emailPresenteado, $nomePresenteado,   $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                $enviado    =   $this->enviarEmail ( 'rudney@dshop.com.br', $nomePresenteado,   'copia - ' . $emailPresenteado, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                break;
            
            case '2':   //  Comprador
                $enviado    =   $this->enviarEmail ( $emailComprador,   $nomeComprador,     $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                $enviado    =   $this->enviarEmail ( 'rudney@dshop.com.br', $nomePresenteado,   'copia - ' . $emailComprador, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                break;
            
            case '3':   //  Ambos
                $enviado    =   $this->enviarEmail ( $emailComprador,   $nomeComprador,     $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                $enviado    =   $this->enviarEmail ( 'rudney@dshop.com.br', $nomePresenteado,   'copia - ' . $emailComprador, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                if ( $enviado )
                    $enviado=   $this->enviarEmail ( $emailPresenteado, $nomePresenteado,   $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                    $enviado    =   $this->enviarEmail ( 'rudney@dshop.com.br', $nomePresenteado,   'copia - ' . $emailPresenteado, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
                break;
        }
        
        if ( $enviado ) {
            $this->_pedido->marcarEnviado ( $idPedidos, $pedido, $codigoCartao );
            return true;
        }
    }
    public function enviarDadosSms ( $sms ) {
        $corpo = ' <body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">
                        <h1>SMS liberados</h1>
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="left" >';
        
        $corpo              .= "<tr><td>Pedido: "               .   $sms['pedido']          .   "</td></tr>
                                <tr><td>Valor: "                .   $sms['valor']           .   "</td></tr>
                                <tr><td>Nome Presenteado: "     .   $sms['nomePresenteado'] .   "</td></tr>
                                <tr><td>Nome Comprador: "       .   $sms['nomeComprador']   .   "</td></tr>
                                <tr><td>Celular Presenteado: "  .   $sms['emailPresenteado'].   "</td></tr>
                                <tr><td>Celular Comprador: "    .   $sms['emailComprador']  .   "</td></tr>
                                <tr><td>Cartao: "               .   $sms['codigoCartao']    .   "</td></tr>
                                <tr><td>Mensagem: "             .   $sms['mensagem']        .   "</td></tr>
                                <tr>
                                    <td>
                                        <br><br>_________________________________________
                                    </td>
                                    <td>
                                        <br><br>_________________________________________
                                    </td>
                                </tr>";
        
        $corpo  .=  "</table></body>";

        $emails_array = array('francini@dshop.com.br');
        $template_html = 'teste_02';
        $nm_subject = 'Cartao C&A - SMS allin';
        $nm_remetente = 'LOG - Cartao C&A';
        $email_remetente = 'log@dshopdesenv.com';
        $nm_reply = 'log@dshopdesenv.com';
        $campos = 'mensagem';
        $valor = $corpo;          
        $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);

        /*
        $assunto            =   'Cartao C&A - SMS';
        $sender             =   'rudney@dshop.com.br';
        $userName           =   'rudney@dshop.com.br';
        $from               =   'rudney@dshop.com.br';
        $replyTo            =   'rudney@dshop.com.br';
        $fromName           =   'rudney@dshop.com.br';
        $confirmacao        =   '';
        $password           =   'rud@dshop@ney';
        
        $this->enviarEmail ( 'rudney@dshop.com.br', $sms['nomePresenteado'], $assunto, $corpo, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        $this->enviarEmail ( 'diego@dshop.com.br', $sms['nomePresenteado'], $assunto, $corpo, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        $this->enviarEmail ( 'vanessa@dshop.com.br', $sms['nomePresenteado'], $assunto, $corpo, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        $this->enviarEmail ( 'log@dshopdesenv.com', $sms['nomePresenteado'], $assunto, $corpo, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        */
        //$this->enviarEmail ( 'rudney@dshop.com.br', $sms['nomePresenteado'], $assunto, $corpo, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
        //$this->enviarEmail ( 'rudney@dshop.com.br', $sms['nomePresenteado'], $assunto, $corpo, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password );
    }
    public function informarEnviosAtrasados ( $atrasados )  {
        $html           =  "<body>
                                    <h2>Envios atrasados - Sistema de Cartões</h2>
                                    <br>
                                    <table border='1'>
                                        <thead>
                                            <th>
                                                Pedido
                                            </th>
                                            <th>
                                                Atendimento
                                            </th>
                                            <th>
                                                Data Liberação
                                            </th>
                                        </thead>
                                        <tbody>";
        foreach ( $atrasados as $atrasado )   {
            $html       .=  "               <tr>
                                                <td>
                                                    "   .   $atrasado->pedido           .   "
                                                </td>
                                                <td>
                                                    "   .   $atrasado->atendimento      .   "
                                                </td>
                                                <td>
                                                    "   .   $atrasado->dataLiberacao    .   "
                                                </td>
                                            </tr>";
        }
        $html           .=  "           </tbody>
                                    </table>
                                </body>";
                                /*
        $assunto        =   utf8_encode ( 'Envios atrasados - Sistema de Cartões' );
        $confirmacao    =   '';
        $sender         =   'rudney@dshop.com.br';
        $userName       =   'rudney@dshop.com.br';
        $from           =   'monitoramento@dshop.com.br';
        $replyTo        =   'monitoramento@dshop.com.br';
        $fromName       =   'Monitoramento - Sistema de Cartões';
        $password       =   'rud@dshop@ney';
        
        $this->enviarEmail (    'rudney@dshop.com.br','Rudney',$assunto,utf8_encode ( $html ),$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        $this->enviarEmail (    'juliana@dshop.com.br','Rudney',$assunto,utf8_encode ( $html ),$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        */
        //$this->enviarEmail (    'william@dshop.com.br','Rudney',$assunto,$html,$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        //$this->enviarEmail (    'daniel@dshop.com.br','Rudney',$assunto,$html,$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        $emails_array = array('juliana@dshop.com.br');
        $template_html = 'teste_02';
        $nm_subject = 'all in Envios atrasados - Sistema de Cartões';
        $nm_remetente = 'Monitoramento - Sistema de Cartões';
        $email_remetente = 'log@dshopdesenv.com';
        $nm_reply = 'log@dshopdesenv.com';
        $campos = 'mensagem';
        $valor = $html;
        $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);
    }
    public function informarEnviosAtrasadosRudney ( $atrasados )  {
        $html           =  "<body>
                                    <h2>Envios atrasados - Sistema de Cartões - Des</h2>
                                    <br>
                                    <table border='1'>
                                        <thead>
                                            <th>
                                                Pedido
                                            </th>
                                            <th>
                                                Atendimento
                                            </th>
                                            <th>
                                                Data Liberação
                                            </th>
                                        </thead>
                                        <tbody>";
        foreach ( $atrasados as $atrasado )   {
            $html       .=  "               <tr>
                                                <td>
                                                    "   .   $atrasado->pedido           .   "
                                                </td>
                                                <td>
                                                    "   .   $atrasado->atendimento      .   "
                                                </td>
                                                <td>
                                                    "   .   $atrasado->dataLiberacao    .   "
                                                </td>
                                            </tr>";
        }
        $html           .=  "           </tbody>
                                    </table>
                                </body>";
        /*
        $assunto        =   utf8_encode ( 'Envios atrasados - Sistema de Cartões' );
        $confirmacao    =   '';
        $sender         =   'rudney@dshop.com.br';
        $userName       =   'rudney@dshop.com.br';
        $from           =   'monitoramento@dshop.com.br';
        $replyTo        =   'monitoramento@dshop.com.br';
        $fromName       =   'Monitoramento - Sistema de Cartões';
        $password       =   'rud@dshop@ney';
        
        $this->enviarEmail (    'rudney@dshop.com.br','Rudney',$assunto,utf8_encode ( $html ),$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        */
        //$this->enviarEmail (    'juliana@dshop.com.br','Rudney',$assunto,utf8_encode ( $html ),$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        //$this->enviarEmail (    'william@dshop.com.br','Rudney',$assunto,$html,$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        //$this->enviarEmail (    'daniel@dshop.com.br','Rudney',$assunto,$html,$confirmacao,$sender,$userName,$from,$replyTo,$fromName, $password );
        $emails_array = array('juliana@dshop.com.br');
        $template_html = 'teste_02';
        $nm_subject = 'all in Envios atrasados - Sistema de Cartões';
        $nm_remetente = 'Monitoramento - Sistema de Cartões';
        $email_remetente = 'log@dshopdesenv.com';
        $nm_reply = 'log@dshopdesenv.com';
        $campos = 'mensagem';
        $valor = $html;
        $this->enviarEmail_allin ($emails_array, $template_html, $nm_subject, $nm_remetente, $email_remetente, $nm_reply, $campos,$valor);
    }
    public function enviarEmail ( $to, $nome, $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password ) {
        $this->_sender->IsSMTP();                                       // Define que a mensagem será SMTP
        $this->_sender->CharSet             = "utf-8";
        $this->_sender->Host                = "mail.dshop.com.br";      // Endereço do servidor SMTP
        $this->_sender->SMTPAuth            = true;                     // Usa autenticação SMTP? (opcional)
        $this->_sender->Username            = $userName;                // Usuário do servidor SMTP
        $this->_sender->Password            = $password;          // Senha do servidor SMTP
        $this->_sender->Sender              = $sender;                  // Seu e-mail
        $this->_sender->From                = $from;                    // Seu e-mail
        $this->_sender->FromName            = $fromName;                // Seu nome
        $this->_sender->AddAddress($to, $nome);
        $this->_sender->IsHTML(true);                                   // Define que o e-mail será enviado como HTML
        $this->_sender->Subject             = $assunto;                 // Assunto da mensagem
        $this->_sender->Body                = $html;
        $this->_sender->AltBody             = $html;
        $this->_sender->ConfirmReadingTo    = $confirmacao;
        $this->_sender->AddReplyTo($replyTo, 'No-Reply');
        $enviado                            = $this->_sender->Send();
        $this->_sender->ClearAllRecipients();

        if ( !$enviado ){
            $header = "MIME-Version: 1.1 \n";
            $header .= "Content-type: text/html; charset=utf-8 \n";
            $header .= "From: $fromName<$from> \n";
            $header .= "Return-Path: $fromName<$from> \n";
            $header .= "Reply-To: $replyTo \n"; // reply to
            $header .= "Disposition-Notification-To: $sender \n"; 
            $header .= "X-Confirm-Reading-To: $confirmacao\n";
            $enviado    =   mail($to, $assunto, $html, $header);
        }
        return $enviado;
    }

    public function enviarEmail_allin ($array_emails,$template_html,$nm_subject,$nm_remetente,$email_remetente,$nm_reply,$campos,$valor) {
        //$array_emails = array('pedrohemorais@gmail.com','pedro.morais@dshop.com.br','daniel@dshop.com.br','william@dshop.com.br');
        $xml_envio = '<mailTransact>';
        date_default_timezone_set('America/Sao_Paulo');
        $dadosFormulario = array();
        foreach($array_emails as $email){
            //É MUITO IMPORTANTE QUE OS ARRAYS ESTEJAM NA ORDEM CORRETA
            $dadosFormulario['nm_email'] = $email;
            $dadosFormulario['template_html'] =   $template_html;
            $dadosFormulario['nm_subject'] = $nm_subject;
            $dadosFormulario['nm_remetente'] = $nm_remetente;
            $dadosFormulario['email_remetente'] = $email_remetente;
            $dadosFormulario['nm_reply'] =  $nm_reply;
            $dadosFormulario['campos'] = $campos;
            $dadosFormulario['valor'] = $valor;
            //Evita erros convertendo os caracteres
            $dadosFormulario['nm_subject'] = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").";"', html_entity_decode($dadosFormulario['nm_subject']));
            $dadosFormulario['nm_remetente'] = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").";"', html_entity_decode($dadosFormulario['nm_remetente'],ENT_QUOTES,'UTF-8'));
            $dadosFormulario['nm_reply'] = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").";"', html_entity_decode($dadosFormulario['nm_reply']));
            $dadosFormulario['valor'] = str_replace("'", "\"",$dadosFormulario['valor']);
            //$dadosFormulario['valor'] = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").";"', html_entity_decode($dadosFormulario['valor']));
            $dadosFormulario['valor'] = htmlentities($dadosFormulario['valor']);

            //header('content-type: text/xml');
            $xml_envio .= '<dadosEmail>';
            foreach($dadosFormulario as $k=>$v){
                $xml_envio.= '<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
            }
            $xml_envio.= '</dadosEmail>';
        }
        $xml_envio .= '</mailTransact>'; 
        //print_r($xml_envio);die();
        $xmlDados = array('xmlDados'=>$xml_envio);
        $soap = new SoapClient("http://services.dshopdesenv.com/ds/server.php/?wsdl");
        try{
            $rs = $soap->SendMailTransaction((object)$xmlDados);
        }
        catch(Exception $e) {
            print_r($e);die();
        }
        ob_clean();
        $saida = $rs->SendMailTransactionResult;
        return $saida;
    }
}
/*
$email = new Email();
$sms['pedido']           = 'pedido';
$sms['valor']            = 'valor';
$sms['nomePresenteado']  = 'nomePresenteado';
$sms['nomeComprador']    = 'nomeComprador';
$sms['emailPresenteado'] = 'emailPresenteado';
$sms['emailComprador']   = 'emailComprador';
$sms['codigoCartao']     = 'codigoCartao';
$sms['mensagem']         = 'mensagem';

$send = '';
print_r($email->enviarDadosSms($sms));
*/
//$email = new Email();
//print_r($email->aa());
?>
