<h2><?php echo t('Proveedores') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Proveedores</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Proveedor  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran proveedores de servicios de mantenimiento de inmuebles</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveProvider') ?>', this);return false;" enctype="multipart/form-data"> 

            <input type="hidden" id="prov_id" name="prov_id"/>
            <input type="text" required id="prov_name" title="Nombre completo" name="prov_name" class="form-control ui-autocomplete-input section_input _general_letters_input_control"  placeholder="Nombre completo" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" required id="prov_tel" title="Telefono" name="prov_tel" class="form-control ui-autocomplete-input section_input" placeholder="Telefono" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="prov_email"  title="Email" name="prov_email" class="form-control ui-autocomplete-input section_input"  placeholder="Email" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="prov_domicilio" title="Domicilio" name="prov_domicilio" class="form-control ui-autocomplete-input section_input" placeholder="Domicilio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <input type="hidden" id="nota_id" name="nota_id"/>
            <input type="hidden" id="nota_prov_id" name="nota_prov_id"/>
            <input type="text" id="nota_garantia" name="nota_garantia" title="Garantia" placeholder="Garantia" class="_eval_param _general_number_input_control form-control ui-autocomplete-input section_input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="nota_exp" name="nota_exp" title="Experiencia" placeholder="Experiencia" class="_eval_param _general_number_input_control form-control ui-autocomplete-input section_input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="nota_timing" name="nota_timing" title="Tiempo de respuesta" placeholder="Tiempo de respuesta" class="_eval_param _general_number_input_control form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="nota_presup" name="nota_presup" title="Presupuesto" placeholder="Presupuesto" class="_eval_param _general_number_input_control form-control ui-autocomplete-input section_input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="nota_trust" name="nota_trust" title="Confianza" placeholder="Confianza" class="_eval_param _general_number_input_control form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="nota_calidad" name="nota_calidad" title="Calidad" placeholder="Calidad" class="_eval_param _general_number_input_control form-control ui-autocomplete-input section_input"  autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input type="text" id="nota_total" name="nota_total" title="Nota total" placeholder="Nota total" class="form-control ui-autocomplete-input section_input" readonly placeholder="Calificación" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <select class="form-control ui-autocomplete-input section_input" id="areas_select" onchange="provider.addArea($('#areas_select option:selected').html())">
                <option value="Area" selected="selected">Seleccione sus áreas</option>
                <?php foreach ($providers_rols as $provider_rol) { ?>
                    <option value="<?php echo $provider_rol['rol']; ?>"><?php echo $provider_rol['rol']; ?></option>
                <?php } ?>
            </select>

            <div class="_areas areas">
            </div>

            <input type="hidden" name="areas" class="_areas_added"/>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('proveedores');">Resetear campos</a>

        </form>

    </div>

    <!--  Lista de Proveedores  -->
    <div class="tab-pane fade _list_entities" id="list">
        <div class="filter_container _proveedores_filter">
            <select class="form-control ui-autocomplete-input filter_input" onchange="general_scripts.filterByValue($(this).val(), 'proveedores', 'area_area')">
                <option value="Area" selected="selected">Filtre por rubro</option>
                <?php foreach ($providers_rols as $provider_rol) { ?>
                    <option value="<?php echo $provider_rol['rol']; ?>"><?php echo $provider_rol['rol']; ?></option>
                <?php } ?>
            </select>
            <input class="form-control ui-autocomplete-input _search_input filter_input" type="text" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Proovedor">
            <a onclick="general_scripts.refreshList('proveedores', 'prov_id', 'prov_name', 'proveedor')" class="refresh glyphicon glyphicon-refresh" href="javascript:;" title="Recargar lista"></a>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>
<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('proveedores','prov_id','proveedor','prov_name');
    });
</script>