<div class="actions_container">
    <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; margin-top: 4px;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Concepto" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
    <a style=" margin-top: 12px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
</div>
<table class="table table-hover">
    <tr>    
        <th>Concepto</th>
        <th>Tipo</th>
        <th>Cuenta</th>
        <th>Control Autorización</th>
        <th>Acciones</th>
    </tr>
    <?
    if ($conceptos->num_rows() > 0) {
        foreach ($conceptos->result() as $row) {
            echo '<tr class="reg_' . $row->conc_id . '">';
            echo '<td>' . $row->conc_desc . '</td>';
            echo '<td>' . $row->conc_tipo . '</td>';
            echo '<td>' . ($row->conc_cc == 'cc_saldo' ? 'Cta. Principal' : 'Cta. Secundaria') . '</td>';
            echo '<td>' . ($row->conc_control == 1 ? 'Si' : 'No') . '</td>';
            echo '<td>';

            echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_conceptos/' . $row->conc_id) . '\')"></a> | ';
            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->conc_id . '\',\'' . site_url('manager/del_conceptos/' . $row->conc_id) . '\')"></a>  ';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>



<script> 
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>conceptos/conc_id/conc_desc','','.contenedor_centro');
    }
    $(function(){
        $('#auto_personas1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/conceptos' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/conceptos/"+ui.item.id,
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
        $("#auto_personas1").keydown(function(){
            if ($("#auto_personas1").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/conceptos/",
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
