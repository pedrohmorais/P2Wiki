<?php
$html = '
<html>
<head>
<title>Categorias a validar</title>
<style>
body {
  font-family: Calibri, DejaVu Sans, Arial;
  margin: 0;
  padding: 0;
  border: none;
  font-size: 13px;
}
td,th{

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

<h1>Produtividade do grupo: <br> '.$info_grupo['group_id'].' - '.$info_grupo['descricao'].'</h1>

</center>
<br>

<div style="margin-top:80px;z-index:1;">
 <div style="width:100%;">
    	<h3>O cálculo da produtividade é feito pela soma dos Comentários x 1, mais Conteúdos x 2 e Tópicos x 3.</h3>
        <table class="table" id="tabelaTopicos" style="text-align:center;width:100%;">

            <thead>

                <tr class="filters">

                    <th>Nome</th>

                    <th>Username</th>

                    <th>Comentarios</th>

                    <th>Conteúdos</th>

                    <th>Tópicos</th>

                    <th>Produtividade</th>

                </tr>

        </thead>

        <tbody>

        
 ';
 		foreach($usuario as $key=>$value){

 		$html .= '<tr>';

        $html .= '<td>'.$usuario[$key]['nome'].'</td>';
        $html .= '<td>'.$usuario[$key]['username'].'</td>';
        $html .= '<td>'.$usuario[$key]['qtd_comentarios'].'</td>';
        $html .= '<td>'.$usuario[$key]['qtd_conteudos'].'</td>';
        $html .= '<td>'.$usuario[$key]['qtd_topicos'].'</td>';
        $html .= '<td>'.$usuario[$key]['produtividade'].'</td>';

	
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