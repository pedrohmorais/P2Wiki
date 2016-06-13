<?php
  
  function getLevel($parent_topic = 0){
   
   #PEGA TODOS DO NÍVEL INICIAL
   $query = mysql_query("SELECT *
    FROM
      tickets_topics
    LEFT OUTER JOIN
      (SELECT id_topic, COUNT(id_topic) numTicket
          FROM
              topic_ticket
          GROUP BY id_topic       
      ) tabela
    USING(id_topic)
    WHERE
      inside_topic = '$parent_topic'
      ORDER BY topic_name") or die(mysql_error());
   
   
   
   while($topic =  mysql_fetch_array($query)){
      #PARA CADA UM, COLOCA NO NÍVEL DO ARRAY
      $level[$topic["id_topic"]]["id_topic"] = $topic["id_topic"];
      $level[$topic["id_topic"]]["name"] = $topic["topic_name"];
      $level[$topic["id_topic"]]["inside_topic"] = $topic["inside_topic"];
      $level[$topic['id_topic']]['numTicket'] = $topic['numTicket'] > 0 ? $topic['numTicket'] : 0;
      
      #VERIFICA SE TEM SUBNIVEIS
      $query_sub = mysql_query("SELECT * FROM tickets_topics WHERE inside_topic = '".$topic["id_topic"]."'");
      $count = mysql_num_rows($query_sub);
      
      if($count>0){
        $level[$topic["id_topic"]]["topics"] = getLevel($topic["id_topic"]);
      }
        
   }
   
   return $level;
   
  }
  
  function renderArray($array, $type = "options", $string='', $splitter = " => "){     
  
    foreach($array as $topic){
      
      if($type == "options"){echo "<option value='".$topic["id_topic"]."'>".$string.$topic["name"]."</option>";}
      
      elseif($type == "list"){
      
        $string = '';
      
        echo "<div class='topico'>";
        
        echo "#<font class='code'>".$topic["id_topic"]."</font> - <a style='font-weight:bold; ' href='javascript:void(0);' class='nomeTopico'>".$string.$topic["name"]."</a>";
        
        echo " &nbsp;&nbsp;({$topic['numTicket']})";
        
        //FORM DE EDIÇÃO
        echo "<form class='formularioEdicao' action='' method='post' id='edit_".$topic["id_topic"]."'>";
        echo " ".$topic["id_topic"]." ";
        echo "<input name='mode' type='hidden' value='edit'>";
        echo "<input name='topic_name' value='".$topic["name"]."'> ";
        echo "<input name='inside_topic' style='width:25px;' value='".$topic["inside_topic"]."'> ";
        echo "<input name='id_topic' type='hidden' value='".$topic["id_topic"]."'>";
        echo "<input type='submit' class='btn_enviar'>";
        echo "<input type='button' value='Cancelar' class='btnCancelar' onclick='document.getElementById(\"edit_".$topic["id_topic"]."\").style.display=\"none\";'>";
        echo "</form>";
        
        //LINKS
        global  $superAdm;
        
        
        if($superAdm > 0){
        if(!isset($topic["topics"])){
          echo " <a class='deletTopic' href='?mode=delete&id_topic=$topic[id_topic]'>Deletar </a>";
          }
        
          echo " <a class='editTopic' href='javascript:void(0);' onclick='document.getElementById(\"edit_".$topic["id_topic"]."\").style.display=\"inline\";'> Editar </a>";
        }   
      }
      
      if(isset($topic["topics"])){
        renderArray($topic["topics"], $type, $string.$topic["name"].$splitter, $splitter);
      }
      
      if($type == "list"){echo "</div>";}
      
    };
  
  }
    
?>
