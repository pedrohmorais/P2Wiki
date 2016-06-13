 <!-- ckeditor --> <?   $data_user = $this->session->userdata('data_user');$data_user = $data_user[0];  //die(print_r($data_user)); ?> 

<script src="http://www.p2wiki.com.br/application/third_party/plugins/ckeditor/ckeditor.js"></script>

<style>

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
#tabelaCategorias tbody tr:hover{

    background-color: #D3DCE3;

}
[data-toggle="modal"]:hover{
    background-color: #D3DCE3;
}

</style>    

<script>

    

</script>

<body style="overflow-x:hidden !important;">

    <div class="page-container">

            <!-- menu -->

            <?php $this->load->view('templates/main/menu'); ?>

        <div class="container">

            <div class="row row-offcanvas row-offcanvas-left">
                <div class="col-xs-12 col-sm-12" >
                    <h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" class="col-sm-12">Informações do grupo <?php echo $info_grupo[0]['descricao']; ?> : </h2>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Cliente Responsável:</span><span><?php echo $info_grupo[0]['cod_cliente'].' - '.$info_grupo[0]['nome_cliente']; ?></span>
                    <br>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Quantidade de integrantes: </span><span><?php echo $info_grupo[0]['usuarios']; ?></span>
                    <br>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Limite de integrantes: </span><span><?php echo $info_grupo[0]['limite']; ?></span>
                    <br>
                    <span style="margin-left: 2%; display: inline-block; font-size: 18px; font-weight: bold;width: 27%;" class="form-control-static">Ativo:</span><span class="glyphicon glyphicon-<?php  echo $info_grupo[0]['ativo']==1?'ok" aria-hidden="true" style="color: green;':'remove" aria-hidden="true" style="color: red;'; ?>font-size: 16px; "></span>
                    <h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;" class="col-sm-12"></h2>
                </div>
                <div class="col-xs-12 col-sm-12" >
                    <div style="width: 100%; height: auto;padding:5px;">
                        <div class="list-group">
                            <a href="#" class="list-group-item disabled">
                                Significado das "Permissões" do grupo.
                            </a>
                            <span href="#" class="list-group-item">Elas determinam qual o acesso que o usuário tem no grupo.</span>
                            <span href="#" class="list-group-item">Elas são válidas apenas para este grupo.</span>
                            <span class="list-group-item">Clique sobre elas caso tenha dúvidas sobre seu sgnificado.</span>
                            <span style="cursor:pointer;" class="list-group-item" data-target="#permition1Modal" data-toggle="modal"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="font-size: 10px; margin-right: 10px;"></span>Permissão Nível 1</span>
                            <span style="cursor:pointer;" class="list-group-item" data-target="#permition2Modal" data-toggle="modal"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="font-size: 10px; margin-right: 10px;"></span>Permissão Nível 2</span>
                            <span style="cursor:pointer;" class="list-group-item" data-target="#permition3Modal" data-toggle="modal"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="font-size: 10px; margin-right: 10px;"></span>Permissão Nível 3</span>
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
                                        <span style="margin-right:20px;" aria-hidden="true" class="glyphicon glyphicon-arrow-right"></span> Usuarios nível 1 são em geral pessoas que precisam apenas ter acesso de leitura aos conhecimentos transmitidos por um grupo.
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

                                <h3 class="panel-title"><span style="margin-right:10px;" aria-hidden="true" class="glyphicon glyphicon-user"></span> Integrantes do grupo : </h3>

                                <div class="pull-right">

                                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

                                </div>

                            </div>
                            <table class="table" id="tabelaCategorias">
                                <thead>
                                    <tr class="filters">
                                        <th class="col-sm-7"><input type="text" disabled="" placeholder="Nome do Integrante" class="form-control"></th>

                                        <th><input type="text" disabled="" placeholder="Username" class=" form-control"></th>

                                        <th><input type="text" disabled="" placeholder="Email" class=" form-control"></th>

                                        <th><input type="text" placeholder="Permissão"  disabled="" class="form-control"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                <?php



                                    if(count($integrantes)>0 && is_array($integrantes)){
                                        foreach ($integrantes as $integrante) {
                                            echo '<tr>';
                                            echo '    <td>'.$integrante['nome'].'</td>';
                                            echo '    <td>'.$integrante['username'].'</td>';
                                            echo '    <td>'.$integrante['email'].'</td>';
                                            echo '    <td class="col-sm-1 text-center">'.$integrante['acesso_interno'].'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            //$('[data-toggle="popover"]').popover({trigger:'hover',template:'<div style="width:220px;" class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'});
        });

    </script>







  



