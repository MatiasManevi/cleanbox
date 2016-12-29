
/*
 * Project: Cleanbox
 * Document: modals
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var modals = new Object();

modals.init = function () {
    modals.$defaultConfirmModal = $('#defaultConfirmModal');
    modals.$chooseProviderModal = $('#chooseProviderModal');
    modals.$deleteTransactionModal = $('#deleteTransactionModal');
};

modals.loadModalConfirm = function (title, description, action, button_text, text_align) {  
    modals.reset(modals.$defaultConfirmModal);
    
    var $text = modals.$defaultConfirmModal.find('._description');
    if(text_align){
        $text.addClass(text_align);
    }else{
        $text.addClass('text-center');
    }
    $text.text(description);
    
    var $title = modals.$defaultConfirmModal.find('._title');
    $title.html(title);
    
    var $action = modals.$defaultConfirmModal.find('._confirm');
    $action.text(button_text);
    $action.attr('onclick', action);
    
    modals.$defaultConfirmModal.modal('show');
};

modals.loadModalUnLockDefaultDays = function (title, description, action, button_text, text_align) {  
    modals.reset(modals.$defaultConfirmModal);
    
    var $text = modals.$defaultConfirmModal.find('._description');
    if(text_align){
        $text.addClass(text_align);
    }else{
        $text.addClass('text-center');
    }
    $text.text(description);
    
    var $title = modals.$defaultConfirmModal.find('._title');
    $title.html(title);
    
    var $input_code = $('<input placeholder="Código" style="text-align:center;width:20%;margin:0 auto;font-size:13px;" title="Solicite el codigo al usuario admin de su sucursal" class="form-control ui-autocomplete-input _auth_code" type="text">');
    var $container = modals.$defaultConfirmModal.find('._media_content');
    $container.html($input_code);
    
    var $action = modals.$defaultConfirmModal.find('._confirm');
    $action.text(button_text);
    $action.attr('onclick', action);
    
    modals.$defaultConfirmModal.modal('show');
};

modals.loadModalChooseProvider = function (title) {  
    var $title = modals.$chooseProviderModal.find('._title');
    $title.html(title);
    
    modals.$chooseProviderModal.modal('show');
};

modals.deleteEntityModal = function (id, table, table_pk, entity_name) {
    var action = 'general_scripts.deleteEntity('+ id +', "'+table+'", "'+table_pk+'")';
    var description = 'Esta seguro de eliminar este registro de '+entity_name+'?';
    var title = 'Eliminar '+entity_name+'';
    var button_text = 'Eliminar';
    modals.loadModalConfirm(title, description, action, button_text);
};

modals.confirmDeleteTransaction = function (transaction_id, table) {
    var action = 'transaction.deleteTransaction(' + transaction_id + ',\''+ table + '\')';
    var description = 'Esta seguro de eliminar por completo esta transaccion?';
    var title = 'Eliminar Transaccion';
    var button_text = 'Eliminar';
    modals.loadModalConfirm(title, description, action, button_text);
};


modals.deleteTransactionModal = function (transaction_id, table) {
    var params = {
        'transaction_id' : transaction_id
    };
    
    general_scripts.ajaxSubmit(get_transaction_items, params, function(response){
        var $transaction_form = $('._delete_transaction_form'); 
        var $credit_table = $('._credits_delete_table'); 
        var $debit_table = $('._debits_delete_table'); 
        var $services_control_table = $('._services_control_delete_table'); 
        var $credits_title = $('._delete_credits_title'); 
        var $debits_title = $('._delete_debits_title'); 
        var $services_control_title = $('._delete_services_control_title'); 
        
        $credits_title.text('Créditos de transacción nro: ' + transaction_id);
        $debits_title.text('Débitos de transacción nro: ' + transaction_id);
        $services_control_title.text('Control de Servicios de transacción nro: ' + transaction_id);
        
        $credit_table.find('tbody tr').not(':first').remove();
        $debit_table.find('tbody tr').not(':first').remove();
        $services_control_table.find('tbody tr').not(':first').remove();
        
        var onsubmit = 'modals.confirmDeleteTransaction(' + transaction_id + ',\''+ table + '\')';
        $transaction_form.attr('onsubmit', onsubmit);
        
        var credits = response.credits;
        var debits = response.debits;
        var services_control = response.services_control;
        
        // Load transaction credits
        if(credits.length > 0){
            for (x = 0; x < credits.length; x++) {
            
                if($credit_table.find('._reg_entity_' + credits[x].id).is('*')){
                    $credit_table.find('._reg_entity_' + credits[x].id).remove();
                }
    
                var $new_row = $('<tr class="_reg_entity_' + credits[x].id + '">');

                $.each(credits[x], function(index, value) {
                    if(index !== 'id' && index !== 'print' && index !== 'transaction_id'){
                        var $new_oolumn = $('<td>' + value + '</td>');
                        $new_row.append($new_oolumn);
                    }
                });
   
                var $actions_column = $('<td>');
                var $delete_action = $('<a title="Eliminar" onclick="modals.deleteEntityModal(' + credits[x].id + ',\'creditos\',\'cred_id\',\'credito\')" href="javascript:;" class="glyphicon glyphicon-trash"></a>');
   
                $actions_column.append('&nbsp;');
                $actions_column.append($delete_action);

                $new_row.append($actions_column);
                $new_row.insertAfter($credit_table.find('tbody tr:first'));
            }
        }else{
            general_scripts.noRecordsTable('credits_delete');
        }
        
        // Load transaction debits
        if(debits.length > 0){
            for (x = 0; x < debits.length; x++) {
            
                var $new_row = $('<tr class="_reg_entity_' + debits[x].id + '">');

                $.each(debits[x], function(index, value) {
                    if(index !== 'id' && index !== 'print' && index !== 'transaction_id'){
                        var $new_oolumn = $('<td>' + value + '</td>');
                        $new_row.append($new_oolumn);
                    }
                });
   
                var $actions_column = $('<td>');
                var $delete_action = $('<a title="Eliminar" onclick="modals.deleteEntityModal(' + debits[x].id + ',\'debitos\',\'deb_id\',\'debito\')" href="javascript:;" class="glyphicon glyphicon-trash"></a>');
   
                $actions_column.append('&nbsp;');
                $actions_column.append($delete_action);
   
                $new_row.append($actions_column);
                $new_row.insertAfter($debit_table.find('tbody tr:first'));
            }
        }else{
            general_scripts.noRecordsTable('debits_delete');
        }
        
        if(services_control.length > 0){
            for (x = 0; x < services_control.length; x++) {
            
                var $new_row = $('<tr class="_reg_entity_' + services_control[x].id + '">');

                $.each(services_control[x], function(index, value) {
                    if(index !== 'id' && index !== 'print' && index !== 'transaction_id'){
                        var $new_oolumn = $('<td>' + value + '</td>');
                        $new_row.append($new_oolumn);
                    }
                });
   
                var $actions_column = $('<td>');
                var $delete_action = $('<a title="Eliminar" onclick="modals.deleteEntityModal(' + services_control[x].id + ',\'services_control\',\'id\',\'control de servicio\')" href="javascript:;" class="glyphicon glyphicon-trash"></a>');
   
                $actions_column.append('&nbsp;');
                $actions_column.append($delete_action);
   
                $new_row.append($actions_column);
                $new_row.insertAfter($services_control_table.find('tbody tr:first'));
            }
        }else{
            general_scripts.noRecordsTable('services_control_delete');
        }
    });
    
    modals.$deleteTransactionModal.modal('show');
};

modals.reset = function($modal){
    // reset texts
    $modal.find('._description').empty();
    $modal.find('._title').empty();
    $modal.find('._media_content').empty();
    // reset actions
    $modal.find('._confirm').removeAttr('onclick');
}

$(function(){
    modals.init(); 
});