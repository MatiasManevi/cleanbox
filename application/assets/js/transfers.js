
/*
 * Project: Cleanbox
 * Document: transfers
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var transfers = new Object();

transfers.transferToSafeBox = function (){
    var amount = $('._transfer_to_safebox').val();
    var reason = $('._reason_transfer_to_safebox').val();
    
    general_scripts.ajaxSubmit(transfer_to_safebox, {
        'amount' : amount,
        'reason' : reason
    }, function(response){
        $('._transfer_to_safebox').val('');
        $('._reason_transfer_to_safebox').val('');
        $('._safebox').html(response.safebox);
        $('._cash').html(response.cash);
        cleanbox_alert.showAlertSuccess(response.success);
    });
};

transfers.transferToCash = function (){
    var amount = $('._transfer_to_cash').val();
    var reason = $('._reason_transfer_to_cash').val();
    
    general_scripts.ajaxSubmit(transfer_to_cash, {
        'amount' : amount,
        'reason' : reason
    }, function(response){
        $('._transfer_to_cash').val('');
        $('._reason_transfer_to_cash').val('');
        $('._safebox').html(response.safebox);
        $('._cash').html(response.cash);
        cleanbox_alert.showAlertSuccess(response.success);
    });
};