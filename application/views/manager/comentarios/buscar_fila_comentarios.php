<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Comentarios') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Comentarios</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cliente  -->
    <div class="tab-pane fade" id="agregar">
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_comentarios') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div style="margin-top:0px;" class="comments">
                <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
                    <div class="porc_data">
                        <label style="font-size: 15.2px;">Formulario para guardar comentarios sobre alquileres y demas, acerca de un Propietario en particular</label>
                    </div>
                </div>
                <input onkeyup="validar_persona('con_prop')" onblur="validar_persona('con_prop')" id="con_prop" value="<?= (isset($row) && $row->com_prop ) ? $row->com_prop : '' ?>" type="text" name="com_prop" class="form-control ui-autocomplete-input"  placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">              
                <input id="auto_cc_id" name="auto_cc_id" type="hidden"/>
                <input id="auto_inm_id" name="auto_inm_id" type="hidden"/>
                <input onkeyup="validar_inm('prop_domi')" onblur="validar_inm('prop_domi')" id="prop_domi" type="text" name="com_dom" value="<?= (isset($row) && $row->com_dom ) ? $row->com_dom : '' ?>"class="form-control ui-autocomplete-input"  placeholder="Domicilio de propiedad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 9px;clear: both;margin-bottom: 15px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">    
                <textarea name="com_com" id="coment" placeholder="Comentarios"><?= (isset($row) && $row->com_com ) ? $row->com_com : '' ?></textarea>

                <input id="com_date" type="hidden" value="<?= (isset($row) && $row->com_date ) ? $row->com_date : date('d-m-Y') ?>" name="com_date"/>
                <input id="com_mes" type="hidden" value="<?= (isset($row) && $row->com_mes ) ? $row->com_mes : date('m') ?>" name="com_mes"/>
                <input id="com_ano" type="hidden" value="<?= (isset($row) && $row->com_ano ) ? $row->com_ano : date('Y') ?>" name="com_ano"/>
                <input id="com_id" type="hidden" value="<?= (isset($row) && $row->com_id ) ? $row->com_id : '' ?>" name="com_id"/>
                <button class="btn btn-primary" id="save_coment" style="float: left; line-height: 0;">Guardar</button>
                <div id="com_display">
                    <span></span>
                </div>
            </div>

        </form>
    </div>

    <div class="tab-pane fade in active" id="lista">
        <div class="actions_container">
            <div class="field span3 buscar" id="mio">
                <div class="alert alert-info">Filtre por Cta Cte. rangos de fecha o la combinacion de ambas</div>
                <input name="cliente" style="height: 26px;" placeholder="Cta. Cte." type="text" value="" id="auto_personas" class="ui-autocomplete-input">
                <input type="hidden" id="cliente"/>
                <input name="desde" placeholder="Desde" type="text" id="desde">
                <input name="hasta" placeholder="Hasta" type="text" id="hasta">      
                <button id="asd1" class="btn btn-primary">Filtrar</button>
                <a id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
            </div>
        </div>
        <table class="table table-hover">
            <tr>    
                <th style="text-align: center !important;">Propietario</th>
                <th style="text-align: center !important;">Fecha del Comentario</th>
                <th style="text-align: center !important;">Comentario</th>
                <th style="text-align: center !important;">Domicilio</th>
                <th style="text-align: center !important;">Acciones</th>
            </tr>
            <?
            if ($comentarios->num_rows() > 0) {
                foreach ($comentarios->result() as $row) {
                    echo '<tr class="reg_' . $row->com_id . '">';
                    echo '<td style="text-align: center !important;">' . $row->com_prop . '</td>';
                    echo '<td style="text-align: center !important;">' . $row->com_date . '</td>';
                    echo '<td style="text-align: center !important;">' . substr($row->com_com, 0, 90) . ' [...]</td>';
                    echo '<td style="text-align: center !important;">' . $row->com_dom . '</td>';
                    echo '<td>';
                    echo '<a style="text-align: center !important;" href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_comentarios/' . $row->com_id) . '\')"></a> | ';
                    echo '<a style="text-align: center !important;" href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->com_id . '\',\'' . site_url('manager/del_comentarios/' . $row->com_id) . '\')"></a>  ';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
            }
            ?>
        </table>
    </div>
</div>
<script>
    $("#asd1").click(function() {      
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var cc = $('#cliente').val();
        var cc = cc.replace(',','.');
        if(cc == '' && hasta != '' && desde != ''){
            request('<?= site_url() . 'filtrar_comentario/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
        }
        if(cc != '' && hasta == '' && desde == ''){
            request('<?= site_url() . 'filtrar_comentario_2/' ?>'+cc,'','.contenedor_centro');
        }
        if(cc != ''){
            if(hasta != '' && desde != ''){
                request('<?= site_url() . 'filtrar_comentario3/' ?>'+cc+'/'+desde+'/'+hasta,'','.contenedor_centro');
            }
        }  
    });
    $('#coment').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && (key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 46 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    function validar_inm(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/prop_id/prop_dom/propiedades/auto_inm_id/'+id_input+'/'+value,'','#auto_inm_id');          
    }
    function validar_persona(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/auto_cc_id/'+id_input+'/'+value,'','#auto_cc_id');          
    }
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>comentarios/com_id','','.contenedor_centro');
    }   
    $(function(){
        $('#con_prop').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_prop').html(R.html);
                            $('#con_prop').blur();
                        }
                    }
                });
            }
        });
        $("#con_prop").keydown(function(){
            if ($("#con_prop").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_prop').html(R.html);
                        }
                    }
                });
            }
        });
    }); 
    $("[id^=auto_personas]").each(function(){
        /* Por cada input #auto_player, ya sea auto_player1, 
         * auto_player2, etc, existe este script universal 
         * que maneja todos y cada uno de los autocomplete
         * de dichos inputs
         * ES UN AUTOCOMPLETE DE MULTIPLES INPUTS */
        
        /* El operador ^ hace las veces de comodin
         * señala que lo que esta a la derecha sea una
         * subcadena del input, para tenerlo en cuenta 
         * en el autocomplete*/
        $(this).autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){    
                            $(this).val(R.html);
                            $('#cliente').val(R.html);
                        }
                    }
                });
            }
        });
        $(this).keydown(function(){
            if ($(this).val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){                          
                            $(this).val(R.html);
                            $('#cliente').val(R.html);
                        }
                    }
                });
            }
        });
        
    });    
    $( "#desde" ).datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)});
    $( "#hasta" ).datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)});
    $(function(){
        $('#prop_domi').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/propiedades/' ?>" ,
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#prop_domi').html(R.html);
                            $('#prop_domi').blur();
                        }
                    }
                });
            }
        });
        $("#prop_domi").keydown(function(){
            if ($("#prop_domi").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#prop_domi').html(R.html);
                        }
                    }
                });
            }
        });
    }); 
</script>