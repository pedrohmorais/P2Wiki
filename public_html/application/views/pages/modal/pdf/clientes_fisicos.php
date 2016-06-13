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

            <h3 class="panel-title">Clientes Físicos</h3>

        </div>

        <table class="table" id="tabelaTopicos">

            <thead>

                <tr class="filters">
                    <th>Código</th>
                    <th style="width:100px !important;">Nome &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Cpf</th>
                    <th>Sexo</th>
                    <th>cep</th>
                    <th>estado</th>
                </tr>

        </thead>

        <tbody>

        
 ';
    foreach($usuario_fisico as $key=>$value){

    $html .= '<tr>';

        $html .= '<td >'.$usuario_fisico[$key]['cod_cliente'].'</td>';
        $html .= '<td>'.$usuario_fisico[$key]['nome'].'</td>';
        $html .= '<td>'.$usuario_fisico[$key]['cpf'].'</td>';
        $html .= '<td>'.$usuario_fisico[$key]['sexo'].'</td>';
        $html .= '<td>'.$usuario_fisico[$key]['cep'].'</td>';
        $html .= '<td>'.$usuario_fisico[$key]['estado'].'</td>';
  
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