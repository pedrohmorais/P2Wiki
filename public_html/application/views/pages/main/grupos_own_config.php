 <!-- ckeditor --> <?   $data_user = $this->session->userdata('data_user');$data_user = $data_user[0];  //die(print_r($data_user)); ?> 

<script src="http://www.p2wiki.com.br/application/third_party/plugins/ckeditor/ckeditor.js"></script>

<style>
a:hover{
    text-decoration: none;
}
.linhaCategoria:hover{
    background-color:#f5f5f5 !important;
}
.trTopico:hover{
    background-color: #f5f5f5 !important;
}
#tableCategoriasList{
    cursor:pointer;
}
.filterable {

    margin-top: 15px;

}

.filterable .panel-heading .pull-right {

    margin-top: -20px;

}

.filterable .filters input[disabled] {

    background-color: transparent;

    border: none;

    cursor: auto;

    box-shadow: none;

    padding: 0;

    height: auto;

}

.filterable .filters input[disabled]::-webkit-input-placeholder {

    color: #333;

}

.filterable .filters input[disabled]::-moz-placeholder {

    color: #333;

}

.filterable .filters input[disabled]:-ms-input-placeholder {

    color: #333;

}

#tabelaTopicos tr:hover{

    background-color:#CCDDED;

}
#tabelaIntegrantes tbody tr:hover{

    background-color: #D3DCE3;

}
[data-toggle="modal"]:hover{
    background-color: #D3DCE3;
}

</style>    

<script>
    function updatePermition(group,usuario,permition){
        $.ajax({

            url : 'http://www.p2wiki.com.br/grupos/updatePermition', 

            cache : false,

            async : false,

            type : 'post',

            dataType : 'json',

            data : 'group_id='+group+'&usuario='+usuario.substr(14)+'&permition='+permition,

            success: function(response,status) {
                console.log(response.retorno);/*
                if(response.retorno == 'true'){
                    $('#glyphicon'+group_id).attr('data-content','Clique para inativar o grupo.');
                    $('#glyphicon'+group_id).css('color','green');
                    $('#glyphicon'+group_id).removeClass('glyphicon-remove');
                    $('#glyphicon'+group_id).addClass('glyphicon-ok');
                    $('#glyphicon'+group_id).attr('onclick','inativarGrupo('+ group_id +')' );
                }*/
                return true;
            }    
        });
    }
    function ativarGrupo(group_id){
        $.ajax({

            url : 'http://www.p2wiki.com.br/grupos/ativarGrupo', 

            cache : false,

            async : false,

            type : 'post',

            dataType : 'json',

            data : 'group_id='+group_id,

            success: function(response,status) {
                console.log(response.retorno);
                if(response.retorno == 'true'){
                    $('#glyphicon'+group_id).attr('data-content','Clique para inativar o grupo.');
                    $('#glyphicon'+group_id).css('color','green');
                    $('#glyphicon'+group_id).removeClass('glyphicon-remove');
                    $('#glyphicon'+group_id).addClass('glyphicon-ok');
                    $('#glyphicon'+group_id).attr('onclick','inativarGrupo('+ group_id +')' );
                }
                return true;
            }    
        });
    }
    function inativarGrupo(group_id){
        $.ajax({

            url : 'http://www.p2wiki.com.br/grupos/inativarGrupo', 

            cache : false,

            async : false,

            type : 'post',

            dataType : 'json',

            data : 'group_id='+group_id,

            success: function(response,status) {
                console.log(response.retorno);
                if(response.retorno == 'true'){
                    $('#glyphicon'+group_id).attr('data-content','Clique para ativar o grupo.');
                    $('#glyphicon'+group_id).css('color','red');
                    $('#glyphicon'+group_id).removeClass('glyphicon-ok');
                    $('#glyphicon'+group_id).addClass('glyphicon-remove');
                    $('#glyphicon'+group_id).attr('onclick','ativarGrupo('+ group_id +')' );
                }
                return true;

            }
        });
    }
    function kickUsuario(group_id,user_id){
        $.ajax({

            url : 'http://www.p2wiki.com.br/grupos/kickUsuario', 

            cache : false,

            async : false,

            type : 'post',

            dataType : 'json',

            data : 'group_id='+group_id+'&user_id='+user_id,

            success: function(response,status) {
                //console.log(response.retorno);
                
                if(response.retorno == 'true'){
                    $('#kickUsuario'+user_id).parent().parent().remove();
                    alert('O usuário '+user_id+' foi excluido com sucesso !');
                }
                
                return true;

            }
        });
    }
    

</script>

<body style="overflow-x:hidden !important;">

    <div class="page-container">

            <!-- menu -->

            <?php $this->load->view('templates/main/menu'); ?>

        <div class="container">

            <div class="row row-offcanvas row-offcanvas-left">
                <div class="col-xs-10 col-sm-10" >
                    <h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" class="col-sm-12">Informações do grupo <?php echo $info_grupo[0]['descricao']; ?> : </h2>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Cliente Responsável:</span><span><?php echo $info_grupo[0]['cod_cliente'].' - '.$info_grupo[0]['nome_cliente']; ?></span>
                    <br>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Quantidade de integrantes: </span><span><?php echo $info_grupo[0]['usuarios']; ?></span>
                    <br>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Limite de integrantes: </span><span><?php echo $info_grupo[0]['limite']; ?></span>
                    <br>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Ativo:</span>
                    <?php    
                        if($info_grupo[0]['ativo'] == 0){
                            echo '        <span id="glyphicon'.$info_grupo[0]['group_id'].'" data-placement="top" data-toggle="popover" data-content="Clique para ativar o grupo."style="cursor:pointer;font-size:16px;color:red;" class="hidden-xs showopacity glyphicon ';
                            echo 'glyphicon-remove';
                            echo '" onclick="ativarGrupo('.$info_grupo[0]['group_id'].')"></span>';
                        }
                        else{
                            echo '        <span id="glyphicon'.$info_grupo[0]['group_id'].'" data-placement="top" data-toggle="popover" data-content="Clique para inativar o grupo." style="cursor:pointer;font-size:16px;color:green;" class="hidden-xs showopacity glyphicon ';
                            echo 'glyphicon-ok';
                            echo '" onclick="inativarGrupo('.$info_grupo[0]['group_id'].')"></span>';
                        }
                    ?>
                    <br>

                    <a target="_blank" href="<?php echo site_url('pdf?rel=rel_produtividade&g='.$info_grupo[0]['group_id']); ?>" style="display: inline-block; font-size: 18px; font-weight: bold;" class="form-control-static"><span class="glyphicon glyphicon-save-file" aria-hidden="true" style="display:inline-block;margin-left:20px;"></span>Gerar relatório de produtividade dos usuários.</a>



                    <h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" class="col-sm-12"></h2>
                </div>
                <div class="col-xs-12 col-sm-12" >
                    <div style="width: 100%; height: auto;padding:5px;">
                        <div class="list-group">
                            <span class="list-group-item disabled" style="cursor:default;">
                                Significado das "Permissões" do grupo.
                            </span>
                            <span href="#" class="list-group-item">Elas determinam qual o acesso que o usuário tem no grupo.</span>
                            <span href="#" class="list-group-item">Elas são válidas apenas para este grupo.</span>
                            <span class="list-group-item">Clique sobre elas caso tenha dúvidas sobre seu significado.</span>
                            <span style="cursor:help;" class="list-group-item" data-target="#permition1Modal" data-toggle="modal"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="font-size: 10px; margin-right: 10px;"></span>Permissão Nível 1</span>
                            <span style="cursor:help;" class="list-group-item" data-target="#permition2Modal" data-toggle="modal"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="font-size: 10px; margin-right: 10px;"></span>Permissão Nível 2</span>
                            <span style="cursor:help;" class="list-group-item" data-target="#permition3Modal" data-toggle="modal"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="font-size: 10px; margin-right: 10px;"></span>Permissão Nível 3</span>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="permition1Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-backdrop fade in" style="width: 100%; height: 100%;"></div>
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Permissão Nível 1 : </h4>
                                    </div>
                                    <div class="modal-body">
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> São usuários que tem permissão apenas para ler os conteúdos, dos tópicos criados por este grupo.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Eles não tem permissão para alterar ou excluir nada.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Nenhuma categoria criada por este grupo aparecerá no painel "Editar Categorias" deste usuário.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Usuários nível 1 são em geral pessoas que precisam apenas ter acesso de leitura aos conhecimentos transmitidos por um grupo.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Modal --><!-- Modal -->
                        <div class="modal fade" id="permition2Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-backdrop fade in" style="width: 100%; height: 100%;"></div>
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Permissão Nível 2 : </h4>
                                    </div>
                                    <div class="modal-body">
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> São usuários que podem editar e excluir as categorias criadas por este grupo.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Eles tem permissão para criar, editar e excluir Categorias, Tópicos e Conteúdos.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Tudo que for criado neste grupo poderá ser editado por um usuário nível 2.
                                        <br>
                                        <br>
                                        <h3>Importante :</h3>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Este tipo de permissão só deve ser dada à usuários de confiança pois todos os conteúdos existentes no grupo são de inteira responsabilidade do seu cliente criador.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Tome muito cuidado ao colocar informações de terceiros no seu conteúdo para evitar problemas de direitos autorais.

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Modal --><!-- Modal -->
                        <div class="modal fade" id="permition3Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-backdrop fade in" style="width: 100%; height: 100%;"></div>
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Permissão Nível 3 : </h4>
                                    </div>
                                    <div class="modal-body">
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> O usuário nível 3 tem todas as permissões dos outros usuários.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Além das permissões dos outros usuários um usuário nível 3 tem permissão para adicionar e remover outros usuários.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Atua no grupo como um administrador/moderador que tem jurisdição apenas no grupo em que lhe foi atribuido este nível, ou seja, a permissão que este usuário tem neste grupo não influencia em outros.
                                        <br>
                                        <br>
                                        <h3>Importante :</h3>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Cuidado com quem você escolhe para ter esta permissão, pois um usuário nível 3 tem plena liberdade de excluir qualquer coisa do grupo.
                                        <br>
                                        <br>
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Evite colocar muitos usuários com permissão nível 3 para obter um controle maior sobre seu grupo.

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Modal -->
                        <div class="panel panel-primary filterable">
                            <div class="panel-heading">

                                <h3 class="panel-title"  style="font-weight: bold;"><span style="margin-right:10px;" aria-hidden="true" class="glyphicon glyphicon-user"></span> Integrantes do grupo : </h3>

                                <div class="pull-right">

                                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

                                </div>

                            </div>
                            <table class="table" id="tabelaIntegrantes">
                                <thead>
                                    <tr class="filters">
                                        <th class="col-sm-4"><input type="text" disabled="" placeholder="Nome do Integrante" class="form-control"></th>

                                        <th class="col-sm-3"><input type="text" disabled="" placeholder="Username" class=" form-control"></th>

                                        <th class="col-sm-3"><input type="text" disabled="" placeholder="Email" class=" form-control"></th>

                                        <th class="col-sm-1"><input type="text" placeholder="Permissão"  disabled="" class="form-control"></th>

                                        <th class="col-sm-1" style="padding-left: 1.3%;"><input type="text" placeholder="Expulsar"  disabled="" class="form-control nao_desabilitar"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                <?php



                                    if(count($integrantes)>0 && is_array($integrantes)){
                                        foreach ($integrantes as $integrante) {
                                            echo '<tr>';
                                            echo '    <td style="font-size: 20px;">'.$integrante['nome'].'</td>';
                                            echo '    <td style="font-size: 20px;">'.$integrante['username'].'</td>';
                                            echo '    <td style="font-size: 20px;">'.$integrante['email'].'</td>';
                                            echo '    <td class="col-sm-1 text-center">';
                                            echo '       <select style="font-size: 16px;" class="form-control" id="selectkUsuario'.$integrante['user_id'].'">';
                                            echo '          <option '.($integrante['acesso_interno']==1?'selected':'').'>1</option>';
                                            echo '          <option '.($integrante['acesso_interno']==2?'selected':'').'>2</option>';
                                            echo '          <option '.($integrante['acesso_interno']==3?'selected':'').'>3</option>';
                                            echo '       </select>';
                                            echo '    </td>';
                                            echo '    <td class="col-sm-1 text-center" style="padding-top: 1.4%;">';
                                            echo '       <span onclick="kickUsuario('.$info_grupo[0]['group_id'].','.$integrante['user_id'].')"';
                                            echo '       class="hidden-xs showopacity glyphicon glyphicon-remove"';
                                            echo '       style="cursor: pointer; font-size: 16px; color: red;"';
                                            echo '       data-content="Tenha sempre certeza do que está fazendo antes de excluir um usuário." data-toggle="popover" data-placement="right" ';
                                            echo '       id="kickUsuario'.$integrante['user_id'].'">';
                                            echo '       </span>';
                                            echo '    </td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <div>
                    <h2 style="border-bottom: 1px solid #bbb;margin-bottom: 9px;" >Categorias do grupo : </h2>
                    <h3 style="margin-bottom: 9px;" ><a href="<?php echo site_url('pdf?rel=validacoes&g='.$info_grupo[0]['group_id']); ?>" target="_blank">Relatório  <span style="display:inline-block;margin-left:20px;" aria-hidden="true" class="glyphicon glyphicon-save-file"></span></a></h3>
                    <div role="alert" onclick="$(this).fadeOut();" id="successValidaCategoria" style="display:none;cursor:pointer;" class="alert alert-success">Categoria validada com sucesso !</div>
                    <div role="alert" onclick="$(this).fadeOut();" id="failedValidaCategoria" style="display:none;cursor:pointer;" class="alert alert-danger">Categoria não validada !</div>
                    <div role="alert" onclick="$(this).fadeOut();" id="successValidaTopico" style="display:none;cursor:pointer;" class="alert alert-success">Tópico validado com sucesso !</div>
                    <div role="alert" onclick="$(this).fadeOut();" id="failedValidaTopico" style="display:none;cursor:pointer;" class="alert alert-danger">Tópico não validado !</div>
                        <!-- /.panel-body -->
                        <div class="panel-body">
                            <div class="table-responsive" style=" overflow: unset;padding: 1px;">
                                <div id="tableCategoriasList" style="width:100%;">
                                        <div class="list-group-item" style="border-radius: 5px 5px 0px 0px; background-color: rgb(245, 245, 245); border-bottom: 0px none; margin-bottom: -2px;">
                                            <span style="display: inline-block;font-weight:bold;width:45%;">Nome Categoria:</span>
                                            <span style="display: inline-block;font-weight:bold;width:10%">Validado:</span>
                                            <span style="display: inline-block;font-weight:bold;width:27%">Validado por:</span>
                                            <span style="display: inline-block;font-weight:bold;18%">Na data:</span>
                                        </div>
                                    <?php
                                    if (count($categorias_grupo) > 0) {
                                   
                                        foreach ($categorias_grupo as $key => $value) {
                                            echo '<div id="linhaCategoria'.$value['cod_categoria'].'" class="list-group-item linhaCategoria" href="#" onclick="togleCategoriaLine(\''.$value['cod_categoria'].'\')">';
                                            echo '<span coluna="categoria" style="display: inline-block;font-weight:bold;width:45%;">'.$value['dados_categoria']->nome_categoria.':</span>';
                                            if(isset($value['validacao']) && trim($value['validacao']['validado']) == 'S'){
                                                $hide_first = '';
                                                $hide_second = 'hidden';
                                            }
                                            else{
                                                $hide_first = 'hidden';
                                                $hide_second = '';
                                            }
                                            
                                            echo '<span coluna="validado" title="" data-original-title="" onclick="validarCategoria(\''.$value['cod_categoria'].'\',\'N\')" class="'.$hide_first.' hidden-xs showopacity glyphicon glyphicon-ok" style="cursor: pointer; font-size: 16px; color: green; width: 10%; min-width: 16px;text-align:center;" <!-- data-content="Clique para desvalidar categoria." data-toggle="popover" data-placement="top" -->>       </span>';
                                            echo '<span coluna="validado" title="" data-original-title="" onclick="validarCategoria(\''.$value['cod_categoria'].'\',\'S\')" class="'.$hide_second.' hidden-xs showopacity glyphicon glyphicon-remove" style="cursor: pointer; font-size: 16px; color: red; width: 10%; min-width: 16px;text-align:center;" data-content="Clique para validar categoria." data-toggle="popover" data-placement="top">       </span>';
                                            echo '<span coluna="usuario" style="display: inline-block;font-weight:bold;width:27%">'.$value['validacao']['nome_usuario'].'</span>';
                                            if(isset($value['validacao']['data_validacao']) && $value['validacao']['data_validacao'] != '' ){
                                                echo '<span coluna="data" style="display: inline-block;font-weight:bold;18%">'.date ( 'd-m-Y à\s H:i:s' ,strtotime($value['validacao']['data_validacao']) ).'</span>       ';
                                            }
                                            else{
                                                echo '<span coluna="data" style="display: inline-block;font-weight:bold;18%"></span>       ';
                                            }
                                            echo '</div>';
                                            echo '<div id="linhaTopicos'.$value['cod_categoria'].'" class="list-group-item linhaTopicos inativo" href="#"  style="display:none;background-color: #fff;padding: 1px;" >';
                                            if(count($value['topicos'])>0 && is_array($value['topicos'])){
                                            echo    '<table class="table table-hover table-bordered" style="margin-bottom: 0px;">';
                                            echo    '<thead>';
                                            echo        '<tr>';
                                            echo            '<th>Nome do tópico</th>';
                                            echo            '<th>Aprovado</th>';
                                            echo            '<th>Data aprovação</th>';
                                            echo        '</tr>';
                                            echo    '</thead>';
                                            echo        '<tbody>';
                                                foreach ($value['topicos'] as $topico_cat) {
                                                    $hide_first_topico = $topico_cat->aprovado=='S'?'':'hidden';
                                                    $hide_second_topico = $topico_cat->aprovado=='S'?'hidden':'';
                                                    echo        '<tr data-content="Clique no nome para visualizar o tópico." data-toggle="popover" data-placement="top" id="trTopico'.$topico_cat->cod_topico.'" class="trTopico">';
                                                    echo        '<td ><a href="'.$topico_cat->link_topico.'" target="_blanc">'.$topico_cat->nome_topico.'</a></td>';
                                                    echo        '<td>';
                                                    echo            '<span coluna="aprovado" title="" data-original-title="" onclick="aprovaTopico(\''.$topico_cat->cod_topico.'\',\'N\')" class="'.$hide_first_topico.' hidden-xs showopacity glyphicon glyphicon-ok" style="cursor: pointer; font-size: 16px; color: green;min-width: 16px;text-align:center;" data-content="Clique para desaprovar o tópico." data-toggle="popover" data-placement="top">       </span>';
                                                    echo            '<span coluna="aprovado" title="" data-original-title="" onclick="aprovaTopico(\''.$topico_cat->cod_topico.'\',\'S\')" class="'.$hide_second_topico.' hidden-xs showopacity glyphicon glyphicon-remove" style="cursor: pointer; font-size: 16px; color: red;min-width: 16px;text-align:center;" data-content="Clique para aprovar o tópico." data-toggle="popover" data-placement="top">       </span>';
                                                    echo        '</td>';
                                                    if($topico_cat->data_aprovacao == '0000-00-00 00:00:00'){
                                                        echo        '<td coluna="data">Não aprovado.</td>';
                                                    }
                                                    else{
                                                        echo        '<td coluna="data">'.date ( 'd-m-Y à\s H:i:s' ,strtotime($topico_cat->data_aprovacao) ).'</td>';
                                                    }
                                                    echo        '</tr>';
                                                }
                                            echo        '</tbody>';
                                            echo    '</table>';
                                            }
                                            else{
                                                echo    '<span style="display: inline-block; font-weight: bold; width: 100%; padding: 15px; text-align: center; font-size: 18px;">Sem tópicos a aprovar !</span>';
                                            }
                                            echo '</div>';
                                        }
                                    }
                                    else{
                                        echo '<div class="list-group-item" href="#">';
                                        echo    '<span style="display: inline-block;font-weight:bold;width:100%;">Sem categorias a gerenciar !</span>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->

                </div>
                    <!-- search users -->
                    <?php 
                        $all_users['info_grupo'] = $info_grupo[0];
                        $this->load->view('pages/main/search_usuarios',$all_users);
                    ?>
                </div><!-- /.col-xs-12 main -->
            </div>

        </div>

    </div>  

    <script>
        function ativarGrupo(group_id){
            $.ajax({

                url : 'http://www.p2wiki.com.br/grupos/ativarGrupo', 

                cache : false,

                async : false,

                type : 'post',

                dataType : 'json',

                data : 'group_id='+group_id,

                success: function(response,status) {
                    console.log(response.retorno);
                    if(response.retorno == 'true'){
                        $('#glyphicon'+group_id).attr('data-content','Clique para inativar o grupo.');
                        $('#glyphicon'+group_id).css('color','green');
                        $('#glyphicon'+group_id).removeClass('glyphicon-remove');
                        $('#glyphicon'+group_id).addClass('glyphicon-ok');
                        $('#glyphicon'+group_id).attr('onclick','inativarGrupo('+ group_id +')' );
                    }
                    return true;
                }    
            });
        }
        function inativarGrupo(group_id){
            $.ajax({

                url : 'http://www.p2wiki.com.br/grupos/inativarGrupo', 

                cache : false,

                async : false,

                type : 'post',

                dataType : 'json',

                data : 'group_id='+group_id,

                success: function(response,status) {
                    console.log(response.retorno);
                    if(response.retorno == 'true'){
                        $('#glyphicon'+group_id).attr('data-content','Clique para ativar o grupo.');
                        $('#glyphicon'+group_id).css('color','red');
                        $('#glyphicon'+group_id).removeClass('glyphicon-ok');
                        $('#glyphicon'+group_id).addClass('glyphicon-remove');
                        $('#glyphicon'+group_id).attr('onclick','ativarGrupo('+ group_id +')' );
                    }
                    return true;

                }
            });
        }
        $('.filterable .btn-filter').click(function(){

            var $panel = $(this).parents('.filterable'),

            $filters = $panel.find('.filters input'),

            $tbody = $panel.find('.table tbody');

            if ($filters.prop('disabled') == true) {

                $filters.prop('disabled', false);

                $('.nao_desabilitar').prop('disabled', true);

                $filters.first().focus();

                $('#inpTopic').hide();

            } else {

                $filters.val('').prop('disabled', true);

                $tbody.find('.no-result').remove();

                $tbody.find('tr').show();

                $('#inpTopic').show();

            }

        });



        $('.filterable .filters input').keyup(function(e){

            /* Ignore tab key */

            var code = e.keyCode || e.which;

            if (code == '9') return;

            /* Useful DOM data and selectors */

            var $input = $(this),

            inputContent = $input.val().toLowerCase(),

            $panel = $input.parents('.filterable'),

            column = $panel.find('.filters th').index($input.parents('th')),

            $table = $panel.find('.table'),

            $rows = $table.find('tbody tr');

            /* Dirtiest filter function ever ;) */

            var $filteredRows = $rows.filter(function(){

                var value = $(this).find('td').eq(column).text().toLowerCase();

                return value.indexOf(inputContent) === -1;

            });

            /* Clean previous no-result if exist */

            $table.find('tbody .no-result').remove();

            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */

            $rows.show();

            $filteredRows.hide();

            /* Prepend no-result row if all rows are filtered */

            if ($filteredRows.length === $rows.length) {

                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));

            }

        });
              
        $(function () {
            $('[data-toggle="popover"]').popover({trigger:'hover',template:'<div style="width:220px;" class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'});
        });

        $('#tabelaIntegrantes select[id^=selectkUsuario] option').click(function(){
            var usuario = $(this).parent().attr('id');
            var permition = $(this).html().trim();
            updatePermition('<?php echo $info_grupo[0]['group_id']; ?>',usuario,permition);    
        });



        var clickCategoria = false;
        function togleCategoriaLine(cod_categoria){
            if(clickCategoria == false){
                if($('#linhaTopicos'+cod_categoria).css('display')=='none'){
                    $('#linhaTopicos'+cod_categoria).removeClass('inativo');
                    $('#linhaCategoria'+cod_categoria).css('background-color','#f5f5f5');
                    $('#linhaTopicos'+cod_categoria).slideToggle('slow');
                }
                else{
                    $('#linhaTopicos'+cod_categoria).addClass('inativo');
                    $('#linhaCategoria'+cod_categoria).css('background-color','#fff');
                }
                $(".linhaTopicos.inativo").fadeOut('slow');
                $(".linhaTopicos.inativo").prev().css('background-color','#fff');
                $('#linhaTopicos'+cod_categoria).addClass('inativo');
            }
        }
        function validarCategoria(cod_categoria,validado){
            clickCategoria = true;
            //alert('to fazendo');
            $.ajax({

            url : 'http://www.p2wiki.com.br/categoria/validaCategoria', 

            cache : false,

            async : false,

            type : 'post',

            dataType : 'json',

            data : 'cod_categoria='+cod_categoria+'&validado='+validado,

            success: function(response,status) {
                //console.log(response.retorno);
                //console.log(validado);
                //console.log($('#linhaTopicos'+cod_categoria+'[coluna=data]'));

                if(response.resultado == 'true'){
                    if(validado=='N'){
                        $('#linhaCategoria'+cod_categoria+' [coluna=data]').html('');
                        $('#linhaCategoria'+cod_categoria+' [coluna=usuario]').html('');
                        $('#linhaCategoria'+cod_categoria+' [coluna=validado].glyphicon-ok').addClass('hidden');
                        $('#linhaCategoria'+cod_categoria+' [coluna=validado].glyphicon-remove').removeClass('hidden');
                    }
                    else{
                        $('#linhaCategoria'+cod_categoria+' [coluna=data]').html(response.data);
                        $('#linhaCategoria'+cod_categoria+' [coluna=usuario]').html(response.usuario);
                        $('#linhaCategoria'+cod_categoria+' [coluna=validado].glyphicon-remove').addClass('hidden');
                        $('#linhaCategoria'+cod_categoria+' [coluna=validado].glyphicon-ok').removeClass('hidden');
                    }
                    $('#successValidaCategoria').html(response.retorno);
                    $('#successValidaCategoria').fadeIn('slow');
                }
                else{
                    $('#failedValidaCategoria').html(response.retorno);
                    $('#failedValidaCategoria').fadeIn('slow');
                }
                return true;
            }    
        });
            setTimeout(function(){
                clickCategoria = false;
            },300);
        }

        function aprovaTopico(cod_topico,aprovado){
            $.ajax({

            url : 'http://www.p2wiki.com.br/categoria/aprovaTopico', 

            cache : false,

            async : false,

            type : 'post',

            dataType : 'json',

            data : 'cod_topico='+cod_topico+'&aprovado='+aprovado,

            success: function(response,status) {
                console.log(response.retorno);
                //console.log(aprovado);
                //console.log($('#linhaTopicos'+cod_categoria+'[coluna=data]'));
                
                if(response.resultado == 'true'){
                    if(aprovado=='N'){
                        $('#trTopico'+cod_topico+' [coluna=data]').html('Não aprovado.');
                        $('#trTopico'+cod_topico+' [coluna=aprovado].glyphicon-ok').addClass('hidden');
                        $('#trTopico'+cod_topico+' [coluna=aprovado].glyphicon-remove').removeClass('hidden');
                    }
                    else{
                        $('#trTopico'+cod_topico+' [coluna=data]').html(response.data);
                        $('#trTopico'+cod_topico+' [coluna=aprovado].glyphicon-remove').addClass('hidden');
                        $('#trTopico'+cod_topico+' [coluna=aprovado].glyphicon-ok').removeClass('hidden');
                    }
                    $('#successValidaTopico').html(response.retorno);
                    $('#successValidaTopico').fadeIn('slow');
                }
                else{
                    $('#failedValidaTopico').html(response.retorno);
                    $('#failedValidaTopico').fadeIn('slow');
                }
                
                return true;
            }    
        });
            
        }
    </script>







  



