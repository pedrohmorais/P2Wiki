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

<h1 >Categorias a validar</h1>

</center>
<br>

<div style="margin-top:40px;z-index:1;">

';
foreach($usuario as $key=>$value){



}
if (count($categorias_grupo) > 0) {

  foreach ($categorias_grupo as $key => $value) {
    $html .= '<div style="width:100%;">';
    $html .= '<span style="font-size:14px;">Nome Categoria: '.$value['dados_categoria']->nome_categoria.'</span><br>';
    $html .= '<span style="font-size:14px;">Validado por : '.$value['validacao']['nome_usuario'].'</span><br>';
    if(!isset($topico_cat->data_aprovacao) || date ( 'Y' ,strtotime($value['validacao']['data_validacao']) < 2014 )){
      $html .= '<span style="font-size:14px;">Data de validação : Não Validado.</span><br>';
    }
    else{
      $html .= '<span style="font-size:14px;">Data de validação : '.date ( 'd-m-Y à\s H:i:s' ,strtotime($value['validacao']['data_validacao']) ).'</span><br>';
    }
    /*
    if(isset($value['validacao']['data_validacao']) && $value['validacao']['data_validacao'] != '' ){
      $html .= '<td style="text-align:center;font-size:14px;">'.date ( 'd-m-Y à\s H:i:s' ,strtotime($value['validacao']['data_validacao']) ).'</td>';
    }
    else{
      $html .= '<td coluna="data" style="display: inline-block;font-weight:bold;18%"></td>       ';
    }
    */
    $html .= '<span style="font-size:14px;">Tópicos: </span>';
    if(isset($value['topicos']) && count($value['topicos']) > 0 && $value['topicos'][0]->cod_topico>0 ){
      $html .= '<table style="width:100%;">';
      $html .= '<tr>';
      $html .= '<th>Código</th>';
      $html .= '<th>Nome</th>';
      $html .= '<th>Aprovado</th>';
      $html .= '<th>Data Aprovado</th>';
      $html .= '<th>Acessos</th>';
      $html .= '</tr>';
        foreach ($value['topicos'] as $topico_cat) {
          $html .= '<tr>';
          $html .= '<td style="text-align:center;">'.$topico_cat->cod_topico.'</td>';
          $html .= '<td style="">'.$topico_cat->nome_topico.'</td>';
          $html .= '<td style="text-align:center;">'.$topico_cat->aprovado.'</td>';
          if($topico_cat->data_aprovacao == '0000-00-00 00:00:00'){
          //if(!isset($topico_cat->data_aprovacao) || date ( 'Y' ,strtotime($topico_cat->data_aprovacao) < 2013 )){
            $html .=        '<td style="text-align:center;">Não aprovado.</td>';
          }
          else{
            $html .=        '<td style="text-align:center;">'.date ( 'd-m-Y à\s H:i:s' ,strtotime($topico_cat->data_aprovacao) ).'</td>';
          }
          $html .= '<td style="text-align:center;">'.$topico_cat->acessos.'</td>';
          $html .= '</tr>';
        }
      $html .= '</table>';
    }
    else{
      $html .= '<div>';
      $html .=    '<span style="text-align:center;font-weight:bold;width:100%;">Sem tópicos a gerenciar !</span>';
      $html .= '</div>';
    }






    $html .= '<br></div><br>';
  }
    //$html .= '</div>';
}

else{
  $html .= '<div>';
  $html .=    '<span style="text-align:center;font-weight:bold;width:100%;">Sem categorias a gerenciar !</span>';
  $html .= '</div>';
}

























$html .= '   </body>';

$html .= '</html>';
include('mpdf/mpdf.php'); // Apenas inclua o arquivo mpdf com o caminho correto
$mpdf= new mPDF();
$mpdf->WriteHTML($html); //Insira a variável com conteúdo para ser escrita.
$mpdf->Output();
exit();
?>