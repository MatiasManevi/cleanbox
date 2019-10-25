<h2><?php echo t('Inspecciones') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nueva</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Inspecciones</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Mantenimiento  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran inspecciones de inmuebles</label>
        </div>
        
        <form class="section_form" style="padding-right: 300px;" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveInspection') ?>', this);return false;" enctype="multipart/form-data"> 

            <div class="form-group col-lg-12">

                <input type="hidden" id="id" name="id"/>
                <input type="hidden" id="property_id" name="property_id">
                <input type="hidden" id="renter_id" name="renter_id">

                <input style="clear: none;width: 49%" required title="Domicilio" name="address" id="address" type="text" class="form-control ui-autocomplete-input section_input" placeholder="Domicilio Inmueble" autocomplete="off" role="textbox">

                <input style="clear: none;width: 49%" required title="Inquilino" name="renter" id="renter" type="text" class="form-control ui-autocomplete-input _general_letters_input_control section_input" placeholder="Inquilino" autocomplete="off" role="textbox">  

                <select style="clear: none;width: 49%" title="Momento de inspección" placeholder="Momento" class="form-control ui-autocomplete-input section_input" id="momentum" name="momentum">
                    <option></option>
                    <option value="1">Previo contrato</option>
                    <option value="2">Durante contrato</option>
                    <option value="3">Post contrato</option>
                </select>

                <input style="clear: none;width: 49%" required title="Fecha de inspección" id="date" type="text" name="date" class="form-control ui-autocomplete-input section_input" placeholder="Fecha de inspección" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" >

                <textarea style="width: 98.5%" required title="Descripción" class="section_area form-control" id="description" name="description" type="text" placeholder="Descripción de la inspección"></textarea> 
            </div>
            
            <!-- imagenes -->
            <input class="_pictures" name="pictures" type="hidden">

            <div class="col-lg-12">
                <!-- Our markup, the important part here! -->
                <div id="image_uploader" data-folder="inspections/" class="dm-uploader p-5">
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
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('inspections');">Resetear campos</a>
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
        general_scripts.getEntitiesOnScrollDown('inspections', 'id', 'inspection', 'id');
    });
</script>

<?= $this->load->view('picture_uploader_script_tag'); ?>