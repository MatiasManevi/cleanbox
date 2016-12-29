<h2><?php echo t('Conceptos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Conceptos</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Concepto  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>Formulario para registrar los Conceptos que se usaran en Creditos (Entradas) y Debitos (Salidas)</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveConcept') ?>', this);return false;" enctype="multipart/form-data"> 
            <input type="hidden" id="conc_id" name="conc_id"/>
            <input required type="text" id="conc_desc" name="conc_desc" class="form-control ui-autocomplete-input section_input _general_letters_input_control"  placeholder="Concepto" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <div class="section_selects">
                <label>Tipo de Concepto</label>
                <select class="form-control ui-autocomplete-input" id="conc_tipo" name="conc_tipo">
                    <option class="form-control ui-autocomplete-input" value="Entrada">Entrada</option>
                    <option class="form-control ui-autocomplete-input" value="Salida">Salida</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Cuenta</label>
                <select id="conc_cc" name="conc_cc" title="Cuenta en la cual trabajara este concepto. Ej: Concepto como Expensas en Secundaria, Rendicion en Principal" class="form-control ui-autocomplete-input">
                    <option class="form-control ui-autocomplete-input" value="cc_saldo">Principal</option>
                    <option class="form-control ui-autocomplete-input" value="cc_varios">Secundaria</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Percibe Interes</label>
                <select id="interes_percibe" name="interes_percibe" title="Define si por este concepto se generaran Intereses" class="form-control ui-autocomplete-input">
                    <option class="form-control ui-autocomplete-input" value="0">No</option>
                    <option class="form-control ui-autocomplete-input" value="1">Si</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Percibe Gestion de Cobro</label>
                <select id="gestion_percibe" name="gestion_percibe" title="Define si por este concepto se generara Gestion de Cobro para la Inmobiliaria" class="form-control ui-autocomplete-input">
                    <option class="form-control ui-autocomplete-input" value="0">No</option>
                    <option class="form-control ui-autocomplete-input" value="1">Si</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Percibe IVA</label>
                <select id="iva_percibe" name="iva_percibe" title="Define si por este concepto se generara y registrara IVA" class="form-control ui-autocomplete-input">
                    <option class="form-control ui-autocomplete-input" value="0">No</option>
                    <option class="form-control ui-autocomplete-input" value="1">Si</option>
                </select>
            </div> 
            <?php // if (User::codeControl()) { ?>
            <!--                <div class="section_selects">
                                <label>Control Autorizacion</label>
                                <select class="form-control ui-autocomplete-input" id="conc_control" name="conc_control" title="Si se necesitara autorizar un debito de mas de $1000 por este concepto">
                                    <option class="form-control ui-autocomplete-input" value="0">No</option>
                                    <option class="form-control ui-autocomplete-input" value="1">Si</option>
                                </select>
                            </div>-->
            <?php // } ?>
            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('conceptos');">Resetear campos</a>
        </form>

    </div>

    <!--  Lista de Conceptos  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _conceptos_filter">
            <input class="form-control ui-autocomplete-input _search_input filter_input" type="text"  aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Concepto">
            <a onclick="general_scripts.refreshList('conceptos', 'conc_id', 'conc_desc', 'concepto')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
        </div>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>
<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('conceptos','conc_id','concepto','conc_desc');
    });
</script>