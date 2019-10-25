<h2><?php echo t('Mantenimientos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Mantenimientos</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Mantenimiento  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran mantenimientos y refacciones a inmuebles</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveMaintenance') ?>', this);return false;" enctype="multipart/form-data"> 

            <input type="hidden" id="mant_id" name="mant_id"/>

            <input required title="Domicilio" type="text" id="mant_domicilio" name="mant_domicilio" class="form-control ui-autocomplete-input section_input"  placeholder="Domicilio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">            
            <input title="Propietario" type="text" id="mant_prop" name="mant_prop" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Inquilino" type="text" id="mant_inq" name="mant_inq" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" >

            <input title="Proveedor 1" onfocus="maintenance.chooseProv($(this));" type="text" id="mant_prov_1" name="mant_prov_1" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Proveedor 1" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Proveedor 2" onfocus="maintenance.chooseProv($(this));" type="text" id="mant_prov_2" name="mant_prov_2" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Proveedor 2" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Proveedor 3" onfocus="maintenance.chooseProv($(this));" type="text" id="mant_prov_3" name="mant_prov_3" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Proveedor 3" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <textarea required title="Descripcion" class="section_area form-control" id="mant_desc" name="mant_desc" type="text" placeholder="Descripción de la tarea"></textarea>     

            <input title="Presupuesto" id="mant_monto" type="text" name="mant_monto" class="form-control ui-autocomplete-input section_input _general_amount_input_control" placeholder="Presupuesto ($)" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Fecha limite de terminacion" name="mant_date_deadline" id="mant_date_deadline" class="form-control ui-autocomplete-input section_input" autocomplete="off" placeholder="Fecha limite" type="text">
            <input title="Fecha de terminacion" name="mant_date_end" id="mant_date_end" class="form-control ui-autocomplete-input section_input" placeholder="Fecha de fin" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" >

            <select title="Prioridad" id="mant_prioridad" name="mant_prioridad" class="form-control ui-autocomplete-input section_input">
                <option value="1">Alta</option>
                <option value="2">Media</option>
                <option value="3">Baja</option>
            </select>

            <select title="Status" id="mant_status" name="mant_status" class="form-control ui-autocomplete-input section_input">
                <option value="1">Creada</option>
                <option value="2">Asignada y en marcha</option>
                <option value="3">Terminada</option>
            </select>

            <input title="Calificacion de tarea" name="mant_calif" id="mant_calif"  class="form-control ui-autocomplete-input section_input _general_amount_input_control"  placeholder="Calificación de tarea" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;width: 400px;float: left;">
            

            <!-- imagenes -->
            <input class="_pictures" name="pictures" type="hidden">

            <div class="col-lg-12" style="margin-top: 20px;width: 70%;">
                <!-- Our markup, the important part here! -->
                <div id="image_uploader" data-folder="manteinments/" class="dm-uploader p-5">
                    <h3 class="mb-5 mt-5 text-muted">Arrastra tus imagenes aqui</h3>

                    <div class="btn btn-primary btn-block mb-5">
                        <span>Abrir buscador de imagenes</span>
                        <input type="file" multiple/>
                    </div>
                </div><!-- /uploader -->
                <div class="col-lg-12">
                    <div class="card h-100">
                        <div class="card-header" style="text-align: center;">
                            Lista de imagenes
                        </div>

                        <ul class="list-unstyled p-2 d-flex flex-column col image_listing" id="files">
                            <li class="text-muted text-center empty">Aun no hay imagenes</li>
                        </ul>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('mantenimientos');">Resetear campos</a>
        </form>

    </div>

    <!--  Lista de Mantenimientos  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _mantenimientos_filter">
            <form action="javascript:;" onsubmit="general_scripts.filterByValues(this);" enctype="multipart/form-data">  
                <input title="Propietario" name="propietary" placeholder="Propietario" type="text" id="mant_prop_list" class="form-control filter_input ui-autocomplete-input _filter_propietary">
                <input title="Inquilino" name="renter" placeholder="Inquilino" type="text" id="mant_inq_list" class="form-control filter_input ui-autocomplete-input _filter_renter">
                <input title="Proveedor" name="provider" placeholder="Proveedor" type="text" id="mant_prov_list" class=" form-control filter_input ui-autocomplete-input _filter_provider">
                <input autocomplete="off" name="from" title="Desde (fecha limite terminacion)" placeholder="Desde" type="text" class="form-control filter_input _datepicker_filter">
                <input autocomplete="off" name="to" title="Hasta (fecha limite terminacion)" placeholder="Hasta" type="text" class="form-control filter_input _datepicker_filter">      
                <input name="table" type="hidden" value="mantenimientos">      
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a onclick="general_scripts.refreshList('mantenimientos', 'mant_id', 'mant_id', 'mantenimiento')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
            </form>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>
<span hidden="true" class="_prov_to_choose" data-id=""></span>
<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('mantenimientos','mant_id','mantenimiento','mant_id');
    });
</script>

<?= $this->load->view('picture_uploader_script_tag'); ?>