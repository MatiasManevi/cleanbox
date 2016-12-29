<h2><?php echo t('Areas de Proveedores') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Areas</a></li>
</ul>

<div class="tab-content section">
    <!--  Crear Area de Proveedor  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran areas de proveedores de servicios de mantenimiento</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveProviderRol') ?>', this);return false;" enctype="multipart/form-data"> 

            <input type="hidden" id="id" name="id"/>
            <input type="text" id="rol" name="rol" required class="form-control ui-autocomplete-input section_input _general_letters_input_control"  title="Area" placeholder="Area" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('providers_rols');">Resetear campos</a>

        </form>

    </div>

    <!--  Lista de Areas  -->
    <div class="tab-pane fade _list_entities" id="list">
        <div class="filter_container _providers_rols_filter">
            <input class="form-control ui-autocomplete-input _search_input filter_input" type="text" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Area">
            <a onclick="general_scripts.refreshList('providers_rols', 'id', 'rol', 'area')" class="refresh glyphicon glyphicon-refresh" href="javascript:;" title="Recargar lista"></a>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>