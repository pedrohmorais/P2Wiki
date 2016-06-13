 <!-- ckeditor --> <?	$data_user = $this->session->userdata('data_user');$data_user = $data_user[0];	//die(print_r($data_user)); ?> 

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
#tabelaCategorias tr:hover{

    font-size: 20px !important;
    cursor:pointer;
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
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="/grupos">Grupos que administro.</a></li>
                        <li role="presentation" class="active"><a href="/grupos/participante">Grupos que pertenço.</a></li>
                    </ul>           
		        	<div style="width: 100%; height: auto; border-width: medium 1px 1px; border-style: none solid solid; border-color: #ddd; border-image: none;padding:5px;">
                        <div class="panel panel-primary filterable">
                            <div class="panel-heading">

                                <h3 class="panel-title"><span style="margin-right:10px;" aria-hidden="true" class="glyphicon glyphicon-king"></span> Grupos que administro : </h3>

                                <div class="pull-right">

                                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Pesquisar</button>

                                </div>

                            </div>
                            <table class="table" id="tabelaCategorias">
                                <thead>
                                    <tr class="filters">
                                        <th class="col-sm-7"><input type="text" disabled="" placeholder="Nome do grupo" class="form-control"></th>

                                        <th><input type="text" disabled="" placeholder="Integrantes" class="nao_desabilitar form-control text-center"></th>

                                        <th><input type="text" disabled="" placeholder="Limite" class="nao_desabilitar form-control text-center"></th>

                                        <th><input type="text" placeholder="Configurar" id="configGroup" disabled="" class="nao_desabilitar form-control text-center"></th>

                                        <th><input type="text" placeholder="Deletar" id="deleteSpan" disabled="" class="nao_desabilitar form-control text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Grupo 1</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">5</td>
                                    <td class="col-sm-1 text-center">
                                        <a href="grupos/my_owns" style="color: rgb(51, 51, 51);" rel="modal" class="iframe">
                                            <span style="font-size:16px;" class=" hidden-xs showopacity glyphicon glyphicon-cog"></span>
                                        </a>
                                    </td>
                                    <td class="col-sm-1 text-center">
                                        <span style="cursor:pointer;font-size:16px;color:red;" class="hidden-xs showopacity glyphicon glyphicon-remove" onclick="deleteCategoria(1)"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Grupo 1</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">5</td>
                                    <td class="col-sm-1 text-center">
                                        <a href="grupos/my_owns" style="color: rgb(51, 51, 51);" rel="modal" class="iframe">
                                            <span style="font-size:16px;" class=" hidden-xs showopacity glyphicon glyphicon-cog"></span>
                                        </a>
                                    </td>
                                    <td class="col-sm-1 text-center">
                                        <span style="cursor:pointer;font-size:16px;color:red;" class="hidden-xs showopacity glyphicon glyphicon-remove" onclick="deleteCategoria(1)"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div><!-- /.col-xs-12 main -->

			</div>

		</div>

	</div>	

    <script>
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
    	  

    </script>







  



