
/*
 * Project: Cleanbox
 * Document: loading
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var loading = {
    show:function(){
        $('#loading').fadeIn()
    },
    hide:function(){
        $('#loading').fadeOut()
    }
};

var loading_list = {
    show:function(){
        $('#loading_list').fadeIn()
    },
    hide:function(){
        $('#loading_list').fadeOut()
    }
};

$(document).ready(function(){
    $('body').append(LOADING_HTML);
    $('._loading_list').append(LOADING_LIST_HTML);
});