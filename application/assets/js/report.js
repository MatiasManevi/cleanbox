
/*
 * Project: Cleanbox
 * Document: report
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var report = new Object();

report.buildReportFromList = function (url, param) {
    loading.show();
    if(param){
        window.location.href = url + '/' + param;
    }else{
        window.location.href = url;
    }
};

report.buildReport = function (url, form, container){
    var params = {};
    
    if(typeof form !== 'undefined'){
        params = $(form).serialize();
    }
    
    general_scripts.ajaxSubmit(url, params, function(response){
        
        if(typeof container === 'undefined'){
            container = '._container';
        }
    
        $(container).html(response.html);
    });
};

report.paintRow = function ($row){
    if($row.hasClass('_incorrect') || !$row.hasClass('_correct')){
        $row.css('background','darkseagreen');
        $row.addClass('_correct');
        $row.removeClass('_incorrect');
    }else if($row.hasClass('_correct')){
        $row.css('background','darksalmon');
        $row.addClass('_incorrect');
        $row.removeClass('_correct');
    }
};

