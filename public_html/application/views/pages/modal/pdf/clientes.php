<?php
$html = '
 <html>
 <head>
   <style>
     body {
       font-family: Calibri, DejaVu Sans, Arial;
       margin: 0;
       padding: 0;
       border: none;
       font-size: 13px;
     }
     td,th{
     	border: 1px solid black;
     }
  
     .exemplo {
       background-color: #CCC;
       color: #FFF;
     }
   </style>
 </head>
 <body>
   
 <div style="position:absolute;top:45px;left:10px;">
 	<img src="http://www.p2wiki.com.br/imagens/Logo/oie_transparent.jpg"  />
 </div>
 <div style="margin-left:130px;">
    	<div>

            <h3 class="panel-title">Clientes</h3>

        </div>

        <table class="table" id="tabelaTopicos">

            <thead>

                <tr class="filters">
                    <th>cod_cliente</th>
                    <th>nome</th>
                    <th>tipo</th>
                    <th>cep</th>
                    <th>estado</th>
                    <th>data_cad</th>
                    <th>data_exp</th>
                    <th>ativo</th>
                </tr>

        </thead>

        <tbody>

        
 ';
 		foreach($usuario as $key=>$value){

 		$html .= '<tr>';

        $html .= '<td >'.$usuario[$key]['cod_cliente'].'</td>';
        $html .= '<td>'.$usuario[$key]['nome'].'</td>';
        $html .= '<td>'.$usuario[$key]['tipo'].'</td>';
        $html .= '<td>'.$usuario[$key]['cep'].'</td>';
        $html .= '<td>'.$usuario[$key]['estado'].'</td>';
        $html .= '<td>'.$usuario[$key]['data_cad'].'</td>';
        $html .= '<td>'.$usuario[$key]['data_exp'].'</td>';
        $html .= '<td>'.$usuario[$key]['ativo'].'</td>';
	
		$html .= '</tr>';
           
        }
        $html .= '   </tr>
</div>
		           			</tbody>

			            </table></body>';

 		$html .= '</html>';
  include('mpdf/mpdf.php'); // Apenas inclua o arquivo mpdf com o caminho correto
  $mpdf= new mPDF();
  $mpdf->WriteHTML($html); //Insira a variável com conteúdo para ser escrita.
  $mpdf->Output();
  exit();
?>