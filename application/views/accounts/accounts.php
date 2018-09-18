<h2><?php echo t('Cuentas Corrientes') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nueva</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Cuentas Corrientes</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Cuenta  -->
    <div class="tab-pane fade in active _add" id="add">
        <div class="section_description">
            <label>En este formulario se registran las ctas. ctes. de Propietarios</label>
        </div>
        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveAccount') ?>', this);return false;" enctype="multipart/form-data"> 
            <input required title="El campo se autocompleta con los Clientes cargados hasta el momento, de lo contrario se creara un Cliente nuevo con el nombre ingresado" type="text" name="cc_prop" class="form-control ui-autocomplete-input section_input _general_letters_input_control" id="cc_prop" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Caja Principal: se registran movimientos de Alquileres" type="text" name="cc_saldo" class="form-control ui-autocomplete-input section_input _general_amount_input_control" id="cc_saldo" placeholder="Saldo Cta. Principal" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Caja Secundaria: se registran movimientos de servicios y otros" type="text" name="cc_varios" class="form-control ui-autocomplete-input section_input _general_amount_input_control" id="cc_varios" placeholder="Saldo Cta. Secundaria" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input readonly title="Prestamos: monto que el propietario debe a la inmobiliaria" type="text" name="loans" class="form-control ui-autocomplete-input section_input _general_amount_input_control" id="loans" placeholder="Prestamos" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input id="cc_id" name="cc_id" type="hidden"/>
            <input id="client_id" name="client_id" type="hidden"/>
            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('cuentas_corrientes');">Resetear campos</a>
        </form>
    </div>

    <!--  Lista de Cuentas Corrientes -->
    <div class="tab-pane fade _list_entities" id="list">
        <div class="filter_container _cuentas_corrientes_filter">
            <input class="form-control ui-autocomplete-input  _search_input filter_input" type="text" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Cta. Cte." name="cc_prop">
            <a onclick="general_scripts.refreshList('cuentas_corrientes', 'cc_id', 'cc_prop', 'cuenta corriente')" class="refresh glyphicon glyphicon-refresh" href="javascript:;" title="Recargar lista"></a>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>

<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('cuentas_corrientes','cc_id','cuenta corriente','cc_prop');
    });
</script>