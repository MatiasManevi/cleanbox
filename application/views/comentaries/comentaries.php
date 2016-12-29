<h2><?php echo t('Comentarios') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Comentarios</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Comentario  -->
    <div class="tab-pane fade in active _add" id="add">
        <div class="section_description">
            <label>Formulario para guardar comentarios sobre alquileres y demas, acerca de un Propietario en particular</label>
        </div>
        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveComentary') ?>', this);return false;" enctype="multipart/form-data"> 
            <input required title="Propietario" name="com_prop" id="com_prop" type="text" placeholder="Propietario" class="form-control ui-autocomplete-input section_input _general_letters_input_control" autocomplete="off" role="textbox" aria-haspopup="true">              
            <input required title="Domicilio de propiedad" name="com_dom" id="com_dom" type="text" placeholder="Domicilio de propiedad" class="form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-haspopup="true">    
            <textarea required title="Comentarios" class="section_area form-control" id="com_com" name="com_com" placeholder="Comentarios"></textarea>
            <input id="prop_id" type="hidden" name="prop_id" >
            <input id="cc_id" type="hidden" name="cc_id">
            <input id="com_date" type="hidden" name="com_date"/>
            <input id="com_mes" type="hidden" name="com_mes"/>
            <input id="com_ano" type="hidden" name="com_ano"/>
            <input id="com_id" type="hidden" name="com_id"/>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('comentarios');">Resetear campos</a>
        </form>
    </div>

    <!--  Lista de Comentarios  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _comentarios_filter">
            <form action="javascript:;" onsubmit="general_scripts.filterByValues(this);" enctype="multipart/form-data">  
                <input name="propietary" title="Propietario" placeholder="Propietario" type="text" class="form-control filter_input _filter_propietary">
                <input autocomplete="off" name="from" title="Desde" placeholder="Desde" type="text" class="form-control filter_input _datepicker_filter">
                <input autocomplete="off" name="to" title="Hasta" placeholder="Hasta" type="text" class="form-control filter_input _datepicker_filter">      
                <input name="table" type="hidden" value="comentarios">      
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a onclick="general_scripts.refreshList('comentarios', 'com_id', 'com_prop', 'comentario')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
            </form>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>
<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('comentarios','com_id','comentario','com_prop');
    });
</script>