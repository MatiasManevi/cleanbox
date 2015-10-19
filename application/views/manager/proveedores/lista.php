<div style="margin-top:20px;margin-bottom: 2px;" class="actions_container">
    <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Proveedor" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
    <a style=" margin-top: 8px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
</div>
<table class="table table-hover">
    <tr>    
        <th>Nombre</th>
        <th>Telefono</th>
        <th>Email</th>    
        <th>Calificación</th>
        <th>Domicilio</th>
        <th>Acciones</th>
    </tr>
    <?
    if ($proveedores->num_rows() > 0) {
        foreach ($proveedores->result() as $row) {
            echo '<tr class="reg_' . $row->prov_id . '">';
            echo '<td>' . $row->prov_name . '</td>';         
            echo '<td>' . $row->prov_tel . '</td>';
            echo '<td>' . $row->prov_email . '</td>';
            echo '<td>' . $row->prov_nota . '</td>';
            echo '<td>' . $row->prov_domicilio . '</td>';
            echo '<td>';

            echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_proveedores/' . $row->prov_id) . '\')"></a> | ';
            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->prov_id . '\',\'' . site_url('manager/del_proveedores/' . $row->prov_id) . '\')"></a>  ';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>

<script>
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>proveedores/prov_id/prov_name','','.contenedor_centro');
    }    
    $(function(){
        $('#auto_personas1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/proveedores' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/proveedores/"+ui.item.id,
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
                    url : BASE_URL + "manager/buscar_fila/proveedores/",
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