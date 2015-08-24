<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Cuentas Corrientes') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Agregar Nueva</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Cuentas Corrientes</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cuenta  -->
    <div class="tab-pane fade" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran las ctas. ctes. de Propietarios</label>
            </div>
        </div>
        <div id="tooltipProp">
            El campo se autocompleta con los Clientes cargados hasta el momento, de lo contrario se creara un Cliente nuevo con el nombre ingresado
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_cuenta') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <input value="<?= (isset($row) && $row->cc_prop ) ? $row->cc_prop : '' ?>"type="text" name="cc_prop" class="form-control ui-autocomplete-input" id="propietario" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->cc_saldo ) ? $row->cc_saldo : '' ?>"type="text" name="cc_saldo" class="form-control ui-autocomplete-input" id="saldo" placeholder="Saldo Cta Cte" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 5px;clear:both;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->cc_varios ) ? $row->cc_varios : '' ?>"type="text" name="cc_varios" class="form-control ui-autocomplete-input" id="varios" placeholder="Saldo Varios" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 5px;clear:both;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->cc_id ) ? $row->cc_id : '0' ?>"name="cc_id" type="hidden" />
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-top: 6px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <div id="com_display">
                <span></span>
            </div>
        </form>

    </div>
    <!--  Lista de Cuentas  -->
    <div class="tab-pane fade in active" id="lista">
        <div class="actions_container">
            <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; margin-top: 5px;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Cta. Cte." id="cuentas" class="form-control ui-autocomplete-input" name="cc_prop">
            <a style=" margin-top: 12px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
        </div>
        <style>
            .inactive {
                pointer-events: none;
                cursor: default;
            }
        </style>
        <table class="table table-hover">
            <tr>    
                <th>Nombre</th>
                <th>Saldo Cuenta Principal</th>
                <th>Saldo Cuenta Secundaria</th>
                <th>Acciones</th>
            </tr>
            <?
            if ($this->session->userdata('username') == 'admin') {
                if ($cuentas_corrientes->num_rows() > 0) {
                    foreach ($cuentas_corrientes->result() as $row) {
                        echo '<tr class="reg_' . $row->cc_id . '">';
                        echo '<td>' . $row->cc_prop . '</td>';
                        echo '<td>$ ' . $row->cc_saldo . '</td>';
                        echo '<td>$ ' . $row->cc_varios . '</td>';
                        echo '<td>';

                        echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_cuenta/' . $row->cc_id) . '\')"></a> | ';
                        if ($row->cc_prop != 'INMOBILIARIA') {
                            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->cc_id . '\',\'' . site_url('manager/del_cuenta/' . $row->cc_id) . '\')"></a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
                }
            } else {
                if ($cuentas_corrientes->num_rows() > 0) {
                    foreach ($cuentas_corrientes->result() as $row) {
                        echo '<tr class="reg_' . $row->cc_id . '">';
                        echo '<td>' . $row->cc_prop . '</td>';
                        echo '<td>$ ' . $row->cc_saldo . '</td>';
                        echo '<td>$ ' . $row->cc_varios . '</td>';
                        echo '<td>';

                        echo '<a href="javascript:;" class="glyphicon glyphicon-edit inactive" onclick="load_edit(\'' . site_url('manager/load_edit_cuenta/' . $row->cc_id) . '\')"></a> | ';
                        if ($row->cc_prop != 'INMOBILIARIA') {
                            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->cc_id . '\',\'' . site_url('manager/del_cuenta/' . $row->cc_id) . '\')"></a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
                }
            }
            ?>
        </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#propietario').hover(function(){
            $('#tooltipProp').css('display','block');
        },function(){
            $('#tooltipProp').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#tooltipProp').css('left',mx+'px').css('right',my+'px').css('top',-20);
        })  
    });
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>cuentas_corrientes/cc_id/cc_prop','','.contenedor_centro');
    }
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })  
    $(function(){
        $('#propietario').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#propietario').html(R.html);
                        }
                    }
                });
            }
        });
        $("#propietario").keydown(function(){
            if ($("#propietario").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#propietario').html(R.html);
                        }
                    }
                });
            }
        });
    });
    $(function(){
        $('#cuentas').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('.contenedor_centro').html(R.html);
                        }
                    }
                });
            }
        });
        $("#cuentas").keydown(function(){
            if ($("#cuentas").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('').html(R.html);
                        }
                    }
                });
            }
        });
    });
</script>
