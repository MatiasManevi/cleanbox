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
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php
    if ($mantenimientos->num_rows() > 0) {
        foreach ($mantenimientos->result() as $row) {
            if ($row->mant_prioridad == 1) {
                $prioridad = 'Alta';
            } elseif ($row->mant_prioridad == 2) {
                $prioridad = 'Media';
            } else {
                $prioridad = 'Baja';
            }
            if ($row->mant_status == 1) {
                $status = 'Creada';
            } elseif ($row->mant_status == 2) {
                $status = 'Asignada y en marcha';
            } else {
                $status = 'Terminada';
            }
            echo '<tr class="reg_' . $row->mant_id . '">';
            echo '<td>' . $row->mant_domicilio . '</td>';
            echo '<td>' . $row->mant_prop . '</td>';
            echo '<td>' . $row->mant_inq . '</td>';
            echo '<td>' . $row->mant_prov_1.' '.$row->mant_prov_2.' '.$row->mant_prov_3 . '</td>';
            echo '<td>' . $prioridad . '</td>';
            echo '<td>' . $status . '</td>';
            echo '<td>';

            echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_mantenimientos/' . $row->mant_id) . '\')"></a> | ';
            echo '<a href="javascript:;" class="glyphicon glyphicon-print" onclick="request_post(\'' . site_url('manager/reporte_mantenimiento/' . $row->mant_id) . '\')"></a> | ';
            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->mant_id . '\',\'' . site_url('manager/del_mantenimientos/' . $row->mant_id) . '\')"></a>  ';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>

<script>
    function filtrar(){
        var desde = $('#desde').val() != '' ? $('#desde').val() : 0;
        var hasta = $('#hasta').val() != '' ? $('#hasta').val() : 0;
        var prop = $('#mant_prop_list').val() != '' ? $('#mant_prop_list').val() : 0;
        var inq = $('#mant_inq_list').val() != '' ? $('#mant_inq_list').val() : 0;
        var prov = $('#mant_prov_list').val() != '' ? $('#mant_prov_list').val() : 0;
        request('<?= site_url() . 'filtrar_mantenimiento/' ?>/'+desde+'/'+hasta+'/'+prop+'/'+inq+'/'+prov,'','.contenedor_centro');
    }
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>mantenimientos/mant_id/mant_id','','.contenedor_centro');
    }    
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