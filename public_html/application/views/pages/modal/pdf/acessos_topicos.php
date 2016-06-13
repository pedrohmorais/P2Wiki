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

     table{
     	
      width:100%;
     }
  
     .exemplo {
       background-color: #CCC;
       color: #FFF;
     }
   </style>
 </head>
 <body>
   
  <div style="position:absolute;top:10px;left:10px;">
  	<img src="http://www.p2wiki.com.br/images/LogoCursive150.png"  />
  </div>
  <center style="position:absolute;top:50px;left:200px;">

      <h1 >Relatório de Tópicos</h1>

  </center>
  <br>

 <div style="margin-top:40px;z-index:1;">
    	  
        <div>
          <h3>Tópicos aprovados : '.$count_topicos['S'].'</h3>
        </div>
        <div>
          <h3>Tópicos não aprovados : '.$count_topicos['N'].'</h3>
        </div>
        <div>
          <h3>Total : '.($count_topicos['S'] + $count_topicos['N']).'</h3>
        </div>
        <table id="tabelaTopicos">

            <thead>

                <tr class="filters">

                    <th>Código</th>

                    <th>Nome</th>

                    <th>Categoria</th>

                    <th>Aprovado</th>

                    <th>Acessos</th>
     
                </tr>

        </thead>

        <tbody>

        
 ';
 		foreach($topicos as $key=>$value){

 		$html .= '<tr>';

        $html .= '<td style="text-align:center;">'.$topicos[$key]['cod_topico'].'</td>';

        $html .= '<td>'.$topicos[$key]['nome_topico'].'</td>';

        $html .= '<td style="text-align:center;">'.$topicos[$key]['cod_categoria'].'</td>';

        $html .= '<td style="text-align:center;">'.$topicos[$key]['aprovado'].'</td>';

        $html .= '<td style="text-align:center;">'.$topicos[$key]['acessos'].'</td>';
	
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