<?
Class Mail
{
	public function __construct()
	{
		require(dirname($_SERVER['SCRIPT_FILENAME']).'/'.APPPATH.'/helpers/phpmailer/class.phpmailer.php');
	}
	
	public function sendMail($data_dados=null)
	{
		// Inicia a classe PHPMailer
		$mail = new PHPMailer();
		 
		// Define os dados do servidor e tipo de conexão
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsSMTP(); // Define que a mensagem será SMTP
		//$mail->SMTPDebug = 1;     
		$mail->Host = "ssl://server.coho.com.br"; // Endereço do servidor SMTP (caso queira utilizar a autenticação, utilize o host smtp.seudomínio.com.br)
		$mail->Port = 465;
		$mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
		$mail->Username = 'saida_smtp@liguesite.com.br'; // Usuário do servidor SMTP (endereço de email)
		$mail->Password = 'Tk(*2}e6m_44'; // Senha do servidor SMTP (senha do email usado)
		 
		// Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->From = "financeiro@liguesite.com.br"; // Seu e-mail
		$mail->Sender = "financeiro@liguesite.com.br"; // Seu e-mail
		$mail->FromName = isset($data_dados['fromname']) ? $data_dados['fromname'] : "Novo candidato"; // Seu nome
		 
		// Define os destinatário(s)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->AddAddress($data_dados['email'], $data_dados['nome']);
		//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
		if(!empty($data_dados['BCC']))$mail->AddBCC($data_dados['BCC'], 'Eva Eventos'); // Cópia Oculta
		 
		// Define os dados técnicos da Mensagem
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
		 
		// Define a mensagem (Texto e Assunto)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->Subject  = $data_dados['assunto']; // Assunto da mensagem
		$mail->Body = $data_dados['mensagem'];
		
		 
		// Define os anexos (opcional)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//$mail->AddAttachment("/home/login/documento.pdf", "novo_nome.pdf");  // Insere um anexo
		 
		// Envia o e-mail
		$enviado = $mail->Send();
		 
		// Limpa os destinatários e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		 
		// Exibe uma mensagem de resultado
		if ($enviado) {
			return true;
		} else {
			/*echo "Não foi possível enviar o e-mail.
			 
			";
			echo "Informações do erro: 
			" . $mail->ErrorInfo;*/
			die($mail->ErrorInfo);
		}
	}
	
	public function getTemplate($data_dados=null)
	{

		$template = "
			<style type=\"text/css\">
				  
			/* Resets: see reset.css for details */
			.ReadMsgBody { width: 100%; background-color: #0162A2;}
			.ExternalClass {width: 100%; background-color: #0162A2;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
			body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
			body {margin:0; padding:0;}
			table {border-spacing:0;}
			table.border_bottom {border-bottom:1px solid #333;}
			table td {border-collapse:collapse;}
			.yshortcuts a {border-bottom: none !important;}


			/* Constrain email width for small screens */
			@media screen and (max-width: 600px) {
				table[class=\"container\"] {
					width: 95% !important;
				}
			}

			/* Give content more room on mobile */
			@media screen and (max-width: 480px) {
				td[class=\"container-padding\"] {
					padding-left: 12px !important;
					padding-right: 12px !important;
				}
			 }

				  
				/* Styles for forcing columns to rows */
				@media only screen and (max-width : 600px) {

					/* force container columns to (horizontal) blocks */
					td[class=\"force-col\"] {
						display: block;
						padding-right: 0 !important;
					}
					table[class=\"col-2\"] {
						/* unset table align=\"left/right\" */
						float: none !important;
						width: 100% !important;

						/* change left/right padding and margins to top/bottom ones */
						margin-bottom: 12px;
						padding-bottom: 12px;
						border-bottom: 1px solid #eee;
					}

					/* remove bottom border for last column/row */
					table[id=\"last-col-2\"] {
						border-bottom: none !important;
						margin-bottom: 0;
					}

					/* align images right and shrink them a bit */
					img[class=\"col-2-img\"] {
						float: right;
						margin-left: 6px;
						max-width: 130px;
					}
				}


				</style>

			</head>
			<body style=\"margin:0; padding:0;border-top:2px solid #FCB813;\" bgcolor=\"#ffffff\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">

			<!-- 100% wrapper (grey background) -->
			<table border=\"0\" width=\"100%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#fff\">
			  <tr>
				<td align=\"center\" valign=\"top\" bgcolor=\"#ebebeb\" style=\"background-color: #ffffff;\">

				  <!-- 600px container (white background) -->
				  <table border=\"0\" width=\"600\" cellpadding=\"0\" cellspacing=\"0\" class=\"container\" bgcolor=\"#ffffff\">
					<tr>
					  <td class=\"container-padding\" bgcolor=\"#ffffff\" style=\"background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 14px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;\">
						<br>

						

			<div style=\"font-weight: bold; font-size: 24px; line-height: 24px; color: #FCB813;\">

				<div style=\"text-align:center;\">
					<img src=\"http://www.centralliguesite.com.br/application/third_party/xsystem/images/logo_2014_blue.png\" width=\"200\" />
				</div>
				<div style=\"font-weight: bold; font-size: 20px; line-height: 24px; color: #FCB813;\">
					
				</div>
			</div>
			<br>

			<div style=\"font-size: 14px; line-height: 24px; color: #0162A2; \"><br>
				$data_dados[header]</div>

			<div style=\"font-size: 12px; line-height: 24px; color: #333333;\"><br>

			$data_dados[principal]
			
			<br>
			
			</div>
			</td>
			</tr>
			<tr>
			<td class=\"container-padding\" bgcolor=\"#ffffff\" style=\"background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;\" align=\"left\">
			<br>

			<div style=\"font-weight: bold; font-size: 18px; line-height: 24px; color: #D03C0F; border-top: 1px solid #ddd;\"><br></div>


			<strong>Atenciosamente, <br/><br/></strong>Equipe Ligue Site - Conectando sua empresa<br/><a href=\"http://www.liguesite.com.br\" target=\"_blank\">http://www.liguesite.com.br</a><br><br>

			<br><br>


					  </td>
					</tr>
				  </table>
				  <!--/600px container -->

				</td>
			  </tr>
			</table>
			<!--/100% wrapper-->
		";
		
		return $template;
	}
}
?>