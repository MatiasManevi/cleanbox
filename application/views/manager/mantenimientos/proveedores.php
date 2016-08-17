<div id="lista_proov">
    <div style="margin-top:20px;margin-bottom: 2px;" class="actions_container">
        <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Proveedor" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
        <select style="height: 34px" id="areas_select" onchange="filter_prov($(this).val())">
            <option value="Area" selected="selected">Filtre por rubro</option>
            <option value="Plomero">Plomero</option>
            <option value="Carpintero">Carpintero</option>
            <option value="Refrigeracion">Refrigeracion</option>
            <option value="Persianas">Persianas</option>
            <option value="Vidriero">Vidriero</option>
            <option value="Ascensores">Ascensores</option>
            <option value="Pintor">Pintor</option>
            <option value="Escribano">Escribano</option>
            <option value="Abogado">Abogado</option>
            <option value="Agrimensor">Agrimensor</option>
            <option value="Contador">Contador</option>
            <option value="Techistas">Techistas</option>
            <option value="Cerrajeros">Cerrajeros</option>
            <option value="Electricista">Electricista</option>
            <option value="Gasista">Gasista</option>
            <option value="Albañil">Albañil</option>
            <option value="Aire Acond.">Aire Acond.</option>
        </select>
        <a style=" margin-top: 8px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
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
            foreach ($proveedores as $row) {
                echo '<tr class="reg_' . $row->prov_id . '">';
                if ($row->prov_bussy == 0) {
                    $disp = 'Libre';
                } else {
                    $disp = 'En tarea';
                }
                echo '<td>' . $row->prov_name . '</td>';
                echo '<td>' . $row->prov_tel . '</td>';
                echo '<td>' . $row->prov_email . '</td>';
                echo '<td>' . $row->prov_nota . '</td>';
                echo '<td>' . $row->prov_domicilio . '</td>';
                echo '<td>' . $disp . '</td>';
                echo '<td>';

                echo '<input type="hidden" class="_name" value="' . $row->prov_name . '">';
                echo '<a id="elegir" href="javascript:;" class="glyphicon glyphicon-ok" onclick="chooseProv(this)" ></a>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>
</div>
<script>
    function chooseProv(that){
        var name = $(that).parents('tr').find('._name').val();
        var id_prov = $('._prov_to_choose').attr('data-id');
        $('#'+id_prov).val(name);
        $('#back_fader').hide();
        $('#popup').hide();
    }
    
    function filter_prov(rubro){
        var params = {
            'rubro': rubro,
            'table':'proveedores_pop'
        };
        $.ajax({
            url : BASE_URL + "manager/filter_prov",
            type:'POST',
            data: params,
            dataType: 'json',
            success:function(R){
                eval(R.js);
                if(R.html != ''){
                    $('#lista_proov').html(R.html);
                }
            }
        });
    }
    
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>proveedores_pop/prov_id/prov_id','','#lista_proov');
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
                            $('#lista_proov').html(R.html);
                        }
                    }
                });
            }
        });
    });   
</script>