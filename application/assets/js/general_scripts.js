
/*
 * Project: Cleanbox
 * Document: general_scripts
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var general_scripts = new Object();
var users = new Object();
var list_filtered = false;

general_scripts.init = function (){
    users.initComponents();
    notifications.getRenterDebts();
    general_scripts.bindControlFields();
    if(window.location.pathname.indexOf('home') !== -1){
        general_scripts.calculateBeginCash();
        general_scripts.calculateProgressiveCash();
    }
    general_scripts.initTooltips();
    general_scripts.disableEnterKeyInForms();
    general_scripts.preventLoseUnsavedForms();
    general_scripts.bindDatepicker($('._datepicker_filter'));
    general_scripts.bindDatepickerMonth($('._datepicker_filter_month'));
};

users.initComponents = function () {
    // User fields
    users.id = $('#id');
    users.username = $('#username');
    users.password = $('#password');
};

users.loadFormData = function (entity) {
    users.id.val(entity.id);
    users.username.val(entity.username);
    users.password.val(entity.password);
};

general_scripts.calculateBeginCash = function (){
    general_scripts.ajaxSubmitWithoutLoading(calculate_begin_cash, {}, function(response){
        $('._begin_cash').html('$ ' + response.amount);
    });
};

general_scripts.calculateProgressiveCash = function (){
    general_scripts.ajaxSubmitWithoutLoading(calculate_progressive_cash, {}, function(response){
        $('._cash').html('$ ' + response.amount);
    })
};

general_scripts.isLocalStorageAvailable = function(){
    if (typeof(Storage) !== "undefined") {
        return true;
    } else {
        return false;
    }
};

general_scripts.removeImage = function (folder, container){
    var params = {
        'image' : $('#image').val(),
        'folder' : folder
    };
    
    general_scripts.ajaxSubmit(delete_image, params, function(){
        var $no_image = $('<img height="200" width="200" class="img_shadow _no_image" src="' + BASE_URL + 'img/no-image.png" alt="logo"/>');
        $('._remove_image').remove();
        $('._image_logo').remove();
        $(container).append($no_image);
    });
};
    
general_scripts.activateUploadFromFileImage = function ($input, folder, container){
    $input.uploadify({                     
        uploader: '/cleanbox/plugins/uploadify/uploadify.swf',
        script: '/cleanbox/plugins/uploadify/uploadify.php',
        cancelImg: '/cleanbox/plugins/uploadify/cancel.png',
        buttonImg: '/cleanbox/plugins/uploadify/button.jpg',
        folder: '/cleanbox/img/' + folder + '/',
        scriptAccess: 'always',
        fileExt     : '*.jpg;*.gif;*.png',
        fileSizeLimit : '0',
        fileDesc    : 'Image Files',
        auto: true,
        multi: false,
        onError: function (a, b, c, d) {
            if (d.status == 404)
                cleanbox_alert.showAlertError('Could not find upload script.');
            else if (d.type === "HTTP")
                cleanbox_alert.showAlertError('error '+d.type+": "+d.status);
            else if (d.type ==="File Size")
                cleanbox_alert.showAlertError(c.name+' '+d.type+' Limit: '+Math.round(d.sizeLimit/1024)+'KB');
            else
                cleanbox_alert.showAlertError('error '+d.type+": "+d.text);
        },
        onComplete: function (event, queueID, fileObj, response, data) {  
            general_scripts.ajaxSubmit(upload_image_from_file, {
                filearray: response,
                folder: folder
            }, function(response){
                $('._no_image').remove();
                var $input_img = $('<input value="' + response.image.value + '" id="image" type="hidden" name="logo"/>');
                var $img = $('<img src="' + response.image.src + '" class="img_shadow _image_logo" alt="logo"/>');
                var $remove = $('<a onclick="general_scripts.removeImage(\''+folder+'\', \'._logo\');" title="Eliminar" class="close _remove_image" href="javascript:;">[&times;]</a>');
                $(container).append($input_img);
                $(container).append($img);
                $(container).append($remove);
                $('._remove_image').show();
            });
        }
    });
};

// Restrict inputs a [0-9|A-Z|a-z]
general_scripts.bindControlFields = function () {  
    // only numbers
    $('body').on('keypress','._general_number_input_control', function (key) {
        if((key.charCode < 48 || key.charCode > 57)) return false;
    });
    
    // only numbers and points for decimals
    $('body').on('keypress','._general_amount_input_control', function (key) {
        if((key.charCode < 44 || key.charCode > 57) || (key.charCode == 47 || key.charCode == 44)) return false;
    });
    
    // only letters
    $('body').on('keypress','._general_letters_input_control', function (key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
};

general_scripts.initTooltips = function (selector){
    
    if(typeof selector === 'undefined'){
        selector = '[title]';
    }
    
    if ($(window).width() > 767) {
        $(selector).tooltip({
            container: 'body',
            placement: 'bottom'
        });
    }
};

general_scripts.preventLoseUnsavedForms = function (){
    $('form').areYouSure();
};

general_scripts.rescanFormDirtyness = function (){
    $('form').trigger('rescan.areYouSure');
};

general_scripts.disableEnterKeyInForms = function (){
    $('form, panel').on('keypress keyup','', function (e) {
        if (e.charCode === 13) { 
            e.preventDefault();
            return false;
        }  
    });
};

general_scripts.ajaxSubmitWithoutLoading = function (url, params, callback) {
    $.ajax({
        url: url,
        type:'POST',
        dataType: 'json',
        data: params,
        success:function(response){
            if(response.status){
                if(callback){
                    callback(response);
                } 
            }else{
                cleanbox_alert.showAlertError(response.error);
            }     
            general_scripts.initTooltips();
        }
    });
};

general_scripts.ajaxSubmit = function (url, params, callback) {
    $.ajax({
        url: url,
        type:'POST',
        dataType: 'json',
        data: params,
        beforeSend:function(){
            loading.show();
        },
        success:function(response){
            if(response.status){
                if(callback){
                    callback(response);
                } 
            }else{
                if(response.error_type == 'delete_credit'){
                    var action = 'general_scripts.deleteEntity('+ response.id +', "creditos", "cred_id", "'+true+'")';
                    modals.loadModalConfirm('Eliminar credito', response.error, action, 'Eliminar');  
                }else{
                    cleanbox_alert.showAlertError(response.error);
                }
            }     
            if(response.keep_loading != undefined && !response.keep_loading){
                loading.hide();
            }else if(response.keep_loading == undefined){
                loading.hide();
            }
            general_scripts.initTooltips();
        }
    });
};

general_scripts.saveEntity = function (url, form) {
    
    general_scripts.ajaxSubmit(url, $(form).serialize(), function (response) {
        if(response.entity && response.table){
            general_scripts.loadEntityToList(response.entity, response.table);
        }
        if(response.table){
            general_scripts.cleanAddTab(response.table.table);
        }
        cleanbox_alert.showAlertSuccess(response.success);
        $(window).scrollTop(0);
    });
    
};

general_scripts.deleteEntity = function (id, table, table_pk, force_delete, callback) {
    var params = {
        'id': id,
        'table': table,
        'table_pk': table_pk,
        'force_delete' : force_delete
    };

    general_scripts.ajaxSubmit(delete_entity, params, function () {
        if(callback){
            // Remover otros, en Contratos a los periodos y servicios por ejemplo
            callback();
        }else{
            $('._reg_entity_' + id).remove();
            
            if(!$('._'+table+'_table').find("[class^=_reg_entity_]").is('*')){
                general_scripts.noRecordsTable(table);
            }
        }
    });
};

general_scripts.loadEntityToEdit = function (id, table, table_pk, entity_name) {
    var params = {
        'id' : id,
        'table' : table,
        'table_pk' : table_pk,
        'entity_name' : entity_name
    };
    
    general_scripts.cleanAddTab(table);
    
    general_scripts.ajaxSubmit(load_entity_to_edit, params, general_scripts.loadEntityHtml)
};

general_scripts.refreshList = function (table, table_pk, table_order, entity_name, choosing_provider) {
    var params = {
        'table' : table,
        'table_pk' : table_pk,
        'table_order' : table_order,
        'entity_name' : entity_name
    };
    
    general_scripts.ajaxSubmit(refresh_list, params, function(response){
        var entities = response.entities;
        var table = response.table;
        
        if(entities.length > 0){
            list_filtered = false;
            general_scripts.cleanTable(table.table);
        
            for (x = 0; x < entities.length; x++) {
                general_scripts.loadEntityToList(entities[x], table, choosing_provider)
            }
            
            $("._row_count").val(30);
            $("._page").val(1);
        }else{
            general_scripts.noRecordsTable(table.table);
        }
    });
};

/* Recarga la lista cuando hay scroll down y el scroll bar llega a bottom */
var getting_entities_on_scroll_down;
general_scripts.getEntitiesOnScrollDown = function(table, table_pk, entity_name, table_order) {
    if(!getting_entities_on_scroll_down){
       
        if($('._list_tab_button').hasClass('active') && !list_filtered) {
            
            if ($(window).scrollTop() == $(document).height() - $(window).height()){ 
               
                var params = {
                    'table':table,
                    'table_pk':table_pk,
                    'table_order':table_order,
                    'entity_name':entity_name,
                    'row_count':parseInt($("._row_count").val()),
                    'page':parseInt($("._page").val())
                }
    
                getting_entities_on_scroll_down = $.ajax({
                    url: get_entities_on_scroll_down,
                    type:'POST',
                    dataType: 'json',
                    data: params,
                    beforeSend: function(){
                        loading_list.show();  
                    },
                    success:function(response){
                        if(response.status){
                            var entities = response.entities;
            
                            if(entities.length > 0){                
                                for (x = 0; x < entities.length; x++) {
                                    general_scripts.loadEntityToList(entities[x], response.table, false, true);
                                } 
                                $("._page").val(response.page);
                                $("._row_count").val(response.row_count);
                                general_scripts.initTooltips();
                            }else{
                                cleanbox_alert.showAlertInfo(response.info);
                            }
                            
                            getting_entities_on_scroll_down = false;
                            
                        }else{
                            cleanbox_alert.showAlertError(response.error);
                        }  
                        
                        loading_list.hide();
                    }
                });
            }
        }
    }else{
    //        getting_entities_on_scroll_down.abort();
    }
};

general_scripts.cleanTable = function (table) {
    $('._'+table+'_table').find("[class^=_reg_entity_], ._no_records").remove();
};

general_scripts.noRecordsTable = function (table) {
    general_scripts.cleanTable(table);
    
    $('._'+table+'_table').append('<tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>');
};

general_scripts.loadEntityHtml = function (response){
    var entity = response.entity, table = response.table;

    switch (table){
        case 'man_users':
            users.loadFormData(entity);
            break;
        case 'clientes':
            client.loadFormData(entity);
            break;
        case 'providers_rols':
            provider_rol.loadFormData(entity);
            break;
        case 'cuentas_corrientes':
            accounts.loadFormData(entity);
            break;
        case 'contratos':
            contracts.loadFormData(entity, response.periods, response.services);
            break;
        case 'propiedades':
            proprietary.loadFormData(entity);
            break;
        case 'comentarios':
            proprietary.loadFormDataComments(entity);
            break;
        case 'proveedores':
            provider.loadFormData(entity, response.areas, response.nota);
            break;
        case 'mantenimientos':
            maintenance.loadFormData(entity);
            break;
        case 'conceptos':
            concept.loadFormData(entity);
            break;
    }
    
    general_scripts.showAddTab();
};

general_scripts.loadEntityToList = function (entity, table, choosing_provider, insert_bottom){
    if(typeof entity.id !== 'undefined'){
        var $table = $('._' + table.table + '_table');
    
        $table.find("._no_records").remove();
    
        if($table.find('._reg_entity_' + entity.id).is('*')){
            $table.find('._reg_entity_' + entity.id).remove();
        }
    
        var $new_row = $('<tr class="_reg_entity_' + entity.id + '">');

        $.each(entity, function(index, value) {
            if(index !== 'id' && index !== 'print' && index !== 'transaction_id'){
                var $new_oolumn = $('<td>' + value + '</td>');
                $new_row.append($new_oolumn);
            }
        });
    
        var $actions_column = $('<td>');
    
        var $edit_action = $('<a title="Editar" onclick="general_scripts.loadEntityToEdit(' + entity.id + ',\''+ table.table + '\',\''+ table.table_pk + '\')" href="javascript:;" class="glyphicon glyphicon-edit"></a>');
    
        if(table.table == 'creditos' || table.table == 'debitos'){
            var $delete_action = $('<a title="Eliminar" onclick="modals.deleteTransactionModal(' + entity.transaction_id + ',\''+ table.table + '\')" href="javascript:;" class="glyphicon glyphicon-trash"></a>');    
        }else{
            var $delete_action = $('<a title="Eliminar" onclick="modals.deleteEntityModal(' + entity.id + ',\'' + table.table + '\',\'' + table.table_pk + '\',\''+ table.entity_name + '\')" href="javascript:;" class="glyphicon glyphicon-trash"></a>');
        }
    
        if(choosing_provider){
            var $choose_action = $('<a href="javascript:;" title="Elegir" class="glyphicon glyphicon-ok" onclick="maintenance.chooseProvider('+entity.id+')"></a>&nbsp;Elegir');
            $actions_column.append('&nbsp;');
            $actions_column.append($choose_action);
        }else{
            if(is_admin || table.table != 'cuentas_corrientes'|| table.table != 'man_users'){
                if(table.table != 'creditos' && table.table != 'debitos' && table.table != 'transferencias_to_safe' && table.table != 'transferencias_to_cash'){
                    $actions_column.append($edit_action);
                }
                if(table.table != 'providers_rols' && table.table != 'transferencias_to_safe' && table.table != 'transferencias_to_cash' || table.table == 'man_users' && is_admin){
                    $actions_column.append('&nbsp;');
                    $actions_column.append($delete_action);
                }
            }
        }
    
        if(entity.print){
            var onclick = general_scripts.getPrintAction(table.table, entity);
            var $print_action = $('<a title="Imprimir" onclick="' + onclick + '" href="javascript:;" class="glyphicon glyphicon-print"></a>');
            $actions_column.append('&nbsp;');
            $actions_column.append($print_action);
        }
    
        $new_row.append($actions_column);
        if(insert_bottom){
            $new_row.insertAfter($table.find('tbody tr:last'));
        }else{
            $new_row.insertAfter($table.find('tbody tr:first'));
        }
    }
};

general_scripts.getPrintAction = function (table, entity){
    switch(table){
        case 'mantenimientos':
            return 'report.buildReportFromList(\'' + show_mantenimiento_report + '\',' + entity.id + ')';
            break;
        case 'creditos':
            return 'report.buildReportFromList(\'' + show_credit_report_list + '\',' + entity.transaction_id + ')';
            break;
        case 'debitos':
            if(entity['deb_concepto'] === 'Rendicion'){
                return 'report.buildReportFromList(\'' + show_debit_report_list + '\',' + entity.id + ')';
            }else{
                return 'report.buildReportFromList(\'' + print_debit_receive + '\',' + entity.trans + ')';
            }
            break;
    }
};

general_scripts.cleanAddTab = function (table){
    $('.section_form')[0].reset();
    $('.section_form').find("input[type=hidden]").val("");
    
    switch (table){
        case 'contratos':
            contracts.periodos.empty();
            contracts.servicios.empty(); 
            break;
        case 'cuentas_corrientes':
            accounts.cc_prop.attr('readonly', false);
            break;
        case 'proveedores':
            provider.areas.empty();
            break;
        case 'creditos':
            credit.dinamic_credits.empty();
            credit.cred_cc.attr('readonly', false);
            credit.cred_depositante.attr('readonly', false);
            credit.contract_info.empty();
            credit.contract_info.hide();
            credit.totals.hide();
            credit.dinamic_credits.hide();
            credit.contract_id = 0;
            $('#send_notification').attr('disabled');
            $('._send_mail_container').removeAttr('title');
            $('._send_mail_container').fadeOut();
            $('._receive_number').fadeOut();
            break;
        case 'debitos':
            debit.dinamic_debits.empty();
            debit.deb_cc.attr('readonly', false);
            debit.account_amount_var = 0;
            break;
        case 'migrar':
            migrate.cc_from.attr('readonly', false);
            migrate.cc_to.attr('readonly', false);
            break;
    }
};

general_scripts.bindInputListSearcher = function (table, table_pk, entity_name, choosing_provider) {
    var $search_input;
    
    if(choosing_provider){
        $search_input = $('._'+table+'_filter ._search_input_choosing');
    }else{
        $search_input = $('._'+table+'_filter ._search_input');
    }
    
    $search_input.autocomplete({
        source: list_autocomplete + '/' + table,
        select: function(event, ui) {
            var params = {
                'table' : table,
                'table_pk' : table_pk,
                'entity_name' : entity_name,
                'id' : ui.item.id
            };
            $.ajax({
                url : search_row,
                data: params,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        var entity = response.entity;
                        var table = response.table;
                        
                        if(entity){
                            general_scripts.cleanTable(table.table);
                            list_filtered = true;
                            general_scripts.loadEntityToList(entity, table, choosing_provider);
                        }else{
                            general_scripts.noRecordsTable(table.table);
                        }
                        general_scripts.initTooltips();
                    }else{
                        cleanbox_alert.showAlertError(response.error);
                    }
                }
            });
        }
    });
};

general_scripts.bindInputAutocomplete = function ($field, table, table_pk, value_searched, type, callback) {
    $field.autocomplete({
        source: list_autocomplete  + '/' + table + '/' + type,
        select: function(event, ui) {
            $.ajax({
                url: search_value,
                type:'POST',
                data: {
                    'table' : table, 
                    'table_pk' : table_pk, 
                    'value_searched' : value_searched, 
                    'id' : ui.item.id
                },
                dataType: 'json',
                success:function(response){
                    if(response.status){
                        $field.html(response.value);
                        if(callback){
                            callback(response);
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

general_scripts.filterByValues = function (form) {
    
    general_scripts.ajaxSubmit(filter_by_values, $(form).serialize(), function (response) {
        if(response.status){
            var entities = response.entities;
            var table = response.table;
            if(entities.length > 0){
                general_scripts.cleanTable(table.table);
                
                list_filtered = true;
                
                for (x = 0; x < entities.length; x++) {
                    general_scripts.loadEntityToList(entities[x], response.table);
                } 
            }else{
                general_scripts.noRecordsTable(table.table);
            }
                
        }else{
            cleanbox_alert.showAlertError(response.error);
        }

    });
    
};

general_scripts.filterByValue = function (value, table, key, choosing_provider) {
    $.ajax({
        url : filter_by_value,
        type:'POST',
        data: {
            'value': value,
            'table': table,
            'key': key
        },
        dataType: 'json',
        success:function(response){
            if(response.status){
                var entities = response.entities;
                var table = response.table;
                if(entities.length > 0){
                    general_scripts.cleanTable(table.table);
                    
                    list_filtered = true;
                    
                    for (x = 0; x < entities.length; x++) {
                        general_scripts.loadEntityToList(entities[x], table, choosing_provider);
                    } 
                }else{
                    general_scripts.noRecordsTable(table.table);
                }
                general_scripts.initTooltips();
            }else{
                cleanbox_alert.showAlertError(response.error);
            }
        }
    });
};

general_scripts.bindDatepicker = function ($input, min_date) {
    var max_date = "+15y";
    if(typeof min_date !== 'undefined'){
        min_date = new Date(1940, 1 - 1, 1);
    }else{
        max_date = min_date;
    }
    $input.datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        duration: "slow",
        maxDate: max_date,
        minDate: min_date
    });
};

general_scripts.bindDatepickerMonth = function ($input, min_date) {
    var max_date = "+15y";

    $input.datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        changeMonth: true,
        changeYear: true,
        duration: "fast",
        maxDate: "+15y",
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            // $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            // $input.blur();
        }
    });
};

general_scripts.showAddTab = function () {
    // change section
    $('._list_entities').removeClass('in').removeClass('active');
    $('._add').addClass('in').addClass('active');
    
    // change pannel buttons
    $('._add_tab_button').addClass('active');
    $('._list_tab_button').removeClass('active');
    
    // change save entity button wording
    $('._add').find('._save_button').text('Guardar');
};

general_scripts.generateCode = function(url){
    general_scripts.ajaxSubmit(url, {}, function(response){
        if(response.status){
            $('._code').val(response.code);
        }else{
            cleanbox_alert.showAlertError(response.error);
        }
    });  
};

general_scripts.changeValueCheckbox = function ($checkbox){
    if($checkbox.is(':checked')){
        $checkbox.val(1);
    }else{
        $checkbox.val(0);
    }
};

$(function () {
    general_scripts.init();
});

String.prototype.capitalize = function(){
    var sa = this.replace(/-/g,' ');
    var saa = sa.toLowerCase();
    var sb = saa.replace( /(^|\s)([a-z])/g , function(m,p1,p2){
        return p1+p2.toUpperCase();
    } );
    var sc = sb.replace(/\s+/g, '-');
    return sc;
};