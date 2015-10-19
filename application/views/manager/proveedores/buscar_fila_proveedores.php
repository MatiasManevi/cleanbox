<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Clientes') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Proveedores</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Proveedor  -->
    <div class="tab-pane fade in" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran proveedores de servicios de mantenimiento de inmuebles</label>
            </div>
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_proveedores') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <input name="prov_id" type="hidden" value="<?= (isset($row) && $row->prov_id ) ? $row->prov_id : '' ?>"/>
            <input value="<?= (isset($row) && $row->prov_name ) ? $row->prov_name : '' ?>" type="text" id="prov_name" name="prov_name" class="form-control ui-autocomplete-input"  placeholder="Nombre" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Contacto</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 83%;"></span>
                <input value="<?= (isset($row) && $row->prov_tel ) ? $row->prov_tel : '' ?>" type="text" id="prov_tel" name="prov_tel" class="form-control ui-autocomplete-input" placeholder="Telefono" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->prov_email ) ? $row->prov_email : '' ?>" type="text" id="prov_email"  name="prov_email" class="form-control ui-autocomplete-input"  placeholder="Email" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Domicilio</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 83%;"></span>
                <input value="<?= (isset($row) && $row->prov_domicilio ) ? $row->prov_domicilio : '' ?>" type="text" name="prov_domicilio" class="form-control ui-autocomplete-input" placeholder="Domicilio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <span style="margin-top: 13px;margin-bottom: 13px;clear: both;float: left;width: 100%;border: 1px solid;color: gray;"></span>
            </div>
            <textarea style="clear:both;width: 35%;float: left;" type="text" placeholder="Comentarios" name="client_comentario"><?= (isset($row) && $row->client_comentario ) ? $row->client_comentario : '' ?></textarea>
            <div style="clear: both;float: left; margin-top: 6px;">
                <input value="<?= (isset($row) && $row->prov_nota ) ? $row->prov_nota : '' ?>" type="text" id="prov_nota"  name="prov_nota" class="form-control ui-autocomplete-input"  placeholder="Calificación" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div> 
            <div style="clear: both;float: left; margin-top: 6px;">
                <span>Agregar Areas</span>     
                <select id="areas_select" onchange="addArea($('#areas_select option:selected').html())">
                    <option value="Area" selected="selected">Area</option>
                    <option value="Plomero">Plomero</option>
                    <option value="Electricista">Electricista</option>
                    <option value="Gasista">Gasista</option>
                    <option value="Albañil">Albañil</option>
                    <option value="Aire Acond.">Aire Acond.</option>
                </select>
                <div id="areas">
                    <?php
                    $added = '';
                    if (count($areas) > 0) {
                        foreach ($areas->result() as $row) {
                            ?>
                            <span id="area_<?php echo $row->area_area ?>" class="area">
                            <?php echo $row->area_area ?>
                                <a onclick="deleteArea('<?php echo $row->area_area ?>')" title="Eliminar" href="javascript:;" class="_close">X</a>
                            </span>
                            <?php $added .= $row->area_area . '-' ?> 
                        <?php } ?>
                <?php } ?>
                </div>
                <input value="<?php echo $added ?>" type="hidden" name="areas" id="areas_added"/>
            </div> 
            <div id="com_display">
                <span></span>
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 15px;clear: both;float: left;line-height: 0;margin-top: 15px;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
        </form>

    </div>

    <div class="tab-pane fade in active" id="lista">
        <div style="margin-top:20px;margin-bottom: 2px;" class="actions_container">
            <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; " aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Proveedor" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
            <a style=" margin-top: 8px;" id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
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
    </div>
</div>
<script>
    function addArea(area){
        if(area != 'Area'){
            var exists = false;
            var areas = $('#areas');
            $('.area').each(function(){
                if($(this).attr('id') == 'area_'+area){
                    exists = true;
                }
            });
            if(!exists){   
                var areas_added = $('#areas_added');
                var areas_ = areas_added.val();
                var new_area = $('<span id="area_'+area+'" class="area">"');
                var action = "deleteArea('"+area+"')";
                var close = $('<a onclick="'+action+'" title="Eliminar" href="javascript:;" class="_close">');
                close.html('X');
                new_area.html(area);
                new_area.append(close);   
                areas.append(new_area);  
                areas_ = areas_ + '-' + area;
                areas_added.val(areas_);
            }    
        }
    }
    
    function deleteArea(area){
        $('.area').each(function(){
            if($(this).attr('id') == 'area_'+area){
                $(this).remove();
            }
        });
        var areas_added = $('#areas_added');
        var areas_ = areas_added.val();
        areas_ = areas_.replace('-'+area,'');
        areas_added.val(areas_);
    }
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>proveedores/prov_id/prov_name','','.contenedor_centro');
    }
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    }) 
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
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */      
    $('#prov_name').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $('#prov_tel').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
</script>