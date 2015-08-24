<div class="actions_container">
    <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; margin-top: 5px;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Propiedades" id="cuentas" class="form-control ui-autocomplete-input" name="cc_prop">
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
        <th>Propietario</th>
        <th>Domicilio</th>
        <th>En contrato con</th>     
        <th>Acciones</th>
    </tr>
    <?
    if ($propiedades->num_rows() > 0) {
        foreach ($propiedades->result() as $row) {
            echo '<tr class="reg_' . $row->prop_id . '">';
            echo '<td>' . $row->prop_prop . '</td>';
            echo '<td>' . $row->prop_dom . '</td>';
            echo '<td>' . ($row->prop_contrato_vigente != '' ? $row->prop_contrato_vigente : 'Libre') . '</td>';
//                echo '<td>' . ($row->prop_enabled == 1 ? 'Si' : 'No') . '</td>';
            echo '<td>';

            echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_propiedad/' . $row->prop_id) . '\')"></a> | ';
            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->prop_id . '\',\'' . site_url('manager/del_propiedad/' . $row->prop_id) . '\')"></a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>

<script>
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>propiedades/prop_id/prop_prop','','.contenedor_centro');
    }
    $(function(){
        $('#cuentas').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_propiedad/cuentas_corrientes/"+ui.item.id,
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
                    url : BASE_URL + "manager/buscar_propiedad/cuentas_corrientes/",
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



