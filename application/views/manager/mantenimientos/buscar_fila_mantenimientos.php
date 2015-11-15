<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Mantenimientos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Mantenimientos</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Mantenimiento  -->
    <div class="tab-pane fade" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran mantenimientos y refacciones a inmuebles</label>
            </div>
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_mantenimientos') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Partes intervinientes</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 64%;"></span>
                <input name="mant_id" type="hidden" value="<?= (isset($row) && $row->mant_id ) ? $row->mant_id : '' ?>"/>
                <input value="<?= (isset($row) && $row->mant_domicilio ) ? $row->mant_domicilio : '' ?>" type="text" id="mant_domicilio" name="mant_domicilio" class="form-control ui-autocomplete-input"  placeholder="Domicilio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">            
                <input value="<?= (isset($row) && $row->mant_prop ) ? $row->mant_prop : '' ?>" type="text" id="mant_prop" name="mant_prop" class="form-control ui-autocomplete-input" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->mant_inq ) ? $row->mant_inq : '' ?>" type="text" id="mant_inq" name="mant_inq" class="form-control ui-autocomplete-input" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->mant_prov ) ? $row->mant_prov : '' ?>" type="text" id="mant_prov" name="mant_prov" class="form-control ui-autocomplete-input" placeholder="Proveedor" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Razon de eleccion de Proveedor</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 45%;"></span>
                <textarea class="desc_mant" name="mant_why_prov" type="text" placeholder="Por qué razón elegiste el proveedor?. Ej: Buena calificacion, recomendación de terceros, no habia otro libre, etc." ><?= (isset($row) && $row->mant_why_prov ) ? $row->mant_why_prov : '' ?></textarea>     
            </div>         
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Datos del mantenimiento</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 57%;"></span>
                <textarea class="desc_mant" name="mant_desc" type="text" placeholder="Descripción de la tarea" ><?= (isset($row) && $row->mant_desc ) ? $row->mant_desc : '' ?></textarea>     
                <input value="<?= (isset($row) && $row->mant_monto ) ? $row->mant_monto : '' ?>" type="text" name="mant_monto" class="form-control ui-autocomplete-input" placeholder="Presupuesto ($)" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Fecha límite</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 78%;"></span>
                <input value="<?= (isset($row) && $row->mant_date_deadline ) ? $row->mant_date_deadline : '' ?>" name="mant_date_deadline" id="mant_date_deadline" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" class="form-control ui-autocomplete-input" placeholder="Fecha limite" type="text"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            </div>   
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Fecha de terminación</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 63%;"></span>
                <input value="<?= (isset($row) && $row->mant_date_end ) ? $row->mant_date_end : '' ?>"name="mant_date_end" id="mant_date_end"  class="form-control ui-autocomplete-input" placeholder="Fecha de fin" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;clear:both">Prioridad</label><span style="border: 1px solid;color: gray;float: right;margin-top: 16px;width: 83%;"></span>
                <select name="mant_prioridad" style="clear: both;float: left;margin-top: 5px;">
                    <option <?= (isset($row) && $row->mant_prioridad == 1 ) ? 'selected="selected"' : '' ?> value="1">Alta</option>
                    <option <?= (isset($row) && $row->mant_prioridad == 2 ) ? 'selected="selected"' : '' ?> value="2">Media</option>
                    <option <?= (isset($row) && $row->mant_prioridad == 3 ) ? 'selected="selected"' : '' ?> value="3">Baja</option>
                </select>
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;clear:both">Status</label><span style="border: 1px solid;color: gray;float: right;margin-top: 16px;width: 88%;"></span>
                <select name="mant_status" style="clear: both;float: left;margin-top: 5px;">
                    <option <?= (isset($row) && $row->mant_status == 1 ) ? 'selected="selected"' : '' ?> value="1">Creada</option>
                    <option <?= (isset($row) && $row->mant_status == 2 ) ? 'selected="selected"' : '' ?> value="2">Asignada y en marcha</option>
                    <option <?= (isset($row) && $row->mant_status == 3 ) ? 'selected="selected"' : '' ?> value="3">Terminada</option>
                </select>
            </div>
            <div class="domicilio">
                <label style="clear: both;margin-top: 10px;color: gray;float: left;font-size: 14px;">Calificación de tarea</label><span style="border: 1px solid;color: gray;float: right;margin-top: 21px;width: 63%;"></span>
                <input value="<?= (isset($row) && $row->mant_calif ) ? $row->mant_calif : '' ?>"name="mant_calif" id="mant_calif"  class="form-control ui-autocomplete-input"  placeholder="Calificación de tarea" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            </div>
            <div id="com_display">
                <span></span>
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 150px;clear: both;float: left;line-height: 0;margin-top: 15px;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <div id="back_fader">
                <div style="width: auto;margin-left: 15px;margin-right: 15px" id="popup">
                </div>
            </div>
        </form>

    </div>

    <div class="tab-pane fade in active" id="lista">
        <div style="margin-top: 28px;" class="actions_container">
            <div class="field span3 buscar">
                <input style="width: 20%;;height: 26px;" placeholder="Propietario" type="text" id="mant_prop_list" class="ui-autocomplete-input">
                <input style="width: 20%;height: 26px;" placeholder="Inquilino" type="text" id="mant_inq_list" class="ui-autocomplete-input">
                <input style="width: 20%;height: 26px;" placeholder="Proveedor" type="text" id="mant_prov_list" class="ui-autocomplete-input">
                <input style="width: 10%;height: 26px;"placeholder="Desde" type="text" id="desde">
                <input style="width: 10%;height: 26px;"placeholder="Hasta" type="text" id="hasta">    
                <a href="javascript:;" onclick="filtrar()" class="btn btn-primary">Filtrar</a>
                <a id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
            </div>
        </div>
        <table class="table table-hover">
            <tr>    
                <th>Domicilio</th>
                <th>Propietario</th>
                <th>Inquilino</th>    
                <th>Proveedor</th>
                <th>Prioridad</th>
                <th>Fecha limite</th>
                <th>Acciones</th>
            </tr>
            <?
            if ($mantenimientos->num_rows() > 0) {
                foreach ($mantenimientos->result() as $row) {
                    if ($row->mant_prioridad == 1) {
                        $prioridad = 'Alta';
                    } elseif ($row->mant_prioridad == 2) {
                        $prioridad = 'Media';
                    } else {
                        $prioridad = 'Baja';
                    }
                    echo '<tr class="reg_' . $row->mant_id . '">';
                    echo '<td>' . $row->mant_domicilio . '</td>';
                    echo '<td>' . $row->mant_prop . '</td>';
                    echo '<td>' . $row->mant_inq . '</td>';
                    echo '<td>' . $row->mant_prov . '</td>';
                    echo '<td>' . $prioridad . '</td>';
                    echo '<td>' . $row->mant_date_deadline . '</td>';
                    echo '<td>';

                    echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_mantenimientos/' . $row->mant_id) . '\')"></a> | ';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->mant_id . '\',\'' . site_url('manager/del_mantenimientos/' . $row->mant_id) . '\')"></a>  ';
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
    function choose_prov(){
        request('<?= site_url() . 'choose_prov/' ?>','','#popup');
        popup();
    }
    function filtrar(){
        var desde = $('#desde').val() != '' ? $('#desde').val() : 0;
        var hasta = $('#hasta').val() != '' ? $('#hasta').val() : 0;
        var prop = $('#mant_prop_list').val() != '' ? $('#mant_prop_list').val() : 0;
        var inq = $('#mant_inq_list').val() != '' ? $('#mant_inq_list').val() : 0;
        var prov = $('#mant_prov_list').val() != '' ? $('#mant_prov_list').val() : 0;
        request('<?= site_url() . 'filtrar_mantenimiento/' ?>/'+desde+'/'+hasta+'/'+prop+'/'+inq+'/'+prov,'','.contenedor_centro');
    }
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */      
    $('#mant_prop, #mant_inq, #mant_prov').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $('#mant_prov').on('keypress keyup focus click',function(){
        choose_prov() 
    });
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    }); 
    $( "#mant_date_deadline" ).datepicker({
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
    $( "#mant_date_end" ).datepicker({
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
    
    var auto_opt_dom = {
        source: "<?php echo site_url('manager/autocomplete') . '/propiedades/' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/"+ui.item.id,
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $(this).val(R.html);
                    }
                }
            });
        } 
    };
    var key_opt_dom = function(){
        if ($(this).val().length == 1){
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/",
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $(this).html(R.html);
                    }
                }
            });
        }
    }
    $("[id^=mant_domicilio]").each(function(){
        $(this).autocomplete(auto_opt_dom);
        $(this).keydown(key_opt_dom);   
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
        $('#mant_inq').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_inq').html(R.html);
                        }
                    }
                });
            }
        });
        $("#mant_inq").keydown(function(){
            if ($("#mant_inq").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_inq').html(R.html);
                        }
                    }
                });
            }
        });
    });
    
    $(function(){
        $('#mant_prop').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prop').html(R.html);
                            $('#mant_prop').blur();
                        }
                    }
                });
            }
        });
        $("#mant_prop").keydown(function(){
            if ($("#mant_prop").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prop').html(R.html);
                        }
                    }
                });
            }
        });
    });
    
    $(function(){
        $('#mant_inq_list').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_inq_list').html(R.html);
                        }
                    }
                });
            }
        });
        $("#mant_inq_list").keydown(function(){
            if ($("#mant_inq_list").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_inq_list').html(R.html);
                        }
                    }
                });
            }
        });
    });
    
    $(function(){
        $('#mant_prop_list').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prop_list').html(R.html);
                            $('#mant_prop_list').blur();
                        }
                    }
                });
            }
        });
        $("#mant_prop_list").keydown(function(){
            if ($("#mant_prop_list").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prop_list').html(R.html);
                        }
                    }
                });
            }
        });
    });
    
    
    $(function(){
        $('#mant_prov_list').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/proveedores' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/proveedores_pop/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prov_list').html(R.html);
                        }
                    }
                });
            }
        });
        $("#mant_prov_list").keydown(function(){
            if ($("#mant_prov_list").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/proveedores_pop/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prov_list').html(R.html);
                        }
                    }
                });
            }
        });
    }); 
</script>