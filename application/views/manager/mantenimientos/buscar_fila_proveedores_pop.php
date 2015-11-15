<div style="margin-top:20px;margin-bottom: 2px;" class="actions_container">
    <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Proveedor" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
    <a style=" margin-top: 8px;" id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
</div>
<table class="table table-hover">
    <tr>    
        <th>Nombre</th>
        <th>Telefono</th>
        <th>Email</th>    
        <th>Calificación</th>
        <th>Domicilio</th>
        <th>Disponible</th>
        <th>Elegir</th>
    </tr>
    <?
    if (count($proveedores) > 0) {
        for ($x = 0; $x < count($proveedores); $x++) {
            echo '<tr>';
            if ($proveedores[$x]['prov_bussy'] == 0) {
                $disp = 'Libre';
            } else {
                $disp = 'En tarea';
            }
            echo '<td>' . $proveedores[$x]['prov_name'] . '</td>';
            echo '<td>' . $proveedores[$x]['prov_tel'] . '</td>';
            echo '<td>' . $proveedores[$x]['prov_email'] . '</td>';
            echo '<td>' . $proveedores[$x]['prov_nota'] . '</td>';
            echo '<td>' . $proveedores[$x]['prov_domicilio'] . '</td>';
            echo '<td>' . $disp . '</td>';
            echo '<td>';
            echo '<input type="hidden" id="name" value="' . $proveedores[$x]['prov_name'] . '">';
            echo '<a id="elegir" href="javascript:;" class="glyphicon glyphicon-ok" onclick="chooseProv(this)" ></a>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>
<div style="margin-top:22px;float:left">
    <span id="spans" type="button" class="btn btn-default btn-lg">
        <a style=" margin: 0 auto;" onclick="$('#back_fader').hide();$('#popup').hide()"id="buttons_cli" class="btn" href="javascript:;">Cancelar</a>
    </span>
</div> 

<script>
    function chooseProv(that){
        var name = $(that).parents('tr').find('#name').val();
        $('#mant_prov').val(name);
        $('#back_fader').hide();
        $('#popup').hide();
    }
    
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>proveedores_pop//prov_name','','.list_prov');
    }    
    
    $(function(){
        $('#auto_personas1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/proveedores' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/proveedores_pop/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('.list_prov').html(R.html);
                        }
                    }
                });
            }
        });
        $("#auto_personas1").keydown(function(){
            if ($("#auto_personas1").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/proveedores_pop/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('.list_prov').html(R.html);
                        }
                    }
                });
            }
        });
    });   
</script>