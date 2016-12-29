<h2><?php echo t('Migrar') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Migrar entre Ctas.</a></li>
</ul>

<div class="tab-content section">
    <!--  Migracion entre cuentas  -->
    <div class="tab-pane fade in active _add" id="add">
        <div class="section_description">
            <label>Aqui se puede migrar de la Cuenta Cte. "A" hacia la Cuenta Cte. "B" un Monto determinado bajo un concepto.</label>
            <label>El Concepto debe existir tanto de "Entrada" como de "Salida", con el mismo Nombre</label>
        </div>

        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveMigration') ?>', this);return false;" enctype="multipart/form-data"> 

            <div class="credit_parts">
                <input type="text" required id="cc_from" name="cc_from" class="form-control ui-autocomplete-input section_input" placeholder="Origen" title="Cta. Cte. Origen" style="width: 49%">
                <input type="text" required id="cc_to" name="cc_to" class="form-control ui-autocomplete-input section_input clear_none" placeholder="Destino" title="Cta. Cte. Destino" style="width: 49%">                
                <input type="hidden" id="cc_from_id" name="cc_from_id">
                <input type="hidden" id="cc_to_id" name="cc_to_id">
            </div>

            <div class="credit_types" style="width: 52%;margin-bottom: 14px;">
                <div class="section_selects clear_none _cred_forma_select">
                    Medio de pago
                    <select onchange="credit.toggleTypePayment($(this).val())" autofocus="true" class="form-control" id="forma" name="forma">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>

                <div class="section_selects clear_none">
                    Tipo Transaccion
                    <select class="form-control" id="tipo_trans" name="tipo_trans" autofocus="true">
                        <option value="Caja">Caja</option>
                        <option value="Bancaria">Bancaria</option>
                    </select>
                </div>
            </div>

            <div class="credit_block _credit_block">
                <input required id="concept" type="text" name="concept" style="margin-right: 5px;width: 17%;float: left;" class="form-control ui-autocomplete-input _concepto" title="El Concepto debe existir tanto de Entrada como de Salida" placeholder="Concepto">
                <input required id="amount" type="text" name="amount" style="margin-right: 5px;width: 7%;float: left;" class="form-control ui-autocomplete-input _monto _general_amount_input_control" title="Monto" placeholder="Monto">
                <input required id="month" type="text" name="month" style="margin-right: 5px;width: 11.1%;float: left;cursor: not-allowed;" class="form-control ui-autocomplete-input _mes_migracion _general_number_input_control _general_amount_input_control _general_letters_input_control" title="Mes" placeholder="Mes">
                <input id="address" type="text" name="address" style="margin-right: 5px;width: 16%;float: left;" class="form-control ui-autocomplete-input _domicilio" title="Inmueble al que este relacionado este crédito o comentario que se desee agregar" placeholder="Domicilio">
            </div>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('migrar');">Resetear campos</a>
        </form>
    </div>
</div>
