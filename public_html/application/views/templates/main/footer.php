<script type="text/javascript">$('.page-container').css('min-height','720px')</script>
<script src="/application/third_party/js/bootstrap.min.js"></script>   
<!-- <script src="/application/third_party/js/bootstrap.js"></script>  -->
<script src="/application/third_party/js/jquery.maskedinput.min.js"></script>

   
   
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo site_url('application/third_party/tables_and_charts/bower_components/metisMenu/dist/metisMenu.min.js'); ?>"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo site_url('application/third_party/tables_and_charts/bower_components/raphael/raphael-min.js'); ?>"></script>
    <script src="<?php echo site_url('application/third_party/tables_and_charts/bower_components/morrisjs/morris.min.js'); ?>"></script>
    
    <!-- <script src="<?php echo site_url('application/third_party/tables_and_charts/js/morris-data.js'); ?>"></script> -->

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo site_url('application/third_party/tables_and_charts/dist/js/sb-admin-2.js'); ?>"></script>

    <!-- datepicker -->
    <script src="<?php echo site_url('application/third_party/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script>  
    <script src="<?php echo site_url('application/third_party/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js'); ?>"></script>  

    <!-- complexify -->
    <script src="<?php echo site_url('application/third_party/plugins/senha/complexify.js'); ?>"></script>  

    
    <!-- DataTables Responsive js -->
    <script src="<?php echo site_url('application/third_party/tables_and_charts/bower_components/datatables/media/js/jquery.dataTables.min.js'); ?>"></script> 
    <script src="<?php echo site_url('application/third_party/tables_and_charts/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js'); ?>"></script> 

    <!-- Jcrop Images -->
    <script src="<?php echo site_url('application/third_party/plugins/Jcrop/js/jquery.Jcrop.js'); ?>"></script> 
    <script src="<?php echo site_url('application/third_party/plugins/Jcrop/js/jquery.Jcrop.min.js'); ?>"></script> 
    
<script>
    $('[class^=col-sm-]').addClass('bgTransparent');
    $('[class^=col-xs-]').addClass('bgTransparent');
    $('[class^=col-md-]').addClass('bgTransparent');
    $('[class^=col-lg-]').addClass('bgTransparent');
    $('#divBackgroundPrincipal').css('opacity','0.4');
    $('#divBackgroundPrincipal').css('z-index','-1');
    //arruma a barra esquerda onresize
    function arrumamenuesquerdo(e){
        /*
        if(e < 1530){
            $('#divLiMenuEsquerdo').attr('style','margin-left: -160px;z-index:1;');
                $('#divLiMenuEsquerdo').on('mouseenter',function(){
                $('.container').css('padding-left','11em');
                $('#divLiMenuEsquerdo').attr('style','margin-left: 0px;');
            });
                $('#divLiMenuEsquerdo').on('mouseleave',function(){
                $('#divLiMenuEsquerdo').attr('style','margin-left: -160px;z-index:1;');
                var cont = 10;
                var padd = setInterval(function(){
                    if(cont == 0){
                        clearInterval(padd);
                        return true;
                    }
                    $('.container').css('padding-left',cont+'em');
                    cont--;
                },20);
            });
        }
        else{
            $('#divLiMenuEsquerdo').attr('style','');
            $('#divLiMenuEsquerdo').on('mouseenter',function(){
                $('#divLiMenuEsquerdo').attr('style','margin-left: 0px;');
            });
            $('#divLiMenuEsquerdo').on('mouseleave',function(){
                $('#divLiMenuEsquerdo').attr('style','margin-left: 0px;');
            });
        }
        */
        //console.log(e);
    }
    arrumamenuesquerdo($(window).width());
    $(window).on('resize',function(e){
        var x = $(window).width();
        arrumamenuesquerdo(x);
    });
    $(document).ready(function(){
        //arruma seta do datepicker
        $('.datepicker .prev i').addClass('glyphicon glyphicon-chevron-left');
        $('.datepicker .prev i').removeClass('icon-arrow-left');
        $('.datepicker .next i').removeClass('icon-arrow-right');
        $('.datepicker .next i').addClass('glyphicon glyphicon-chevron-right');
    });

</script>
<!-- Footer -->
    <div style="position:absolute;width: 100% ! important; margin-top: 100px; background-color: rgb(21, 138, 196); color: rgb(248, 248, 248); height: 50px;" id="footerP2wiki">
        <center>
            <!--
            <div style="float:left;">
                <img src="images/LogoCursive.jpg"/>
            </div>
        -->
            <table style="padding:5px;">
                <tbody>
                    <tr>
                    <!--
                    <td style="padding: 15px;">Login</td>
                    <td>-</td>
                    <td style="padding: 15px;">Cadastre-se</td>
                    <td>-</td>
                    <td style="padding: 15px;">Sobre nós</td>
                    <td>-</td>
                    <td style="padding: 15px;">Contato</td>
                    -->
                        <td style="padding: 15px;">P2wiki - Caso tenha alguma dúvida entre em contato conosco pelo email administracao@p2wiki.com.br.</td>
                    </tr>
                </tbody>
            </table>
            
        </center>
    </div>
<!-- /Footer -->