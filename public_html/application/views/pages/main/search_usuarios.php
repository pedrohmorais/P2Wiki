<style type="text/css">
#tabelaAllUsers tbody tr:hover{
    background-color: #D3DCE3;
}
/* COLOCA  A SETINHA NA DATATABLE */
table.dataTable thead .sorting::after {
    color: rgba(50, 50, 50, 0.5);
    content: "";
    float: right;
    font-family: fontawesome;
}
table.dataTable thead .sorting_asc::after {
    content: "";
    float: right;
    font-family: fontawesome;
}
table.dataTable thead .sorting_desc::after {
    content: "";
    float: right;
    font-family: fontawesome;
}
</style>
<h2 style="border-bottom: 1px solid #bbb;margin-bottom:50px;"> Adicionar Usuário ao grupo <?php echo $info_grupo['descricao'] ?></h2>
    <div class="alert alert-success" style="display:none;cursor:pointer;" id="successAddUsuarioGrupo" onclick="$(this).fadeOut();" role="alert">Grupo cadastrado com sucesso !</div>
    <div class="alert alert-danger" style="display:none;cursor:pointer;" id="failedAddUsuarioGrupo" onclick="$(this).fadeOut();" role="alert">Grupo não cadastrado !</div>
    <form method="POST" action="/grupos/inserir_usuario_grupo" id="form_inserir_usuario_grupo">
    <table>
        <tr>
            <td style="padding:10px;" class="form-inline">
                <label>Nome do usuário: </label>
            </td>   
            <td class="form-inline" style="width:75%;">
                <input id='addUsuarioGrupo_nome' type="text" style="width:100%;" class="form-control" disabled />
            </td>   
        </tr>
        <tr>
            <td style="padding:10px;" class="form-inline">
                <label>Username: </label>
            </td>   
            <td class="form-inline" style="width:75%;">
                <input id='addUsuarioGrupo_username' type="text" style="width:100%;" class="form-control" disabled />
            </td>   
        </tr>
        <tr>
            <td style="padding:10px;" class="form-inline">
                <label>Nivel de acesso (Permissão) ao grupo: </label>
            </td>   
            <td class="form-inline">
                <select name="acesso_interno" id="addUsuarioGrupo_acesso_interno" class="form-control pull-right" style="min-width: 50%;">
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                </select>
            </td> 
        </tr>
        <tr>
            <td class="form-inline" style="width:75%;">
                <input type="hidden" style="width:100%;" class="form-control" name="user_id" id="addUsuarioGrupo_user_id" />
            </td>  
            <td class="form-inline" style="width:75%;">
                <input type="hidden" style="width:100%;" class="form-control" id="addUsuarioGrupo_group_id" name="group_id" value="<?php echo $info_grupo['group_id']; ?>" />
                <input type="hidden" style="width:100%;" class="form-control" id="addUsuarioGrupo_cod_cliente" name="cod_cliente" value="<?php echo $info_grupo['cod_cliente']; ?>" />
            </td>   
        </tr>
        <tr>
            <td colspan="2" class="form-inline" style="padding-top: 20px;padding-bottom: 20px;">
                <input class="btn btn-default" type="submit" style="width: 100%;" value="Adicionar Usuário">
            </td>
        </tr>
    </table>    
<h2 style="border-bottom: 1px solid #bbb;padding-bottom: 9px;"></h2>
<?php
    echo '<script>';
    if ($this->session->flashdata('sg_insert_usuario_grupo')) {
        echo    '$("#successAddUsuarioGrupo").html("'.$this->session->flashdata('sg_insert_usuario_grupo').'");';
        echo    '$("#successAddUsuarioGrupo").fadeIn();';
    }
    elseif ($this->session->flashdata('sg_erro_insert_usuario_grupo')) {
        echo    '$("#failedAddUsuarioGrupo").html("'.$this->session->flashdata('sg_erro_insert_usuario_grupo').'");';
        echo    '$("#failedAddUsuarioGrupo").fadeIn();';
    }
    echo '</script>';
?>
<div class="panel panel-primary filterable">
    <div class="panel-heading">
        <h3 style="font-weight: bold;" class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true" style="margin-right:10px;"></span> Pesquisar usuários : </h3>
    </div>
    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="tabelaAllUsers" style="padding: 20px;">
        <thead>
            <tr >
                <th>Nome</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_users as $user) {
                echo '<tr style="cursor:pointer;" onclick="selecionarUsuarioID(\''.$user['user_id'].'\',\'#addUsuarioGrupo_user_id\');selecionarUsuarioNome(\''.$user['nome'].'\',\'#addUsuarioGrupo_nome\');selecionarUsuarioUsername(\''.$user['username'].'\',\'#addUsuarioGrupo_username\')">';
                echo '    <td>';
                echo '       <span>';
                echo            $user['nome'];
                echo '       </span>';
                echo '    </td>';
                echo '    <td>';
                echo '       <span>';
                echo            $user['username'];
                echo '       </span>';
                echo '    </td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
                            
<script>
    function selecionarUsuarioID(id,callback){
        $(callback).val(id);
    }
    function selecionarUsuarioNome(id,callback){
        $(callback).val(id);
    }
    function selecionarUsuarioUsername(id,callback){
        $(callback).val(id);
    }
    $('#form_inserir_usuario_grupo').submit(function(){
        var mensagem = '';
        if($('#addUsuarioGrupo_acesso_interno').val().trim()==''){
            mensagem = 'Preencha o campo de acesso ao grupo !';
        }
        if($('#addUsuarioGrupo_user_id').val().trim()==''){
            mensagem = 'Preencha o campo de id do usuário !';
        }
        if($('#addUsuarioGrupo_group_id').val().trim()==''){
            mensagem = 'Preencha o campo de id do grupo !';
        }
        if($('#addUsuarioGrupo_cod_cliente').val().trim()==''){
            mensagem = 'Preencha o campo de código do cliente !';
        }
        if(mensagem.trim()!=''){
            $("#failedAddUsuarioGrupo").html(mensagem);
            $("#failedAddUsuarioGrupo").fadeIn();
            return false;
        }
    });
    $(document).ready(function() {
        $('#tabelaAllUsers').DataTable({
            responsive: true,
            padding:20,
            language: 
            {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_  Resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar &nbsp;",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            }
        });
        $('#tabelaAllUsers_wrapper').attr('style','padding:20px;');
        $('#tabelaAllUsers_info').parent().after('<br>');
        $('#tabelaAllUsers_length').parent().removeClass('col-sm-6');
        $('#tabelaAllUsers_length').parent().addClass('col-sm-3');
        $('#tabelaAllUsers_filter').parent().removeClass('col-sm-6');
        $('#tabelaAllUsers_filter').parent().addClass('col-sm-3');


    } );

</script>                   
   





  



