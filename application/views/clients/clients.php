<h2><?php echo t('Clientes') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Clientes</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Cliente  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran clientes (Propietarios | Inquilinos)</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveClient') ?>', this);return false;" enctype="multipart/form-data"> 

            <input id="client_id" name="client_id" type="hidden"/>
            <input required title="Nombre" type="text" id="client_name" name="client_name" class="form-control ui-autocomplete-input section_input _general_letters_input_control"  placeholder="Nombre" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="C.U.I.T / DNI" type="text" id="client_cuit" name="client_cuit" class="form-control ui-autocomplete-input section_input _general_number_input_control" placeholder="C.U.I.T / DNI" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Razón de vínculo" type="text" id="client_razon_vinculo" name="client_razon_vinculo" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Razón de vínculo" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Telefono Fijo" type="text" id="client_tel" name="client_tel" class="form-control ui-autocomplete-input section_input _general_number_input_control" placeholder="Telefono Fijo" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Celular" type="text" id="client_celular" name="client_celular" class="form-control ui-autocomplete-input section_input _general_number_input_control" placeholder="Celular" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Email" id="client_email" type="text" name="client_email" class="form-control ui-autocomplete-input section_input"  placeholder="Email" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Calle" id="client_calle" type="text" name="client_calle" class="form-control ui-autocomplete-input section_input" placeholder="Calle" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Nro. Calle" id="client_nro_calle" type="text" name="client_nro_calle" class="form-control ui-autocomplete-input section_input _general_number_input_control" placeholder="Nro. Calle" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Piso" type="text" id="client_piso" name="client_piso" class="form-control ui-autocomplete-input section_input" placeholder="Piso" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Depto." type="text" id="client_dto" name="client_dto" class="form-control ui-autocomplete-input section_input" placeholder="Depto." autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Código Postal" id="client_postal" type="text" name="client_postal" class="form-control ui-autocomplete-input section_input _general_number_input_control" placeholder="Código Postal" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Localidad" id="client_localidad" type="text" name="client_localidad" class="form-control ui-autocomplete-input section_input" placeholder="Localidad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input title="Provincia" id="client_provincia" type="text" name="client_provincia" class="form-control ui-autocomplete-input section_input _general_letters_input_control" placeholder="Provincia" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">


            <textarea title="Comentarios" class="section_area form-control" type="text" placeholder="Comentarios" id="client_comentario" name="client_comentario"></textarea>

            <div class="section_selects">
                <label>Categoria</label>
                <select class="form-control ui-autocomplete-input" id="client_categoria" name="client_categoria">
                    <option class="form-control ui-autocomplete-input" value="Propietario">Propietario</option>
                    <option class="form-control ui-autocomplete-input" value="Inquilino">Inquilino</option>
                    <option class="form-control ui-autocomplete-input" value="Garante">Garante</option>
                </select>
            </div> 

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('clientes');">Resetear campos</a>
        </form>
    </div>

    <!--  Lista de Clientes  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _clientes_filter">
            <input class="form-control ui-autocomplete-input _search_input filter_input" type="text"  aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Cliente">
            <a onclick="general_scripts.refreshList('clientes', 'client_id', 'client_name', 'cliente')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>
