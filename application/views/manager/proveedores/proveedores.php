<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Proveedores') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li><a href="#lista" data-toggle="tab">Lista de Proveedores</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Proveedor  -->
    <div class="tab-pane fade in active" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran proveedores de servicios de mantenimiento de inmuebles</label>
            </div>
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_proveedores') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <input name="prov_id" type="hidden" value="<?= (isset($row) && $row->prov_id ) ? $row->prov_id : '' ?>"/>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Contacto</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 83%;"></span>
                <input value="<?= (isset($row) && $row->prov_name ) ? $row->prov_name : '' ?>" type="text" id="prov_name" name="prov_name" class="form-control ui-autocomplete-input"  placeholder="Nombre" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->prov_tel ) ? $row->prov_tel : '' ?>" type="text" id="prov_tel" name="prov_tel" class="form-control ui-autocomplete-input" placeholder="Telefono" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->prov_email ) ? $row->prov_email : '' ?>" type="text" id="prov_email"  name="prov_email" class="form-control ui-autocomplete-input"  placeholder="Email" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Domicilio</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 83%;"></span>
                <input value="<?= (isset($row) && $row->prov_domicilio ) ? $row->prov_domicilio : '' ?>" type="text" name="prov_domicilio" class="form-control ui-autocomplete-input" placeholder="Domicilio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>

            <div class="domicilio">
                <input name="nota_id" type="hidden" value="<?= (!empty($nota) && $nota->nota_id) ? $nota->nota_id : '' ?>"/>
                <label style="color: gray;float: left;font-size: 14px;">Calificación</label><span style="border: 1px solid;color: gray;float: right;margin-bottom: 10px;margin-top: 13px;width: 79%;"></span>
                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Garantia</label>
                    <input value="<?= (!empty($nota) && $nota->nota_garantia) ? $nota->nota_garantia : 0 ?>" type="text" id="nota_garantia"  name="nota_garantia" class="_eval_param form-control ui-autocomplete-input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div>
                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Conocimientos y Exp</label>
                    <input value="<?= (!empty($nota) && $nota->nota_exp) ? $nota->nota_exp : 0 ?>" type="text" id="nota_exp"  name="nota_exp" class="_eval_param form-control ui-autocomplete-input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div>
                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Tiempo de Respuesta.</label>
                    <input value="<?= (!empty($nota) && $nota->nota_timing) ? $nota->nota_timing : 0 ?>" type="text" id="nota_timing"  name="nota_timing" class="_eval_param form-control ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div>
                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Presupuesto</label>
                    <input value="<?= (!empty($nota) && $nota->nota_presup) ? $nota->nota_presup : 0 ?>" type="text" id="nota_presup"  name="nota_presup" class="_eval_param form-control ui-autocomplete-input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div>
                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Confianza</label>
                    <input value="<?= (!empty($nota) && $nota->nota_trust) ? $nota->nota_trust : 0 ?>" type="text" id="nota_trust"  name="nota_trust" class="_eval_param form-control ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div>
                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Calidad de trabajo</label>
                    <input value="<?= (!empty($nota) && $nota->nota_calidad) ? $nota->nota_calidad : 0 ?>" type="text" id="nota_calidad"  name="nota_calidad" class="_eval_param form-control ui-autocomplete-input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div>

                <div class="calif">
                    <label style="color: gray;float: left;font-size: 14px;">Calificacion</label>
                    <input readonly value="<?= (!empty($nota) && $nota->nota_total) ? $nota->nota_total : '' ?>" type="text" id="nota_total"  name="nota_total" class="form-control ui-autocomplete-input"  placeholder="Calificación" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
                </div> 
            </div> 
            <div class="domicilio">
                <label style="margin-right: 5px; color: gray;float: left;font-size: 14px;">Agregar Areas</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 75%;"></span>    
                <select style="clear: both;float: left;margin-top: 5px;" id="areas_select" onchange="addArea($('#areas_select option:selected').html())">
                    <option value="Area" selected="selected">Seleccione un área</option>
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
                <div id="areas">
                    <?php
                    $added = '';
                    if (isset($areas) && count($areas) > 0) {
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
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 150px;clear: both;float: left;line-height: 0;margin-top: 100px;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>

        </form>

    </div>
    <!--  Lista de Proveedores  -->
    <div class="tab-pane fade" id="lista">
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
        <div id="lista_proov">
            <?= isset($lista) ? $lista : '' ?>
        </div>
    </div>
</div>




<script>
    $('._eval_param').on('focus keydown keyup keypress paste', function (key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
  
        var sum = 0;
        $('._eval_param').each(function(){
            var val = parseInt($(this).val());
            if(val){
                sum += val;
            }
        });
        var total = sum / 6;
        total = Math.round(total * 100) / 100;
        
        $('#nota_total').val(total);
    });
    
    function filter_prov(rubro){
        var params = {
            'rubro': rubro
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
    
    function addArea(area){
        if(area != 'Area' && area != 'Seleccione un área'){
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
        areas_ = areas_.replace(area,'');
        areas_added.val(areas_);
    }
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */      
    $('#prov_name').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $('#prov_tel').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    }) 

</script>