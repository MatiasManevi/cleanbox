<div style="margin-top:20px;margin-bottom: 2px;" class="actions_container">
    <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Cliente" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
    <a style=" margin-top: 8px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
</div>
<table class="table table-hover">
    <tr>    
        <th>Nombre</th>
        <th>Email</th>
        <th>Telefono Fijo</th>
        <th>Celular</th>
        <th>C.U.I.T</th>
        <th>Domicilio</th>
        <th>Acciones</th>
    </tr>
    <?
    if ($clientes->num_rows() > 0) {
        foreach ($clientes->result() as $client) {
            echo '<tr class="reg_' . $client->client_id . '">';
            echo '<td>' . $client->client_name . '</td>';
            echo '<td>' . $client->client_email . '</td>';
            echo '<td>' . $client->client_tel . '</td>';
            echo '<td>' . $client->client_celular . '</td>';
            echo '<td>' . $client->client_cuit . '</td>';
            echo '<td>' . $client->client_calle . ' ' . $client->client_nro_calle . '</td>';
            echo '<td>';

            echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_clientes/' . $client->client_id) . '\')"></a> | ';
            echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $client->client_id . '\',\'' . site_url('manager/del_clientes/' . $client->client_id) . '\')"></a>  ';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>

<script>
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>clientes/client_id/client_name','','.contenedor_centro');
    }    
    $(function(){
        $('#auto_personas1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/clientes/"+ui.item.id,
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
                    url : BASE_URL + "manager/buscar_fila/clientes/",
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