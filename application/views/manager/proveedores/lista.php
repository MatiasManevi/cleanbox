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
    if (count($proveedores) > 0) {
        foreach ($proveedores as $row) {
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
        request('<?= site_url() . 'refresh/' ?>proveedores/prov_id/prov_id','','#lista_proov');
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
                            $('#lista_proov').html(R.html);
                        }
                    }
                });
            }
        });
    });   
</script>