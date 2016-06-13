<?php
 require   ("./PHPMailer-master/class.phpmailer.php");
 
if ( ! function_exists('enviarEmail'))
{    
    function enviarEmail ( $to, $nome, $assunto, $html, $confirmacao, $sender, $userName, $from, $replyTo, $fromName, $password ) {
        $email = new PHPMailer; 
        $email->IsSMTP();                                       // Define que a mensagem será SMTP
        $email->CharSet             = "utf-8";
        $email->Host                = "mail.p2wiki.com.br";      // Endereço do servidor SMTP
        $email->SMTPAuth            = true;                     // Usa autenticação SMTP? (opcional)
        $email->Username            = $userName;                // Usuário do servidor SMTP
        $email->Password            = $password;          // Senha do servidor SMTP
        $email->Sender              = $sender;                  // Seu e-mail
        $email->From                = $from;                    // Seu e-mail
        $email->FromName            = $fromName;                // Seu nome
        $email->AddAddress($to, $nome);
        $email->IsHTML(true);                                   // Define que o e-mail será enviado como HTML
        $email->Subject             = $assunto;                 // Assunto da mensagem
        $email->Body                = $html;
        $email->AltBody             = $html;
        $email->ConfirmReadingTo    = $confirmacao;
        $email->AddReplyTo($replyTo, 'No-Reply');
        $enviado                            = $email->Send();
        $email->ClearAllRecipients();

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
}


?>
