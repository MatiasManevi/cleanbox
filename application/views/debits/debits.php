<h2><?php echo t('Debitos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Nuevo Debito</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Debitos</a></li>
</ul>

<input type="hidden" class="_row_count" value="<?php echo $row_count ?>">
<input type="hidden" class="_page" value="1">  

<div class="tab-content section">
    <!--  Crear Debito  -->
    <div class="tab-pane fade in active _add" id="add">

        <div class="section_description">
            <label>En este formulario se registran débitos de toda indole, 
                impactando en cualquier cuenta corriente existente que se ingrese,
                en la forma y bajo los conceptos que sean especificados.</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="debit.saveDebits('<?php echo site_url('saveDebit') ?>');" enctype="multipart/form-data"> 
            <input type="text" id="deb_cc" name="deb_cc" class="form-control ui-autocomplete-input section_input" title="Cuenta Corriente" placeholder="Cta. Cte." required>
            <input type="hidden" id="deb_id" name="deb_id">
            <input type="hidden" id="cc_id" name="cc_id">
            <input type="text" id="account_amount" name="account_amount" class="form-control ui-autocomplete-input section_input clear_none" readonly placeholder="Saldo disponible" title="Saldo operativo disponible en Cta. Cte." style="width: 10%;">

            <div class="section_selects clear_none" style="width: 12%;margin-top: -21px;">
                Forma de Pago
                <select id="deb_forma" class="form-control" name="deb_forma">
                    <option class="form-control" value="Efectivo">Efectivo</option>
                    <option class="form-control" value="Cheque">Cheque</option>
                </select>
            </div>

            <div class="section_selects clear_none" style="width: 12%;margin-top: -21px;">
                Tipo de Transacción
                <select id="deb_tipo_trans" class="form-control" name="deb_tipo_trans">
                    <option class="form-control" value="Caja">Caja</option>
                    <option class="form-control" value="Bancaria">Bancaria</option>
                </select>
            </div>

            <input class="form-control ui-autocomplete-input section_input _search_debit_concept" style="margin-top: 10px;" placeholder="Buscar Concepto">

            <div class="_dinamic_debits">
            </div>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('debitos');">Resetear campos</a>
        </form>
    </div>

    <!--  Lista de Debitos  -->
    <div class="tab-pane fade _list_entities" id="list">

        <div class="filter_container _debitos_filter">
            <form action="javascript:;" onsubmit="general_scripts.filterByValues(this);" enctype="multipart/form-data">  
                <input autocomplete="off" title="Cta. Cte" name="propietary" placeholder="Cta. Cte" type="text" class="form-control filter_input ui-autocomplete-input _filter_propietary">
                <input autocomplete="off" name="from" placeholder="Desde" type="text" class="form-control filter_input _datepicker_filter">
                <input autocomplete="off" name="to" placeholder="Hasta" type="text" class="form-control filter_input _datepicker_filter">      
                <input autocomplete="off" title="Concepto" name="concept" placeholder="Concepto" type="text" class="form-control filter_input ui-autocomplete-input _filter_concept">         
                <input name="table" type="hidden" value="debitos">      
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a onclick="general_scripts.refreshList('debitos', 'deb_id', 'deb_id', 'debito')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
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
        general_scripts.getEntitiesOnScrollDown('debitos','deb_id','debito','deb_id');
    });
</script>