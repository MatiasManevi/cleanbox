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
                echo '<a href="javascript:;" class="glyphicon glyphicon-trash inactive" onclick="del(\'' . $row->cc_id . '\',\'' . site_url('manager/del_cuenta/' . $row->cc_id) . '\')"></a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
    }
    ?>
</table>

<script>
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>cuentas_corrientes/cc_id/cc_prop','','.contenedor_centro');
    }
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



