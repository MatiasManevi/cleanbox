<h2><?php echo t('Creditos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Nuevo Credito</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Creditos</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Credito  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran créditos de toda indole, entre inquilinos y ctas. ctes. de propietarios, Inmobiliaria e inquilinos,</label>
            <label>Inmobiliaria y propietarios, Inmobilaria y terceros, entre otros. Los créditos impactarán a la Cta. Cte. que se ingrese, en la forma</label>
            <label>y bajo los conceptos que sean especificados.</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="credit.saveCredits('<?php echo site_url('saveCredit') ?>');" enctype="multipart/form-data"> 
            <input type="text" id="receive_number" name="receive_number" class="form-control ui-autocomplete-input section_input clear_both _receive_number _general_number_input_control" placeholder="N° recibo" autocomplete="off" title="N° que figura en recibo de papel que se imprimira" style="width: 10%;float:none;display:none;">

            <div class="credit_parts">
                <input type="text" required id="cred_depositante" name="cred_depositante" class="form-control ui-autocomplete-input section_input" placeholder="Depositante/Inquilino" title="Depositante/Inquilino" style="width: 49%">
                <input type="text" required id="cred_cc" name="cred_cc" class="form-control ui-autocomplete-input section_input clear_none" placeholder="Cta. Cte./Propietario" title="Cta. Cte./Propietario" style="width: 49%">                
                <input type="hidden" id="cc_id" name="cc_id">
                <input type="hidden" id="client_id" name="client_id">
            </div>

            <div hidden class="contract_info _contract_info"></div>

            <div class="credit_block _credit_block">
                <input type="text" name="concept" class="form-control ui-autocomplete-input _search_credit_concept section_input" placeholder="Buscar Concepto" autocomplete="off" style="width: 326px;">
            </div>

            <div class="_dinamic_credits" style="display:none;"></div>

            <div class="_totals totals">
                <label type="text" style="margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> SUBTOTALES </label>
                <input title="Suma de todos los montos netos" type="text" id="total_rent" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> INTERESES </label>                
                <input title="Suma de todos los intereses netos" type="text" id="total_intereses" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> IVA </label>                
                <input title="Suma de todo el IVA neto" type="text" id="total_iva" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> TOTAL </label>                
                <input type="text" id="total" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">                
            </div>

            <div class="send_mail_container _send_mail_container">
                <i style="font-size: 25px;" class="glyphicon glyphicon-send" ></i><input style="width: 45%;" disabled type="checkbox" name="send_notification" id="send_notification" class="clear_both">
            </div>

            <button class="btn btn-primary _save_button submit_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('creditos');">Resetear campos</a>
        </form>

    </div>

    <!--  Lista de Creditos  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _creditos_filter">
            <form action="javascript:;" onsubmit="general_scripts.filterByValues(this);" enctype="multipart/form-data">  
                <input autocomplete="off" title="Cta. Cte" name="propietary" placeholder="Cta. Cte" type="text" class="form-control filter_input ui-autocomplete-input _filter_propietary">
                <input autocomplete="off" title="Depositante" name="renter" placeholder="Depositante" type="text" class="form-control filter_input ui-autocomplete-input _filter_renter">
                <input autocomplete="off" name="from" placeholder="Desde" type="text" class="form-control filter_input _datepicker_filter">
                <input autocomplete="off" name="to" placeholder="Hasta" type="text" class="form-control filter_input _datepicker_filter">      
                <input autocomplete="off" title="Concepto" name="concept" placeholder="Concepto" type="text" class="form-control filter_input ui-autocomplete-input _filter_concept">         
                <input name="table" type="hidden" value="creditos">      
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a onclick="general_scripts.refreshList('creditos', 'cred_id', 'cred_id', 'credito')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
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
        general_scripts.getEntitiesOnScrollDown('creditos','cred_id','credito','cred_id');
    });
</script>