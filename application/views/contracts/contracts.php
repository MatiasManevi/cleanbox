<h2><?php echo t('Contratos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Contratos</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Contrato  -->
    <div class="tab-pane fade in active _add" id="add">
        <div class="section_description">
            <label>En este formulario se registran los contratos a gestionar. Los mismos pierden su vigencia en el sistema una vez alcanzada la Fecha de Vencimiento</label>
            <label>Los campos Propietario, Inquilino, Garantes e Inmuebles se autocompletaran con los ya almacenados en el sistema, de lo contrario, se crearán nuevos</label>
            <label>registros de los mismos con la información ingresada</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="contracts.saveContract('<?php echo site_url('saveContract') ?>');return false;" enctype="multipart/form-data"> 

            <input type="hidden" id="con_id" name="con_id">
            <input type="hidden" id="cc_id" name="cc_id">
            <input type="hidden" id="client_id" name="client_id">
            <input type="hidden" id="gar1_id" name="gar1_id">
            <input type="hidden" id="gar2_id" name="gar2_id">
            <input type="hidden" id="prop_id" name="prop_id">

            <input required title="Propietario" id="con_prop" type="text"name="con_prop" class="form-control ui-autocomplete-input _general_letters_input_control clear_both contract_fields" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 400px;">
            <input required title="Inquilino" id="con_inq" type="text" name="con_inq" class="form-control ui-autocomplete-input _general_letters_input_control contract_fields" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 400px;">                     

            <input required title="Garante 1" id="con_gar1" type="text" name="con_gar1" class="form-control ui-autocomplete-input _general_letters_input_control contract_fields clear_both" placeholder="Garante 1" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 400px;">                     
            <input title="Garante 2" id="con_gar2" type="text" name="con_gar2" class="form-control ui-autocomplete-input _general_letters_input_control contract_fields" placeholder="Garante 2" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 400px;">                     

            <input required title="Vencimiento contrato" id="con_venc" type="text" name="con_venc" class="form-control ui-autocomplete-input contract_fields clear_both" placeholder="Fecha Venc." autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 130px;">
            <input required title="Domicilio" id="con_domi" type="text" name="con_domi" class="form-control ui-autocomplete-input contract_fields" placeholder="Domicilio Inmueble" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 265px;">

            <input required id="con_porc" title="Ej: 0.07 para indicar 7% Gestion de cobro" type="text" name="con_porc" class="form-control ui-autocomplete-input _general_amount_input_control contract_fields" placeholder="Gestion de Cobro" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 135px;">
            <input required id="con_punitorio" title="Ej: 0.07 para indicar 7% Porc. punitorio" type="text" name="con_punitorio" class="form-control ui-autocomplete-input _general_amount_input_control contract_fields" placeholder="Interes Punitorio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 135px;">
            <input required id="con_tolerancia" type="text" name="con_tolerancia" class="form-control ui-autocomplete-input _general_number_input_control contract_fields" placeholder="Tolerancia" title="Cantidad de dias que se tolera el atraso de pagos" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="width: 120px;">

            <div class="contract_selects">
                <div class="select">
                    <label>Honorarios</label>
                    <input required id="honorary_cuotes" title="Cantidad de cuotas en que se pagan Honorarios" type="text" name="honorary_cuotes" class="form-control ui-autocomplete-input clear_both _general_number_input_control contract_fields" placeholder="Cantidad de cuotas" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="">
                    <input id="honorary_cuotes_payed" title="Cuotas abonadas de Honorarios" type="text" name="honorary_cuotes_payed" class="form-control ui-autocomplete-input _general_number_input_control contract_fields" placeholder="Cantidad de Cuotas Abonadas" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="">
                    <input id="honorary_cuotes_price" title="Precio de la cuota" type="text" name="honorary_cuotes_price" class="form-control ui-autocomplete-input _general_number_input_control contract_fields" placeholder="Precio de la cuota" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="">
                </div>

                <div class="select">
                    <label>Deposito de Garantia</label>
                    <input required id="warranty_cuotes" title="Cantidad de cuotas en que se paga Deposito de Garantia" type="text" name="warranty_cuotes" class="form-control ui-autocomplete-input _general_number_input_control contract_fields" placeholder="Cantidad de cuotas" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="">
                    <input id="warranty_cuotes_payed" title="Cuotas abonadas de Deposito de Garantia" type="text" name="warranty_cuotes_payed" class="form-control ui-autocomplete-input _general_number_input_control contract_fields" placeholder="Cantidad de Cuotas Abonadas" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="">
                    <input id="warranty_cuotes_price" title="Precio de la cuota" type="text" name="warranty_cuotes_price" class="form-control ui-autocomplete-input _general_number_input_control contract_fields" placeholder="Precio de la cuota" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="">
                </div>
            </div>

            <div class="contract_selects">
                <div class="select">
                    <label>Activo</label>
                    <select class="form-control ui-autocomplete-input" id="con_enabled" name="con_enabled">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="select">
                    <label>Tipo Contrato</label>
                    <select class="form-control ui-autocomplete-input" id="con_tipo" name="con_tipo">
                        <option value="Alquiler">Alquiler</option>
                        <option value="Loteo">Loteo</option>
                        <option value="Alquiler Comercial">Alquiler Comercial</option>
                    </select>
                </div>  

                <div class="select">
                    <label>Incluye IVA/Honorarios</label>
                    <select class="form-control ui-autocomplete-input" id="con_iva" name="con_iva">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div> 

                <div class="select">
                    <label>Incluye IVA/Alquiler</label>
                    <select class="form-control ui-autocomplete-input" id="con_iva_alq" name="con_iva_alq">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div> 

                <div class="select">
                    <label>Vencimiento o Extensión</label>
                    <select class="form-control ui-autocomplete-input" id="con_motivo" name="con_motivo" title="Cuando un contrato vence automaticamente tendra valor 'Vencido', cuando se prorroga o rescinde se debe colocar manualmente el valor correspondiente">
                        <option value=""></option>
                        <option value="Vencido">Vencido</option>
                        <option value="Rescindido">Rescindido</option>
                        <option value="Prorrogado">Prorrogado</option>
                    </select>
                </div> 

            </div>

            <div class="contract_periods">
                <label>Periodos</label>
                <div class="_periods">
                </div>
                <span onclick="contracts.addPeriodHtml();" type="button" class="btn btn-default btn-lg add_button">
                    <a class="glyphicon glyphicon-plus-sign"></a>&nbsp;Agregar
                </span>
            </div>

            <div class="contract_services">
                <label>Servicios</label>
                <div class="_services">
                </div>
                <span onclick="contracts.addServiceHtml();" type="button" class="btn btn-default btn-lg add_button">
                    <a class="glyphicon glyphicon-plus-sign"></a>&nbsp;Agregar
                </span>
            </div>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>   
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('contratos');">Resetear campos</a>
        </form>
    </div>

    <!--  Lista de Contratos  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _contratos_filter">
            <input class="form-control ui-autocomplete-input _search_input filter_input" type="text"  aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar por Propietario">
            <a onclick="general_scripts.refreshList('contratos', 'con_id', 'con_prop', 'contrato')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
        </div>

        <label class="active_contracts">Contratos Vigentes: <span class="_count_alive_contracts"><?php echo $alive_contracts ?></span></label>

        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>

<script>
    $(window).scroll(function(){ 
        // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
        general_scripts.getEntitiesOnScrollDown('contratos','con_id','contrato','con_prop');
    });
</script>