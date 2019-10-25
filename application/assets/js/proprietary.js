
/*
 * Project: Cleanbox
 * Document: proprietary
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var proprietary = new Object();
var accounts = new Object();
var contracts = new Object();

proprietary.init = function () {
    proprietary.initComponents();
    
    // Cuentas corrientes searchers
    general_scripts.bindInputAutocomplete(accounts.cc_prop, 'clientes', 'client_id', 'client_name', false, function (response) {       
        accounts.cc_prop.attr('readonly', true);
        accounts.client_id.val(response.id);
    });
    
    general_scripts.bindInputListSearcher('cuentas_corrientes', 'cc_id', 'cuenta corriente');

    // Contratos searchers
    general_scripts.bindInputAutocomplete(contracts.con_prop, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
        contracts.cc_id.val(response.id);
        contracts.con_inq.focus();
    });
    general_scripts.bindInputAutocomplete(contracts.con_inq, 'clientes', 'client_id', 'client_name', false, function(response){
        contracts.client_id.val(response.id); 
        contracts.con_gar1.focus();
    });
    general_scripts.bindInputAutocomplete(contracts.con_gar1, 'clientes', 'client_id', 'client_name', false, function(response){
        contracts.gar1_id.val(response.id);
        contracts.con_gar2.focus();
    });
    general_scripts.bindInputAutocomplete(contracts.con_gar2, 'clientes', 'client_id', 'client_name', false, function(response){
        contracts.gar2_id.val(response.id); 
    });
    general_scripts.bindInputAutocomplete(contracts.con_domi, 'propiedades', 'prop_id', 'prop_dom', false, function(response){
        contracts.prop_id.val(response.id); 
    });
    // Si tipea otro nombre luego de seleccionar uno, borra el id
    contracts.con_prop.on('keypress','', function (key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
        contracts.cc_id.val('');
    });
    contracts.con_inq.on('keypress','', function (key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
        contracts.client_id.val('');
    });
    contracts.con_gar1.on('keypress','', function (key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
        contracts.gar1_id.val('');
    });
    contracts.con_gar2.on('keypress','', function (key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
        contracts.gar2_id.val('');
    });
    contracts.con_domi.on('keypress','', function (key) {
        contracts.prop_id.val('');
    });
    
    contracts.bindInputListSearcherAllById('._contratos_filter', 'cuentas_corrientes', 'cc_id');
    general_scripts.bindDatepicker(contracts.con_venc);
    
    // Propiedades searchers
    general_scripts.bindInputAutocomplete(proprietary.prop_prop, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
        proprietary.cc_id.val(response.id);
    });
    proprietary.prop_prop.on('keypress','', function (key) {
        proprietary.cc_id.val('');
    });
    general_scripts.bindInputAutocomplete(proprietary.prop_contrato_vigente, 'clientes', 'client_id', 'client_name');
    contracts.bindInputListSearcherAllById('._propiedades_filter', 'cuentas_corrientes', 'prop_prop');
    
    // Comentarios searchers
    general_scripts.bindInputAutocomplete(proprietary.com_prop, 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
        proprietary.cc_id.val(response.id);
    });
    general_scripts.bindInputAutocomplete(proprietary.com_dom, 'propiedades', 'prop_id', 'prop_dom', false, function(response){
        proprietary.prop_id.val(response.id);
    });
    proprietary.com_prop.on('keypress','', function (key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode == 11 && key.charCode == 27 && key.charCode == 127 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
        proprietary.cc_id.val('');
    });
    proprietary.com_dom.on('keypress','', function (key) {
        proprietary.prop_id.val('');
    });
    general_scripts.bindDatepicker($('._datepicker_filter'));
    general_scripts.bindInputAutocomplete($('._filter_propietary'), 'cuentas_corrientes', 'cc_id', 'cc_prop');
};

// Inicializa componentes html
proprietary.initComponents = function () {
    // Account fields
    accounts.cc_id = $('#cc_id');
    accounts.client_id = $('#client_id');
    accounts.cc_prop = $('#cc_prop');
    accounts.cc_saldo = $('#cc_saldo');
    accounts.cc_varios = $('#cc_varios');
    accounts.loans = $('#loans');
    
    // Contract fields
    contracts.con_id = $('#con_id');
    contracts.cc_id = $('#cc_id');
    contracts.client_id = $('#client_id');
    contracts.gar1_id = $('#gar1_id');
    contracts.gar2_id = $('#gar2_id');
    contracts.prop_id= $('#prop_id');
    contracts.con_prop = $('#con_prop');
    contracts.con_inq = $('#con_inq');
    contracts.con_gar1 = $('#con_gar1');
    contracts.con_gar2 = $('#con_gar2');
    contracts.con_venc = $('#con_venc');
    contracts.con_domi = $('#con_domi');
    contracts.con_tolerancia = $('#con_tolerancia');
    contracts.con_porc = $('#con_porc');
    contracts.con_punitorio = $('#con_punitorio');
    contracts.honorary_cuotes = $('#honorary_cuotes');
    contracts.honorary_cuotes_payed = $('#honorary_cuotes_payed');
    contracts.honorary_cuotes_price = $('#honorary_cuotes_price');
    contracts.warranty_cuotes = $('#warranty_cuotes');
    contracts.warranty_cuotes_payed = $('#warranty_cuotes_payed');
    contracts.warranty_cuotes_price = $('#warranty_cuotes_price');
    contracts.periodos = $('._periods');
    contracts.servicios = $('._services');
    // selects
    contracts.con_enabled = $('#con_enabled');
    contracts.con_motivo = $('#con_motivo');
    contracts.con_tipo = $('#con_tipo');
    contracts.con_iva = $('#con_iva');
    contracts.con_iva_alq = $('#con_iva_alq');
    
    // Propiedad fields
    proprietary.prop_id = $('#prop_id');
    proprietary.prop_prop = $('#prop_prop');
    proprietary.prop_dom = $('#prop_dom');
    proprietary.prop_enabled = $('#prop_enabled');
    proprietary.prop_contrato_vigente = $('#prop_contrato_vigente');
    
    // Comentarios fields
    proprietary.cc_id = $('#cc_id');
    proprietary.prop_id = $('#prop_id');
    proprietary.com_prop = $('#com_prop');
    proprietary.com_dom = $('#com_dom');
    proprietary.com_com = $('#com_com');//textarea
    proprietary.com_date = $('#com_date');
    proprietary.com_mes = $('#com_mes');
    proprietary.com_ano = $('#com_ano');
    proprietary.com_id = $('#com_id');
    proprietary.pictures = $('._pictures');
    proprietary.image_listing = $('.image_listing');
    proprietary.to_timeline = $('.to_timeline');
};

contracts.addPeriodHtml = function (period){   
    var $period_container = $('<div/>', {
        'class' : '_period_container period_container'
    }).appendTo('._periods').hide().fadeIn(700);

    var $period_begin = $('<input/>', {
        name: 'per_inicio',
        type: 'text',
        required: true,
        title: 'Inicio periodo',
        value: period ? period.per_inicio : '', 
        style : 'margin-right: 5px;font-size: 13px;width: 247px;float: left;',
        'class': 'form-control _per_inicio',
        
        placeholder: 'Fecha Inicio'                 
    }).appendTo($period_container);
    general_scripts.bindDatepicker($period_begin);
        
    var $period_end = $('<input/>', {
        name: 'per_fin',
        type: 'text',
        required: true,
        title: 'Fin periodo',
        value: period ? period.per_fin : '', 
        style : 'margin-right: 5px;font-size: 13px;width: 247px;float: left;',
        'class': 'form-control _per_fin',
        
        placeholder: 'Fecha Fin'                 
    }).appendTo($period_container);
    general_scripts.bindDatepicker($period_end);
        
    $('<input/>', {
        name: 'per_monto',
        type: 'text',
        required: true,
        title: 'Monto periodo',
        value: period ? period.per_monto : '', 
        
        style : 'margin-right: 5px;font-size: 13px;width: 247px;float: left;',
        'class': 'form-control ui-autocomplete-input _per_monto _general_amount_input_control',
        placeholder: 'Monto'                 
    }).appendTo($period_container);
             
    $('<input/>', {
        name: 'per_id',
        type: 'hidden',
        value: period ? period.per_id : '', 
        'class':'_per_id'        
    }).appendTo($period_container);
                 
    var $delete_button = $('<span/>', {
        onclick: 'contracts.removePeriod(this)',
        style: 'height: 34px;',
        'class' : 'btn btn-default btn-lg'
    }).appendTo($period_container);
    
    $('<a/>', {
        'class' : 'glyphicon glyphicon-trash',
        style : 'text-decoration: none; margin-top: -3px;'
    }).appendTo($delete_button);
    
    general_scripts.rescanFormDirtyness();
};

contracts.removePeriod = function (that) { 
    var $delete_button = $(that); 
    var $period_container = $delete_button.parents('._period_container');
    var per_id = $period_container.find('._per_id').val();
    
    if(per_id != 0){
        general_scripts.deleteEntity(per_id, 'periodos', 'per_id', function() { 
            $period_container.remove(); 
        });
    }else{
        $period_container.remove();
    }
};
    
contracts.addServiceHtml = function (service){
    var $service_container = $('<div/>', {
        'class' : '_service_container service_container'
    }).appendTo('._services').hide().fadeIn(700);
        
    var $service = $('<input/>', {
        name: 'serv_concepto',
        type: 'text',
        value: service ? service.serv_concepto : '', 
        style : 'margin-right: 5px;font-size: 13px;width: 403px;float: left;',
        'class': 'form-control ui-autocomplete-input _serv_concepto',
        placeholder: 'Servicio'                 
    }).appendTo($service_container);
    $('<input/>', {
        name: 'serv_control',
        'class': '_serv_control',
        type: 'hidden',
        value: service ? 1 : 0
    }).appendTo($service_container);

    $service.autocomplete({
        source: list_autocomplete  + '/conceptos/cc_varios',
        select: function(event, ui) {
            $.ajax({
                url : search_value,
                type:'POST',
                data: {
                    'table' : 'conceptos', 
                    'table_pk' : 'conc_id', 
                    'value_searched' : 'conc_desc', 
                    'id' : ui.item.id
                },
                dataType: 'json',
                success:function(response){
                    if(response.status){
                        $service.val(response.value);
                        $service.attr('readonly', true);
                        $service_container.find('._serv_control').val(response.id);
                    }else{
                        cleanbox_alert.showAlertError(response.error);
                    }
                }
            });
        }
    }); 

    var $action_container = $('<div/>', {
        'class': '_action_container',
        style : 'float: left;margin-right: 19px;margin-top: 0;width:120px;'                 
    }).appendTo($service_container);
        
    var $action_select = $('<select/>', {
        'class':'form-control ui-autocomplete-input _serv_accion',
        name : 'serv_action',
        style : 'float:left;'                 
    }).appendTo($action_container);
        
    $('<option/>', {
        'class':'form-control ui-autocomplete-input',
        value : 'Controlar',   
        selected : service ? (service.serv_accion == 'Controlar' ? true : false) : '',
        html : 'Controlar'
    }).appendTo($action_select);   
    
    $('<option/>', {
        'class':'form-control ui-autocomplete-input',
        value : 'Pagar',   
        selected : service ? (service.serv_accion == 'Pagar' ? true : false) : '',
        html : 'Pagar'
    }).appendTo($action_select);
       
    $('<input/>', {
        name: 'serv_id',
        value: service ? service.serv_id : '', 
        'class': '_serv_id',
        type: 'hidden'     
    }).appendTo($service_container);
    
    var $delete_button = $('<span/>', {
        onclick: 'contracts.removeService(this)',
        style: 'height: 34px;',
        'class' : 'btn btn-default btn-lg'
    }).appendTo($service_container);
        
    $('<a/>', {
        'class' : 'glyphicon glyphicon-trash',
        style : 'text-decoration: none; margin-top: -3px;'
    }).appendTo($delete_button);
    
    general_scripts.rescanFormDirtyness();
};

contracts.removeService = function (that) { 
    var $delete_button = $(that); 
    var $service_container = $delete_button.parents('._service_container');
    var serv_id = $service_container.find('._serv_id').val();
    
    if(serv_id != 0){
        general_scripts.deleteEntity(serv_id, 'servicios', 'serv_id', function() { 
            $service_container.remove(); 
        });
    }else{
        $service_container.remove();
    }
};

contracts.loadFormData = function (entity, periods, services) {
    
    contracts.con_id.val(entity.con_id);
    contracts.cc_id.val(entity.cc_id);
    contracts.client_id.val(entity.client_id);
    contracts.gar1_id.val(entity.gar1_id);
    contracts.gar2_id.val(entity.gar2_id);
    contracts.prop_id.val(entity.prop_id);
    contracts.con_prop.val(entity.con_prop);
    contracts.con_inq.val(entity.con_inq);
    contracts.con_gar1.val(entity.con_gar1);
    contracts.con_gar2.val(entity.con_gar2);
    contracts.con_venc.val(entity.con_venc);
    contracts.con_domi.val(entity.con_domi);
    contracts.con_tolerancia.val(entity.con_tolerancia);
    contracts.con_porc.val(entity.con_porc);
    contracts.con_punitorio.val(entity.con_punitorio);
    contracts.honorary_cuotes.val(entity.honorary_cuotes);
    contracts.honorary_cuotes_payed.val(entity.honorary_cuotes_payed);
    contracts.honorary_cuotes_price.val(entity.honorary_cuotes_price);
    contracts.warranty_cuotes.val(entity.warranty_cuotes);
    contracts.warranty_cuotes_payed.val(entity.warranty_cuotes_payed);
    contracts.warranty_cuotes_price.val(entity.warranty_cuotes_price);
   
    //selects
    contracts.con_enabled.find('option[value="' + entity.con_enabled + '"]').prop('selected', true);
    contracts.con_motivo.find('option[value="' + entity.con_motivo + '"]').prop('selected', true);
    contracts.con_tipo.find('option[value="' + entity.con_tipo + '"]').prop('selected', true);
    contracts.con_iva.find('option[value="' + entity.con_iva + '"]').prop('selected', true);
    contracts.con_iva_alq.find('option[value="' + entity.con_iva_alq + '"]').prop('selected', true);

    for (x = 0; x < periods.length; x++) {
        contracts.addPeriodHtml(periods[x]);
    }
   
    for (x = 0; x < services.length; x++) {
        contracts.addServiceHtml(services[x]);
    }
    
};

accounts.loadFormData = function (entity) {
    accounts.cc_id.val(entity.cc_id);
    accounts.client_id.val(entity.client_id);
    accounts.cc_prop.val(entity.cc_prop);
    accounts.cc_saldo.val(entity.cc_saldo);
    accounts.cc_varios.val(entity.cc_varios);
    accounts.loans.val(entity.loans);
};

proprietary.loadFormData = function (entity){  
    proprietary.prop_id.val(entity.prop_id);
    proprietary.cc_id.val(entity.cc_id);
    proprietary.prop_prop.val(entity.prop_prop);
    proprietary.prop_dom.val(entity.prop_dom);
    proprietary.prop_enabled.val(entity.prop_enabled);
    
    var in_contract = entity.prop_contrato_vigente != '' ? entity.prop_contrato_vigente : 'Libre';
    
    proprietary.prop_contrato_vigente.val(in_contract);

    proprietary.to_timeline.css('display', 'block');
    proprietary.to_timeline.html('<p> Podes ver la linea de tiempo de esta propiedad <a href="'+BASE_URL+'timeline/property/'+entity.prop_id+'" target="_blank">ACA</a></p>');
    // pictures
    var pics = '';
    for (var i = entity.pictures.length - 1; i >= 0; i--) {
        if(entity.pictures[i].url.length){
            pics = pics + entity.pictures[i].url+',';
        }
    }
    
    if(pics.length){
        proprietary.pictures.val(pics);
        capturePics();
    }
};

proprietary.loadFormDataComments = function (entity){  
    proprietary.cc_id.val(entity.cc_id);
    proprietary.com_id.val(entity.com_id);
    proprietary.prop_id.val(entity.prop_id);
    proprietary.com_prop.val(entity.com_prop);
    proprietary.com_dom.val(entity.com_dom);
    proprietary.com_com.val(entity.com_com);
    proprietary.com_date.val(entity.com_date);
    proprietary.com_mes.val(entity.com_mes);
    proprietary.com_ano.val(entity.com_ano);
};

contracts.saveContract = function (url) { 
    var params = {
        'con_id': contracts.con_id.val(),
        'cc_id': contracts.cc_id.val(),
        'client_id': contracts.client_id.val(),
        'gar1_id': contracts.gar1_id.val(),
        'gar2_id': contracts.gar2_id.val(),
        'prop_id': contracts.prop_id.val(),
        'con_prop': contracts.con_prop.val(),
        'con_inq': contracts.con_inq.val(),
        'con_gar1': contracts.con_gar1.val(),
        'con_gar2': contracts.con_gar2.val(),
        'con_venc': contracts.con_venc.val(),
        'con_domi': contracts.con_domi.val(),
        'con_tolerancia': contracts.con_tolerancia.val(),
        'honorary_cuotes': contracts.honorary_cuotes.val(),
        'honorary_cuotes_payed': contracts.honorary_cuotes_payed.val(),
        'honorary_cuotes_price': contracts.honorary_cuotes_price.val(),
        'warranty_cuotes': contracts.warranty_cuotes.val(),
        'warranty_cuotes_payed': contracts.warranty_cuotes_payed.val(),
        'warranty_cuotes_price': contracts.warranty_cuotes_price.val(),
        'con_porc': contracts.con_porc.val(),
        'con_punitorio': contracts.con_punitorio.val(),
        'con_enabled': contracts.con_enabled.val(),
        'con_motivo': contracts.con_motivo.val(),
        'con_tipo': contracts.con_tipo.val(),
        'con_iva': contracts.con_iva.val(),
        'con_iva_alq': contracts.con_iva_alq.val()
    };
    
    if(!contracts.con_id.val().length){
        params['con_date_created'] =  moment().format('D-M-YYYY');
    }
    params['con_date_declined'] = '';
    params['con_date_renovated'] = '';
        
    if(params['con_motivo'] == 'Rescindido'){
        params['con_date_declined'] = moment().format('D-M-YYYY');
    }else if(params['con_motivo'] == 'Prorrogado'){
        params['con_date_renovated'] = moment().format('D-M-YYYY');
    }
    
    params['periods'] = [];
    params['services'] = [];
    
    $('._period_container').each(function(){
        var $period = $(this);
        var new_period = {};
        
        new_period['per_id'] = $period.find('._per_id').val();
        new_period['per_inicio'] = $period.find('._per_inicio').val();
        new_period['per_fin'] = $period.find('._per_fin').val();
        new_period['per_monto'] = $period.find('._per_monto').val(); 
        
        params['periods'].push(new_period);
    });
    
    $('._service_container').each(function(){
        var $service = $(this);
        var new_service = {};
        
        new_service['serv_id'] = $service.find('._serv_id').val();
        new_service['serv_concepto'] = $service.find('._serv_concepto').val();
        new_service['serv_accion'] = $service.find('._serv_accion').val();
        new_service['serv_control'] = $service.find('._serv_control').val();
        
        params['services'].push(new_service);
    });

    general_scripts.ajaxSubmit(url, params, function (response) {
        general_scripts.loadEntityToList(response.entity, response.table);
        general_scripts.cleanAddTab(response.table.table);
        cleanbox_alert.showAlertSuccess(response.success);
        $('._count_alive_contracts').html(response.count_alive_contracts);
    });
    
};

contracts.bindInputListSearcherAllById = function (filter, table, value_searched) {
    $(filter + ' ._search_input').autocomplete({
        source: list_autocomplete + '/' + table,
        select: function(event, ui) {
            var params = {
                'value_searched' : value_searched,
                'id' : ui.item.id
            };
            $.ajax({
                url : search_all_of_id,
                data: params,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        var entities = response.entities;
                        var table = response.table;
                        
                        if(entities.length > 0){
                            general_scripts.cleanTable(table.table);
        
                            list_filtered = true;
                            
                            for (x = 0; x < entities.length; x++) {
                                general_scripts.loadEntityToList(entities[x], table)
                            }
                        }else{
                            general_scripts.noRecordsTable(table.table);
                        }
                    }else{
                        cleanbox_alert.showAlertError(response.error);
                    }
                }
            });
        }
    });
};

$(function(){
    proprietary.init();
});