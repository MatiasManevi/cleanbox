<h2><?php echo t('Propiedades') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nueva</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Propiedades</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Propiedad  -->
    <div class="tab-pane fade in active _add" id="add">
        <div class="section_description">
            <label>En este formulario se registran las propiedades de los propietarios</label>
            <div style="display: none;" class="to_timeline">
                
            </div>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveProperty') ?>', this);return false;" enctype="multipart/form-data"> 
            <input id="prop_id" name="prop_id" type="hidden"/>
            <input id="cc_id" name="cc_id" type="hidden">
            <input id="prop_enabled" name="prop_enabled" type="hidden" value="1">

            <input required title="Propietario" class="form-control ui-autocomplete-input section_input _general_letters_input_control" type="text" name="prop_prop" id="prop_prop" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required title="Domicilio" class="form-control ui-autocomplete-input section_input" type="text" name="prop_dom" id="prop_dom" placeholder="Domicilio de propiedad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <label class="clear_both">En contrato vigente con:</label>
            <input title="Inquilino que ocupa el inmueble actualmente" class="form-control ui-autocomplete-input section_input _general_letters_input_control" type="text" name="prop_contrato_vigente" id="prop_contrato_vigente" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            

            <!-- imagenes -->
            <input class="_pictures" name="pictures" type="hidden">

            <div class="col-lg-12" style="margin-top: -101px;width: 70%;">
                <!-- Our markup, the important part here! -->
                <div id="image_uploader" data-folder="properties/" class="dm-uploader p-5">
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
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('propiedades');">Resetear campos</a>
        </form>

    </div>

    <!--  Lista de Propiedades  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _propiedades_filter">
            <input class="form-control ui-autocomplete-input _search_input filter_input" type="text"  aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar por propietario">
            <a onclick="general_scripts.refreshList('propiedades', 'prop_id', 'prop_prop', 'propiedad')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>

<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('propiedades','prop_id','propiedad','prop_prop');
    });
</script>

<?= $this->load->view('picture_uploader_script_tag'); ?>