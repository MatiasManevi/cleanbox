
/*
 * Project: Cleanbox
 * Document: transaction
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var transaction = new Object();
var concept = new Object();
var credit = new Object();
var debit = new Object();
var migrate = new Object();

transaction.init = function () {
    transaction.initComponents();
    transaction.initSearchers();     
};  

transaction.initComponents = function () {
    concept.initComponents();
    credit.initComponents();
    debit.initComponents();
    migrate.initComponents();
};

transaction.initSearchers = function (){
    concept.initSearchers();
    credit.initSearchers();
    debit.initSearchers();
    migrate.initSearchers();
};
    
concept.initComponents = function () {
    // Concepts fields
    concept.conc_id = $('#conc_id');
    concept.conc_desc = $('#conc_desc');
    // selects
    concept.conc_cc = $('#conc_cc');
    //    concept.conc_control = $('#conc_control');
    concept.conc_tipo = $('#conc_tipo');
    concept.interes_percibe = $('#interes_percibe');
    concept.gestion_percibe = $('#gestion_percibe');
    concept.iva_percibe = $('#iva_percibe');
};

credit.initComponents = function () {
    credit.total_rent = $('#total_rent');
    credit.total_intereses = $('#total_intereses');
    credit.total_iva = $('#total_iva');
    credit.total = $('#total');
    
    credit.cred_depositante = $('#cred_depositante');
    credit.cred_cc = $('#cred_cc');
    credit.cc_id = $('#cc_id');
    credit.client_id = $('#client_id');
    // selects
    credit.search_credit_concept = $('._search_credit_concept');
    credit.dinamic_credits = $('._dinamic_credits');
    credit.contract_info = $('._contract_info');
    credit.totals = $("._totals");
    credit.contract_id = 0;
};

debit.initComponents = function () {
    debit.deb_cc = $('#deb_cc');
    debit.deb_id = $('#deb_id');
    debit.cc_id = $('#cc_id');
    debit.account_amount = $('#account_amount');
    debit.account_amount_var = 0;
    
    debit.dinamic_debits = $('._dinamic_debits');
    debit.search_debit_concept = $('._search_debit_concept');
    // selects
    debit.deb_forma = $('#deb_forma');
    debit.deb_tipo_trans = $('#deb_tipo_trans');
};

migrate.initComponents = function () {
    migrate.cc_from = $('#cc_from');
    migrate.cc_to = $('#cc_to');
    migrate.cc_from_id = $('#cc_from_id');
    migrate.cc_to_id = $('#cc_to_id');
    migrate.concept = $('#concept');
    migrate.amount = $('#amount');
    migrate.month = $('#month');
    migrate.address = $('#address');
     
    migrate.forma = $('#forma');
    migrate.tipo_trans = $('#tipo_trans');
};
 
concept.initSearchers  = function (){
    general_scripts.bindInputListSearcher('conceptos', 'conc_id', 'concepto');
};

credit.initSearchers = function(){
    general_scripts.bindInputAutocomplete($('._filter_propietary'), 'cuentas_corrientes', 'cc_id', 'cc_prop');
   
    general_scripts.bindInputAutocomplete($('._filter_renter'), 'clientes', 'client_id', 'client_name');
   
    general_scripts.bindInputAutocomplete($('._filter_concept'), 'conceptos', 'conc_id', 'conc_desc', 'Entrada');
      
    general_scripts.bindInputAutocomplete(credit.cred_depositante, 'clientes', 'client_id', 'client_name', false, function(response){
        $.ajax({
            url: validate_contract_parts,
            type:'POST',
            data: {
                'client_id' : response.id
            },
            dataType: 'json',
            success:function(response){
                credit.client_id.val(response.client_id);
                if(response.status){
                    credit.cred_depositante.attr('readonly', true);
                    credit.cred_cc.val(response.prop_name);
                    credit.cred_cc.attr('readonly', true);
                    credit.contract_id = response.con_id;
                    credit.cc_id.val(response.cc_id);
                    credit.search_credit_concept.focus();
                }else{
                    cleanbox_alert.showAlertInfo(response.error);
                }
            }
        });
    });
    
    general_scripts.bindInputAutocomplete(credit.cred_cc, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
        credit.cc_id.val(response.id);
        credit.cred_cc.attr('readonly', true);
        credit.search_credit_concept.focus();
    }); 
    
    credit.search_credit_concept.autocomplete({
        source: list_autocomplete  + '/conceptos/Entrada',
        select: function(event, ui) {
            
            var params = {
                'conc_id' : ui.item.id,
                'con_prop' : credit.cred_cc.val(),
                'con_inq' : credit.cred_depositante.val(),
                'con_id' : credit.contract_id       
            };
            
            $.ajax({
                url : search_credit_concept,
                type:'POST',
                dataType: 'json',
                data:params,
                beforeSend:function(){
                    loading.show();
                    if(credit.cc_id.val().length == 0 || credit.client_id.val().length == 0){
                        loading.hide();
                        cleanbox_alert.showAlertError('Por favor, primero complete Depositante y Cuenta existentes')    
                        return false;
                    }
                },
                success:function(response){
                    if(response.status){
                        // bloqueamos cuenta y depositante
                        credit.cred_cc.attr('readonly', true);
                        credit.cred_depositante.attr('readonly', true);
                            
                        // setteamos montos totales
                        credit.total_rent.val(response.data.total_rent);
                        credit.total_intereses.val(response.data.interes);
                        credit.total_iva.val(response.data.iva);
                        credit.total.val(response.data.total);
                        credit.totals.show();
                        
                        var contract_debts = response.data.contract_debts;
                        var services_control_debts = response.data.services_control_debts;
                        var contract = response.data.contract;
                        var concept = response.data.concept;
                        var iva_percibe = response.iva_percibe;
                        var interes_percibe = response.interes_percibe;
                            
                        // armo estructura deudas alquiler / loteo y servicios a pagar
                        if(contract_debts.length){
                            for (var x = 0; x < contract_debts.length; x++){
                                // chequeamos que una entrada no se repita en concepto y mes
                                if(credit.canAddRows(contract_debts[x])){
                                    credit.addCredit(contract_debts[x]['concept'], contract, contract_debts[x]['iva_percibe'], contract_debts[x]['interes_percibe'], contract_debts[x]['amount'], contract_debts[x]['default_days'], contract_debts[x]['intereses'], contract_debts[x]['month'], contract_debts[x]['sald_account']);
                                }else{
                                    // Es un contrato, tiene deudas pero ya estan cargadas, 
                                    // entonces carga un solo campo mas con el concepto buscado
                                    // pero con monto no determinado
                                    if(credit.dinamic_credits.is(':visible')){
                                        credit.addCredit(concept, contract, contract_debts[x]['iva_percibe'], contract_debts[x]['interes_percibe']);
                                        break;
                                    }
                                } 
                            }
                        }else{
                            // Es un contrato pero no tiene deudas, crear un campo con concepto
                            // unicamente por si desea pagar alquiler o servicio adelantado
                            credit.addCredit(concept, contract, iva_percibe, interes_percibe);
                        } 
                                               
                        // armo estructura para servicios a controlar
                        if(services_control_debts){
                            for (var y = 0; y < services_control_debts.length; y++){
                                if(credit.canAddRowsControl(services_control_debts[y])){
                                    credit.addServiceControl(services_control_debts[y]['concept'], services_control_debts[y]['month']);
                                }else{
                                    // Es un control de servicio, tiene deudas pero ya estan cargadas, 
                                    // entonces carga un solo campo mas con el concepto buscado
                                    if(credit.dinamic_credits.is(':visible')){
                                        credit.addServiceControl(services_control_debts[y]['concept'], '');
                                        break;
                                    }
                                } 
                            }
                        }
                             
                        credit.search_credit_concept.val('');
                        credit.dinamic_credits.show();
                        
                        if(response.exist_contract){
                            // muestro informacion del contrato
                            credit.contract_info.html(response.contract_info);
                            credit.contract_info.show();    
                        }
                        credit.recalculateTotals();
                        
                        if(response.show_send_mail){
                            $('._send_mail_container').fadeIn();
                            
                            if(response.enable_send_mail){
                                $('#send_notification').removeAttr('disabled');
                                $('._send_mail_container').attr('title', "Checkea para enviar notificacion de la transaccion via mail");
                            }else{
                                $('._send_mail_container').attr('title', "Ups! este propietario no tiene registrado un mail valido para enviar notificacion de la transaccion via mail");
                            }
                        }else{
                            $('._send_mail_container').fadeOut();
                        }
                        
                        if(response.print_report && print_receive){
                            $('._receive_number').fadeIn();
                        }
                    }else{
                        cleanbox_alert.showAlertError(response.error);
                    }
                    
                    general_scripts.initTooltips();
                    loading.hide();
                }
            });
        }
    });
};

debit.initSearchers = function (){
    debit.search_debit_concept.autocomplete({
        source: list_autocomplete  + '/conceptos/Salida',
        select: function(event, ui) {  
            if(debit.cc_id.val().length == 0){
                loading.hide();
                cleanbox_alert.showAlertError('Por favor, primero ingrese una Cuenta Corriente existente')    
                return false;
            }else{
                debit.addDebit(ui.item.value);
                event.preventDefault(); 
                debit.search_debit_concept.val('');
            }
        }
    });
    
    general_scripts.bindInputAutocomplete(debit.deb_cc, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function (response){
        debit.deb_cc.attr('readonly', true);
        debit.account_amount.val(response.account_amount);
        debit.account_amount_var = response.account_amount;
        debit.search_debit_concept.focus();
        debit.cc_id.val(response.id);
    });
    
    debit.deb_cc.on('keyup','', function (key) {
        debit.cc_id.val('');
    });
};

migrate.initSearchers = function (){
    migrate.month.datepicker( {
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            migrate.month.blur();
        }
    });
    
    general_scripts.bindInputAutocomplete(migrate.cc_from, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
        migrate.cc_from_id.val(response.id);
        migrate.cc_from.attr('readonly', true);
    });
    
    general_scripts.bindInputAutocomplete(migrate.cc_to, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
        migrate.cc_to_id.val(response.id);
        migrate.cc_to.attr('readonly', true);
    });

    general_scripts.bindInputAutocomplete(migrate.concept, 'conceptos', 'conc_id', 'conc_desc', 'both');
    general_scripts.bindInputAutocomplete(migrate.address, 'propiedades', 'prop_id', 'prop_dom');
};

concept.loadFormData = function(entity) {
    concept.conc_id.val(entity.conc_id);
    concept.conc_desc.val(entity.conc_desc);
    concept.conc_tipo.find('option[value="' + entity.conc_tipo + '"]').prop('selected', true);
    concept.conc_cc.find('option[value="' + entity.conc_cc + '"]').prop('selected', true);
    //    concept.conc_control.find('option[value="' + entity.conc_control + '"]').prop('selected', true);
    concept.interes_percibe.find('option[value="' + entity.interes_percibe + '"]').prop('selected', true);
    concept.gestion_percibe.find('option[value="' + entity.gestion_percibe + '"]').prop('selected', true);
    concept.iva_percibe.find('option[value="' + entity.iva_percibe + '"]').prop('selected', true);
};

credit.canAddRows = function (deuda){
    // chequea que una entrada no se repita en concepto y mes
    var can_add = true;
    $('._credit_block').each(function(){
        var $concepto = $(this).find('._concepto');
        var $mes = $(this).find('._mes');
        if($concepto.val() == deuda.concept && $mes.val() == deuda.month){
            can_add = false;
        }
    }); 
    return can_add;
};

credit.canAddRowsControl = function (deuda){
    // chequea que una entrada no se repita en concepto y mes
    var can_add = true;
    $('._service_control_block').each(function(){
        var $concepto = $(this).find('._concept');
        var $mes = $(this).find('._month');
        if($concepto.val() == deuda.concept && $mes.val() == deuda.month){
            can_add = false;
        }
    }); 
    return can_add;
};
     
credit.addServiceControl = function(concept, month){
    //Creo el Bloque
    var $service_control_block = $('<div/>', {
        'class' : 'credit_block _service_control_block'
    });
    credit.dinamic_credits.append($service_control_block);
    
    //Creo los inputs Concepto y Mes a controlar
    $('<input/>', {
        type: 'text',
        value: concept,
        readonly: true,
        required: true,
        style : 'margin-right: 5px;width: 17%;float: left;',
        'class': 'form-control ui-autocomplete-input _concept',
        placeholder: 'Concepto'
    }).appendTo($service_control_block);
    
              
    var $date = $('<input/>', {
        type: 'text',
        value: month,
        autocomplete: 'off',
        required: true,
        style : 'margin-right: 5px;width: 11.1%;float: left;',
        'class': 'form-control ui-autocomplete-input _month _general_number_input_control _general_amount_input_control _general_letters_input_control',
        placeholder: 'Mes'
    }).appendTo($service_control_block);
    var date_month_unix = Date.parse(month);
    var date = new Date(date_month_unix);
    $date.datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        defaultDate: date,
        changeMonth: true,
        changeYear: true,
        duration: "fast",
        maxDate: "+15y",
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            $date.blur();
        }
    });
    
    var $service_control_select = $('<select style="width:auto;float:left;margin-right: 5px;" title="Especifica si se presento el comprobante por el Inquilino" class="form-control _control">');
    var $select_control = $('<option value="1">Controlado</option>');        
    var $select_no_control = $('<option value="0">No presento comprobante</option>');   
    $select_control.appendTo($service_control_select);
    $select_no_control.appendTo($service_control_select);
    $service_control_select.appendTo($service_control_block);
         
    var $span = $('<span/>', {
        onclick: 'credit.removeServiceControl($(this))',
        style: 'height: 34px;',
        'class' : 'btn btn-default btn-lg _remove'
    }).appendTo($service_control_block);
    $('<a/>', {
        'class' : 'glyphicon glyphicon-trash',
        style : 'text-decoration: none; margin-top: -3px;'
    }).appendTo($span);
};
 
credit.addCredit = function (concept, contract, concept_perceive_iva, concept_perceive_interes, amount, default_days, intereses, date, parcial_payment){   
    
    var iva_honorary = typeof contract != 'undefined' && contract['con_iva'] == "Si" ? true : false;
        
    var iva_alquiler = typeof contract != 'undefined' && contract['con_iva_alq'] == "Si" ? true : false;
        
    var address = typeof contract != 'undefined' ? contract['con_domi'] : '';

    if(!concept){
        concept = '';
    }
    if(!amount){
        amount = '';
    }
    if(!default_days){
        default_days = '';
    }
    if(!intereses){
        intereses = '';
    }
    if(!date){
        date = '';
    }
    
    //Creo el Bloque
    var $credit_block = $('<div/>', {
        'class' : 'credit_block _credit_block'
    });
    credit.dinamic_credits.append($credit_block);
        
    //Creo los inputs Concepto, Monto y Mes Alquiler
    $('<input/>', {
        name: 'credit[concepto][]',
        type: 'text',
        value: concept,
        readonly: true,
        required: true,
        style : 'margin-right: 5px;width: 17%;float: left;',
        'class': 'form-control ui-autocomplete-input _concepto',
        placeholder: 'Concepto'
    }).appendTo($credit_block);
        
    $('<input/>', {
        name: 'credit[monto][]',
        required:true,
        value: amount,
        onkeyup:'credit.recalculateTotals()',
        onblur:'credit.recalculateTotals()',
        type: 'text',
        title:'Monto de '+concept,
        autocomplete: 'off',
        style : 'margin-right: 5px;width: 6.5%;float: left;',
        'class': 'form-control ui-autocomplete-input _monto _general_amount_input_control',
        placeholder: 'Monto'                 
    }).appendTo($credit_block);
             
    var $date = $('<input/>', {
        name: 'credit[mes][]',
        type: 'text',
        value: date,
        autocomplete: 'off',
        required: true,
        style : 'margin-right: 5px;width: 11.1%;float: left;',
        'class': 'form-control ui-autocomplete-input _mes _general_number_input_control _general_amount_input_control _general_letters_input_control',
        placeholder: 'Mes'
    }).appendTo($credit_block);
    var date_month_unix = Date.parse(date);
    var month = new Date(date_month_unix);
    $date.datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        defaultDate: month,
        changeMonth: true,
        changeYear: true,
        duration: "fast",
        maxDate: "+15y",
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            $date.blur();
        }
    });
    
    if(typeof contract != 'undefined'){
        if(concept == 'Honorarios'){
            var next_cuote_honorary = parseInt(contract['honorary_cuotes_payed']) + 1;
            if(next_cuote_honorary > contract['honorary_cuotes']){
                cleanbox_alert.showAlertInfo('Estas por pagar mas '+concept+' del que corresponde');
            }
            address = address + ' Cuota ' + next_cuote_honorary + '/' + contract['honorary_cuotes'];
        }    
        if(concept == 'Deposito de garantia'){
            var next_cuote_warranty = parseInt(contract['warranty_cuotes_payed']) + 1;
            if(next_cuote_warranty > contract['warranty_cuotes']){
                cleanbox_alert.showAlertInfo('Estas por pagar mas '+concept+' del que corresponde');
            }
            address = address + ' Cuota ' + next_cuote_warranty + '/' + contract['warranty_cuotes'];
        }  
    }
    
    var $address = $('<input/>', {
        name: 'credit[domicilio][]',
        value: address,
        title: 'Inmueble al que este relacionado este crédito o comentario que se desee agregar',
        type: 'text',
        style : 'margin-right: 5px;width: 16%;float: left;',
        'class': 'form-control ui-autocomplete-input _domicilio',
        placeholder: 'Domicilio Inmueble'                 
    }).appendTo($credit_block);
    general_scripts.bindInputAutocomplete($address, 'propiedades', 'prop_id', 'prop_dom');
    
    var $type_money_select = $('<select style="width:auto;float:left;margin-right: 5px;" title="Medio de pago" onchange="credit.toggleTypePayment($(this))" class="form-control _cred_forma" name="credit[cred_forma][]">');
    var $select_money = $('<option value="Efectivo">Efectivo</option>');        
    var $select_paper = $('<option value="Cheque">Cheque</option>');   
    $select_money.appendTo($type_money_select);
    $select_paper.appendTo($type_money_select);
   
    var $type_transaction_select = $('<select style="width:auto;float:left;margin-right: 5px;" title="Tipo Transaccion" class="form-control _cred_tipo_trans" name="credit[cred_tipo_trans][]">');
    var $select_cash = $('<option value="Caja">Caja</option>');        
    var $select_bank = $('<option value="Bancaria">Bancaria</option>');   
    $select_cash.appendTo($type_transaction_select);
    $select_bank.appendTo($type_transaction_select);
 
    $type_money_select.appendTo($credit_block);
    $type_transaction_select.appendTo($credit_block);
    
      
    if(concept == 'Alquiler' || concept == 'Alquiler Comercial' || concept == 'Honorarios' || concept == 'Deposito de garantia'){      
        var $div_select_container = $('<div class="forma_pago_select" title="Indica totalidad o parcialidad del pago del '+concept+'">');
        
        var $select_forma_pago = $('<select class="form-control _select_tipo_pago">');
        
        var $select_option_total = $('<option value="Total">Total</option>');
        var $select_option_cuenta = $('<option value="A Cuenta">A Cuenta</option>');
        var $select_option_saldo = $('<option value="Saldo">Saldo</option>');
        
        $select_forma_pago.append($select_option_total);
        $select_forma_pago.append($select_option_cuenta);
        $select_forma_pago.append($select_option_saldo);
        
        $div_select_container.append($select_forma_pago);
        $div_select_container.appendTo($credit_block);
        if(parcial_payment){
            $select_forma_pago.val('Saldo');
        }else{
            $select_forma_pago.val('Total');
        }
    }
    
    var $agregated_values = $('<div class="clear_both" style="margin-top: 9px;margin-bottom: 10px;">')
    
    if(iva_alquiler && concept == contract['con_tipo'] || iva_honorary  && concept == 'Honorarios' ||
        concept_perceive_iva &&concept != contract['con_tipo'] && concept != 'Honorarios'){

        $("<label>", {
            style : "margin-top: 8px; margin-right: 7px;",
            html:'IVA: '
        }).appendTo($agregated_values);

        $("<input/>", {
            name: "credit[iva_calculado][]",
            type: "text",
            readonly: true,
            autocomplete: "off",
            value: amount != '' ? amount * iva_percentaje : '',
            title: 'Iva calculado sobre el monto de '+concept+' '+date,
            style : "margin-right: 5px;width: 5%;float: left;margin-right: 68px;",
            "class": "form-control ui-autocomplete-input _iva_calculado",
            placeholder: "IVA"                 
        }).appendTo($agregated_values);
    }
    
    if(concept_perceive_interes){
        $("<label>", {
            style : "margin-top: 8px; margin-right: 7px;",
            html:'INTERES: '
        }).appendTo($agregated_values);
        
        var defined_default = false;
        var value_default = '';
        
        if(code_control){
            if(default_days != ''){
                defined_default = true;
                value_default =  default_days + ' dias de mora';
            }
        }else{
            if(default_days != ''){
                value_default =  default_days + ' dias de mora';
            }
        }
        
        $("<input/>", {
            value: value_default,
            readonly: defined_default,
            onkeyup:"credit.recalculateTotals()",
            onblur:"credit.recalculateTotals()",
            onclick:"credit.unlockDefaultDays($(this))",
            name: "credit[dias_mora][]",
            type: "text",
            title: defined_default ? 'Para modificar los dias de mora haz click aqui' : '',
            autocomplete: "off",
            style : "cursor:pointer !important;margin-right: 5px;width: 11.5%;float: left;",
            "class": "form-control ui-autocomplete-input _dias_mora",
            placeholder: "Dias de mora"                 
        }).appendTo($agregated_values);
            
        $("<input/>", {
            name: "credit[interes_calculado][]",
            type: "text",
            autocomplete: "off",
            title:'Interes calculado sobre el monto de '+concept+' '+date+' segun los dias de mora y el % punitorio',
            readonly: true,
            value: intereses,  
            style : "margin-right: 5px;width: 5.8%;float: left;",
            "class": "form-control ui-autocomplete-input _interes_calculado",
            placeholder: "Interes"                 
        }).appendTo($agregated_values);
            
        var $select_pay_int = $('<select style="width:auto" title="Si: el inquilino pagara intereses devengados, No: el inquilino tiene intereses devengados pero No pagara hoy" class="form-control _paga_intereses" name="credit[paga_intereses][]">');
        var $select_paga = $('<option value="Si">Si</option>');        
        var $select_nopaga = $('<option value="No">No</option>');        
        $select_pay_int.append($select_paga).append($select_nopaga);
        $select_pay_int.appendTo($agregated_values);
    }
      
    var $span = $('<span/>', {
        onclick: 'credit.removeCredit($(this))',
        style: 'height: 34px;',
        'class' : 'btn btn-default btn-lg _remove'
    }).appendTo($credit_block);
    $('<a/>', {
        'class' : 'glyphicon glyphicon-trash',
        style : 'text-decoration: none; margin-top: -3px;'
    }).appendTo($span);
            
    $agregated_values.appendTo($credit_block);
};

credit.removeCredit = function ($remove) {
    $remove.parents("._credit_block").remove();
    credit.recalculateTotals();
};

credit.removeServiceControl = function ($remove) {
    $remove.parents("._service_control_block").remove();
};
    
credit.recalculateTotals = function (){
    var total_rent = 0;    
    var total_intereses = 0;
    var total_iva = 0;
    var total = 0;
    
    //recalculo montos
    $('._monto').each(function () {
        total_rent += $(this).val() * 1;
    });
        
    // recalculo interes si hay
    $('._interes_calculado').each(function () {
        var $bloque = $(this).parents('._credit_block');
        var $monto = $bloque.find('._monto');
        var $dias_mora = $bloque.find('._dias_mora');
            
        var mora = $dias_mora.val();
        mora = mora.replace(/\D/g,'');
        var interes = $monto.val() * mora * $('#con_punitorio').val();
        var original = parseFloat(interes);
        interes = Math.round(original*100)/100;
            
        $(this).val(interes);
        if($monto.val() == ''){
            $(this).val('');
        }
        total_intereses += interes;
    });
        
    //recalculo iva
    $('._iva_calculado').each(function () {
        var $bloque = $(this).parents('._credit_block');
        var $monto = $bloque.find('._monto');
            
        var iva = $monto.val() * iva_percentaje;
        var original = parseFloat(iva);
        iva = Math.round(original*100)/100;
            
        $(this).val(iva);
        if($monto.val() == ''){
            $(this).val('');
        }
            
        total_iva += iva;
    });
        
        
    var original_total_rent = parseFloat(total_rent);
    var result_total_rent = Math.round(original_total_rent*100)/100 ;
    credit.total_rent.val(result_total_rent);
    
    var original_iva = parseFloat(total_iva);
    var result_iva = Math.round(original_iva*100)/100 ;
    credit.total_iva.val(result_iva);   
        
    var original_intereses = parseFloat(total_intereses);
    var result_interes = Math.round(original_intereses*100)/100 ;
    credit.total_intereses.val(result_interes);  
        
    total = total_rent + total_intereses + total_iva;
    var original_total = parseFloat(total);
    var result_total = Math.round(original_total*100)/100 ;
    credit.total.val(result_total);
    
};

credit.toggleTypePayment = function ($that){
    var type = $that.val();
    
    if(type == 'Efectivo'){
        $that.next("._payment_type").remove();
    }else{
        var $payment_type = $('<div/>', {
            'class': '_payment_type'
        }).insertAfter($that);
        
        //Creo los inputs Concepto y Monto
        $('<input/>', {
            type: 'text',
            title: 'Nro de Cheque',
            style : 'margin-right: 5px;width: 99px;float: left;',
            'class': 'form-control ui-autocomplete-input _cred_nro_cheque',
            placeholder: 'Nro Cheque'                 
        }).appendTo($payment_type);
        //Creo los inputs Concepto y Monto
        
        $('<input/>', {
            type: 'text',
            title: 'Banco',
            style : 'margin-right: 5px;width: 99px;float: left;',
            'class': 'form-control ui-autocomplete-input _cred_banco',
            placeholder: 'Banco'                 
        }).appendTo($payment_type);
        
        general_scripts.initTooltips();
    }
};
    
credit.unlockDefaultDays = function ($default){
    $default_days = $default;
    if($default_days.attr('readonly') == 'readonly'){
        var title = 'Modificar dias de mora';
        var descripcion = 'Para modificar los días de mora es necesario ingresar un código de autoriación, solicitelo a su encargado';
        var button_text = 'Aceptar';
        var action = 'credit.authUnlock($(\'._auth_code\').val())';
        modals.loadModalUnLockDefaultDays(title, descripcion, action, button_text)
    }
};
    
credit.authUnlock = function (auth_code){
    $.ajax({
        url: authorize_code,
        type:'POST',
        data: {
            'auth_code' : auth_code
        },
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        success:function(response){
            if(response.status){
                $default_days.removeAttr('readonly');
                $default_days.css('cursor', 'inherit');
                $default_days = null;  
            }else{
                cleanbox_alert.showAlertError(response.error);
            }
            loading.hide();
        }
    });
};

credit.saveCredits = function (url) {
    if(print_receive && $('#receive_number').is(':visible') && $('#receive_number').val().length == 0){
        cleanbox_alert.showAlertInfo('Ingresa el numero de recibo que imprimiras!');
        $('#receive_number').focus();
        return false;
    }
    
    var params = {
        'credits' : [],
        'services_control' : [],
        'send_notification': $('#send_notification').is(':checked'),
        'con_id': 0
    };
   
    if(!credit.contract_info.is(':empty')){
        params['con_id'] = $('#con_id').val();
    }
    
    credit.dinamic_credits.find('._credit_block').each(function(){
        var $credit_block = $(this);
        var new_credit = {};
        
        new_credit['cred_id'] = '';
        new_credit['cred_cc'] = credit.cred_cc.val();
        new_credit['con_id'] = params['con_id'] ? params['con_id'] : null;
        new_credit['cc_id'] = credit.cc_id.val();
        new_credit['client_id'] = credit.client_id.val();
        new_credit['cred_depositante'] = credit.cred_depositante.val();
        new_credit['receive_number'] = $('#receive_number').val();
        new_credit['cred_forma'] = $credit_block.find('._cred_forma').val();
        new_credit['cred_tipo_trans'] = $credit_block.find('._cred_tipo_trans').val();
        new_credit['cred_banco'] = $credit_block.find('._cred_banco').is('*') ? $credit_block.find('._cred_banco').val() : '';
        new_credit['cred_nro_cheque'] = $credit_block.find('._cred_nro_cheque').is('*') ? $credit_block.find('._cred_nro_cheque').val() : '';
        new_credit['cred_fecha'] = moment(new Date()).format("DD-MM-YYYY");   
        new_credit['cred_mes_alq'] = $credit_block.find('._mes').val();
        new_credit['cred_concepto'] = $credit_block.find('._concepto').val();
        new_credit['cred_monto'] = $credit_block.find('._monto').val();
        new_credit['cred_interes'] = $credit_block.find('._dias_mora').is('*') ? $credit_block.find('._dias_mora').val().replace(/[^0-9]/g,'') : '';
        new_credit['cred_domicilio'] = $credit_block.find('._domicilio').val();
        new_credit['cred_tipo_pago'] = $credit_block.find('._select_tipo_pago').is('*') ? $credit_block.find('._select_tipo_pago').val() : '';
        new_credit['cred_iva_calculado'] = $credit_block.find('._iva_calculado').is('*') ? $credit_block.find('._iva_calculado').val() : '';
        new_credit['cred_interes_calculado'] = $credit_block.find('._interes_calculado').is('*') ? $credit_block.find('._interes_calculado').val() : '';
        new_credit['paga_intereses'] = $credit_block.find('._paga_intereses').is('*') && $credit_block.find('._paga_intereses').val() == 'Si' ? true : false;
        
        params['credits'].push(new_credit);
    });
    
    credit.dinamic_credits.find('._service_control_block').each(function(){
        var $service_control_block = $(this);
        var new_control = {};
        
        new_control['service'] = $service_control_block.find('._concept').val();
        new_control['contract'] = params['con_id'] ? params['con_id'] : null;
        new_control['date'] = moment(new Date()).format("DD-MM-YYYY");
        new_control['status'] = $service_control_block.find('._control').val();
        new_control['month_checked'] = $service_control_block.find('._month').val();
        
        params['services_control'].push(new_control);
    });

    general_scripts.ajaxSubmit(url, params, function(response){
        if(response.status){
            cleanbox_alert.showAlertSuccess(response.success);  
            var entities = response.credits;
            var table = response.table;
            
            if(entities.length > 0){
                for (var x = 0; x < entities.length; x++) {
                    general_scripts.loadEntityToList(entities[x], table)
                }
            }
            general_scripts.cleanAddTab(response.table.table);
            
            if(response.print_report){
                transaction.printCreditReport(params);
            }
        }else{
            cleanbox_alert.showAlertError(response.error);
        }
    });
};
    
transaction.printCreditReport = function (params) {
    loading.show();
    cookie.setCookie('credits_receive', JSON.stringify(params), 1);
    window.location.href = show_credit_report;
};

debit.addDebit = function (concept){   

    if(!concept){
        concept = '';
    } 
    
    //Creo el Bloque
    var $debit_block = $('<div/>', {
        'class' : 'debit_block _debit_block'
    });
    debit.dinamic_debits.append($debit_block);
        
    //Creo los inputs Concepto, Monto y Mes Alquiler
    $('<input/>', {
        name: 'debit[concepto][]',
        type: 'text',
        value: concept,
        readonly: true,
        required: true,
        style : 'margin-right: 5px;width: 17%;float: left;',
        'class': 'form-control ui-autocomplete-input _concepto',
        placeholder: 'Concepto'
    }).appendTo($debit_block);
        
    $('<input/>', {
        name: 'debit[monto][]',
        required:true,
        onkeyup:'debit.recalculateAccountAmount()',
        onblur:'debit.recalculateAccountAmount()',
        type: 'text',
        title:'Monto de '+concept,
        autocomplete: 'off',
        style : 'margin-right: 5px;width: 6.5%;float: left;',
        'class': 'form-control ui-autocomplete-input _monto _general_amount_input_control',
        placeholder: 'Monto'                 
    }).appendTo($debit_block);
              
    var $mes = $('<input/>', {
        name: 'debit[mes][]',
        type: 'text',
        autocomplete: 'off',
        required: true,
        style : 'margin-right: 5px;width: 11.1%;float: left;',
        'class': 'form-control ui-autocomplete-input _mes _general_number_input_control _general_amount_input_control _general_letters_input_control',
        placeholder: 'Mes'
    }).appendTo($debit_block);
    $mes.datepicker( {
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            $mes.blur();
        }
    });
        
    var $domicilio = $('<input/>', {
        name: 'debit[domicilio][]',
        title: 'Inmueble al que este relacionado este crédito o comentario que se desee agregar',
        type: 'text',
        style : 'margin-right: 5px;width: 16%;float: left;',
        'class': 'form-control ui-autocomplete-input _domicilio',
        placeholder: 'Domicilio Inmueble'                 
    }).appendTo($debit_block);
    general_scripts.bindInputAutocomplete($domicilio, 'propiedades', 'prop_id', 'prop_dom');
        
    var $span = $('<span/>', {
        onclick: 'debit.removeDebit($(this))',
        style: 'height: 34px;',
        'class' : 'btn btn-default btn-lg _remove'
    }).appendTo($debit_block);
    $('<a/>', {
        'class' : 'glyphicon glyphicon-trash',
        style : 'text-decoration: none; margin-top: -3px;'
    }).appendTo($span);
            
};

debit.removeDebit = function ($remove) {
    $remove.parents("._debit_block").remove();
    debit.recalculateAccountAmount();
};

debit.recalculateAccountAmount = function () {
    var total_debit = 0;

    $('._debit_block').each(function(){
        if($(this).find('._monto').val().length){
            total_debit += parseFloat($(this).find('._monto').val());
        }
    });
    
    debit.account_amount.val(debit.account_amount_var - total_debit);
};

debit.renditionAmountOk = function () {
    debit.account_amount.val();
    
    if(loan_rendition){
        return true;
    }else{
        var rendition = false;
        $('._debit_block').each(function(){
            if($(this).find('._concepto').val() == 'Rendicion'){
                rendition = true;
            }
        });
        
        if(Number(debit.account_amount.val()) < 0 && rendition){
            cleanbox_alert.showAlertError('Las rendiciones no pueden superar el saldo disponible en la Cta. Cte. del propietario')    
            return false;
        }
    }

    return true;
};

debit.saveDebits = function (url) {
    if(debit.renditionAmountOk()){
        var params = {
            'debits' : []
        };
    
        debit.dinamic_debits.find('._debit_block').each(function(){
            var $debit_block = $(this);
            var new_debit = {};
        
            new_debit['deb_id'] = '';
            new_debit['deb_cc'] = debit.deb_cc.val();
            new_debit['cc_id'] = debit.cc_id.val();
            new_debit['deb_forma'] = $('#deb_forma').val();
            new_debit['deb_tipo_trans'] = $('#deb_tipo_trans').val();
            new_debit['deb_mes'] = $debit_block.find('._mes').val();
            new_debit['deb_concepto'] = $debit_block.find('._concepto').val();
            new_debit['deb_monto'] = $debit_block.find('._monto').val();
            new_debit['deb_domicilio'] = $debit_block.find('._domicilio').val();
       
            params['debits'].push(new_debit);
        });
    
        general_scripts.ajaxSubmit(url, params, function(response){
            if(response.status){
                cleanbox_alert.showAlertSuccess(response.success);  
                var entities = response.debits;
                var table = response.table;
            
                if(entities.length > 0){
                    for (var x = 0; x < entities.length; x++) {
                        general_scripts.loadEntityToList(entities[x], table)
                    }
                }
            
                general_scripts.cleanAddTab(response.table.table);

                if(response.print_report){
                    transaction.printDebitReport(response.transaction_id);
                }
            }else{
                cleanbox_alert.showAlertError(response.error);
            }
        });
    }
};

transaction.printDebitReport = function (transaction_id) {
    loading.show();
    cookie.setCookie('debits_receive', JSON.stringify(transaction_id), 1);
    window.location.href = print_debit_receive;
};

transaction.deleteTransaction = function (transaction_id, table) {
    modals.$deleteTransactionModal.modal('hide');
    
    var params = {
        'transaction_id' : transaction_id
    };
    
    general_scripts.ajaxSubmit(delete_transaction, params, function(response){
        var entities = response.entities;
        
        if(entities.length > 0){
            for (var x = 0; x < entities.length; x++) {
                $('._reg_entity_' + entities[x].id).remove();
            }
        }
        
        if(!$('._'+table+'_table').find("[class^=_reg_entity_]").is('*')){
            general_scripts.noRecordsTable(table);
        }
    });
};

$(function(){
    transaction.init();   
});