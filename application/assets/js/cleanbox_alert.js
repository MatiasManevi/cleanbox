
/*
 * Project: Cleanbox
 * Document: cleanbox_alert
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var cleanbox_alert = new Object();

cleanbox_alert.showAlertSuccess = function (message) {
    if(!$('.alert-success.alert-cleanbox').is(':visible')){
        $('.alert-success.alert-cleanbox').find('._message').html(message);
        $('.alert-success.alert-cleanbox').fadeIn(500, function () {
            setTimeout(function () {
                $('.alert-success.alert-cleanbox').fadeOut(3500);
            }, 1000);
        });
    }
};

cleanbox_alert.showAlertInfo = function (message) {
    if(!$('.alert-info.alert-cleanbox').is(':visible')){
        $('.alert-info.alert-cleanbox').find('._message').html(message);
        $('.alert-info.alert-cleanbox').fadeIn(500, function () {
            setTimeout(function () {
                $('.alert-info.alert-cleanbox').fadeOut(4500);
            }, 1000);
        });
    }
};

cleanbox_alert.showAlertError = function (message) {
    if(!$('.alert-danger.alert-cleanbox').is(':visible')){
        $('.alert-danger.alert-cleanbox').find('._message').html(message);
        $('.alert-danger.alert-cleanbox').fadeIn(500, function () {
            setTimeout(function () {
                $('.alert-danger.alert-cleanbox').fadeOut(4500);
            }, 1000);
        });
    }
};