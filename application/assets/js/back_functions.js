var loading={
    show:function(){
        $('#loading').fadeIn()
    },
    hide:function(){
        $('#loading').hide()
    }
};


$(document).ready(function(){
    $('body').append(LOADING_HTML);
    loading.hide();
});
var n=0;
$('#ccnav a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})  
$(document).ready(function(){     
    $('.btn').click(function(e) {
        if(e.target.id == "back_fader")
        {
            $('#back_fader').hide();
            $('#popup').hide();
        }
    });
}); 
$(document).ready(function(){     
    $('.btn').click(function(e) {
        if(e.target.id == "back_fader1")
        {
            $('#back_fader1').hide();
            $('#popup1').hide();
        }
    });
}); 
$(document).ready(function(){     
    $('.btn').click(function(e) {
        if(e.target.id == "back_fader2")
        {
            $('#back_fader2').hide();
            $('#popup2').hide();
        }
    });
}); 
function popup(){
    document.getElementById('back_fader').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
}
function popup1(){
    document.getElementById('back_fader1').style.display = 'block';
    document.getElementById('popup1').style.display = 'block';
}
function popup2(){
    document.getElementById('back_fader2').style.display = 'block';
    document.getElementById('popup2').style.display = 'block';
}
function toggle_element(forma){
    if(forma == 'Efectivo'){
        undo_campos();
    }else{
        do_campos();
    }
}
function do_campos(){
    jQuery('<div/>', {
        id: 'campos',
        'class' : 'bloque'
    }).appendTo('.forma_pago_select').hide().fadeIn(700);
    //Creo los inputs Concepto y Monto
    jQuery('<input/>', {
        id: 'nro_cheque',
        name: 'nro_cheque',
        type: 'text',
        style : 'margin-top: 5px;margin-right: 5px;font-size: 16px;width: 100%;float: left;',
        'class': 'form-control ui-autocomplete-input',
        placeholder: 'Nro Cheque'                 
    }).appendTo('#campos');
    //Creo los inputs Concepto y Monto
    jQuery('<input/>', {
        id: 'banco',
        name: 'banco',
        type: 'text',
        style : 'margin-top: 5px;margin-right: 5px;font-size: 16px;width: 100%;float: left;',
        'class': 'form-control ui-autocomplete-input',
        placeholder: 'Banco'                 
    }).appendTo('#campos');
}
function undo_campos(){
    $("#campos").remove();
}
function load_section(li,url,sublist){
    $('.side_menu').find('li').removeClass('active');
    if(sublist){
        $(li).parents('.hassublist').addClass('active');
        $(li).parents('.sublist').show();
    }    
    $(li).addClass('active');
    $.ajax({
        url:url,
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            if(trim(R)=='') window.location='login';
            $('.back_main_content').html(R);
            loading.hide();
        }
    });
    n=0;
}

function load_cont(url){

    $.ajax({
        url:url,
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            if(trim(R)=='') window.location='login';
            $('.back_main_content').html(R);
            loading.hide();
        }
    });
    n=0;
}

function get_text_ckeditor(textarea){
    var editor = CKEDITOR.instances[textarea]
    if(editor){
        return CKEDITOR.instances[textarea].getData();
    }else{
        return $('#'+textarea).html();
    }
}
function submit_edit(form){
    if($(form).find('textarea')){
        $.each($(form).find('textarea'),function(){
            if(!$(this).hasClass('noeditor') && !$(this).is(':visible')){
                $(this).html(get_text_ckeditor($(this).attr('id')));
            }
        //alert($(this).attr('name')+':'+$(this).html());
        });
    }
    $.ajax({
        url:$(form).attr('action'),
        data:'&'+$(form).serialize(),
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            if(R.indexOf('@ERROR@ ') == -1){
                if($('.addedit_form').length > 0){
                    $('.back_main_content').html(R);
                }else{
                    $('.msg_display').html(R);
                    $('.msg_display').fadeIn(300).delay(800).fadeOut(2500);
                }
            }else{
                R=R.replace('@ERROR@ ','');
                alert(R);
                if($('.addedit_form').length == 0){
                    $('.msg_display').html('Data error uploading changes, try again');
                    $('.msg_display').fadeIn(300).delay(800).fadeOut(2500);
                }
            }
            loading.hide();            
        }
    });
    return false;
}
function submit_delete(url){
    $.ajax({
        url:url,
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){          
            $('.back_main_content').html(R);
            loading.hide();            
        }
    });
    return false;
}
function load_cal(url){
    $.ajax({
        url:url,
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            $('#calmain').replaceWith(R);
            loading.hide();
        }
    });
    return false;
}
function change_lang(a,lang){
    $('.sections_sel').find('li').removeClass('active');
    $(a).parent().addClass('active');
    $('.section_edit').hide();
    $(lang).slideDown()
}

function submit_search(form){
    $.ajax({
        url:$(form).attr('action'),
        data:'&'+$(form).serialize(),
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            if(R.indexOf('@ERROR@ ') == -1){
                $('.back_main_content').html(R);
            }else{
                R=R.replace('@ERROR@ ','');
                alert(R);
            }
            loading.hide();

        }
    });
    return false;
}

function load_edit(url){
    $.ajax({
        url:url,
        type:'POST',
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            if(R.js != '') eval(R.js);
            $('.contenedor_centro').html(R.html);
            if(R.blur != '') eval(R.blur);
            loading.hide();
        }
    });
    return false;
}


function submit_login(form){
    $.ajax({
        url:$(form).attr('action'),
        data:'&'+$(form).serialize(),
        type:'POST',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Transaction error, try again');
            loading.hide();
        },
        success:function(R){
            if(R.indexOf('@ERROR@ ') != -1){
                R=R.replace('@ERROR@ ','');
                alert(R);
            }
            loading.hide();

        }
    });
    return false;
}
function del(id,url, show){
    //$('table').find('reg_'+id);
    $.ajax({
        url:url,
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Ocurrió un error enviando su solicitud, por favor intente de nuevo');
            loading.hide();
        },
        success:function(R){
            if(R.js != '') eval(R.js);
            if(show == undefined) alert(R.html);
            else {
                $(show).html(R.html);
                $('#contenedor_centro').html(R.presupuesto);
            }
            
            if(R.error==1 && show != undefined){
                $(show).addClass('error');
            }else{
                $('#contenedor_centro').html(R.presupuesto);
                $('table').find('.reg_'+id).remove();
                $(show).addClass('success').removeClass('error');
            }
            
            loading.hide();
        }
    });
    return false;
}

/*
 * Remove empty spaces from a string (like php trim function)
 */
function trim(text) {
    text= text.replace(/ /g,""); //elimina espacios a izquierda y derecha
    text= text.replace(/\n\r/g,"");
    text= text.replace(/\n/g,"");
    return text;
}

function request_post_pop(url,form,show){
    get_texts_ckeditor(form);
    $.ajax({
        url:url,
        type:'POST',
        data: '&'+$(form).serialize(),
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        success:function(R){
            if(R.js != '') eval(R.js);
            if(R.error==1){
                $('#com_display1').html(R.html).addClass('alert alert-danger');
            }else{
                if (R.error==5){   
                    $('#com_display1 span').html(R.mensaje_error);
                }else{
                    $(show).html(R.html);                   
                    $('#com_display1').html(R.success);
                    $('#com_display1').addClass('alert alert-success').removeClass('alert alert-danger');   
                }
                
            }  
            loading.hide();
        }
    });
    return false;
}
function request_post(url,form,show){
    get_texts_ckeditor(form);
    $.ajax({
        url:url,
        type:'POST',
        data: '&'+$(form).serialize(),
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        success:function(R){
            if(R.js != '') eval(R.js);
            if(R.error==1){
                $('#com_display').html(R.html).addClass('alert alert-danger');
            }else{
                if (R.error==5){   
                    $('#com_display span').html(R.mensaje_error);
                }else{
                    $(show).html(R.html);                   
                    $('#com_display').html(R.success);
                    $('#com_display').addClass('alert alert-success').removeClass('alert alert-danger');   
                }
                
            }  
            loading.hide();
        }
    });
    return false;
}

function request_post_cuenta(url,form,show){
    get_texts_ckeditor(form);
    $.ajax({
        url:url,
        type:'POST',
        data: '&'+$(form).serialize(),
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        success:function(R){
            if(R.js != '') eval(R.js);
            if(R.error==1 || R.error==2){
                $('#com_display').html(R.html).addClass('alert alert-danger');
                $('#com_display').removeClass('msg_display');
            }else{
                if (R.error==5){   
                    $('#com_display span').html(R.mensaje_error);
                }else{
                    $(show).html(R.html);            
                    $('#com_display').addClass('alert alert-success').removeClass('alert alert-danger');   
                    $('#com_display').removeClass('msg_display');
                }             
            }  
            loading.hide();
        }
    });
    return false;
}







function get_texts_ckeditor(form){
    if($(form).find('textarea')){
        $.each($(form).find('textarea'),function(){
            if(!$(this).hasClass('noeditor') && !$(this).is(':visible')){
                
                var editor =CKEDITOR.instances[$(this).attr('id')]
                if(editor){
                    $(this).html(CKEDITOR.instances[$(this).attr('id')].getData());
                }else{
                    $(this).html($('#'+$(this).attr('id')).html());
                }
            }
        //alert($(this).attr('name')+':'+$(this).html());
        });
    }
}
function request_redirect(url,data,show){
    $.ajax({
        url:url,
        type:'POST',
        data: '&'+(data != undefined ? data : ''),
        dataType: 'json',
        beforeSend  :function(){
            loading.show();
        },
        error:function(){
            alert('Ocurrió un error enviando su solicitud, por favor intente de nuevo');
            loading.hide();
        },        
        success:function(R){
            eval(R.js);
            if(R.html != ''){
                if(show == undefined) alert(R.html);
                else $(show).html(R.html);
            }
            if(R.error==1 && show != undefined){
                $(show).addClass('error');
            }
            if(R.success==1){
                $(show).addClass('success').removeClass('error');
            }  
            loading.hide();
        }
    });
    return false;
}
function request(url,data,show){
    $.ajax({
        url:url,
        type:'POST',
        data: '&'+(data != undefined ? data : ''),
        dataType: 'json',
        
        success:function(R){
            eval(R.js);
            if(R.html != ''){
                if(show == undefined) alert(R.html);
                else $(show).html(R.html);
            }
            if(R.error==1 && show != undefined){
                $(show).addClass('error');
            }
            if(R.success==1){
                $(show).addClass('success').removeClass('error');
            }  
           
        }
    });
    return false;
}
function request_informe(url,data,show){
    $.ajax({
        url:url,
        type:'POST',
        data: '&'+(data != undefined ? data : ''),
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        success:function(R){
            eval(R.js);
            if(R.html != ''){
                if(show == undefined) alert(R.html);
                else $(show).html(R.html);
            }
            if(R.error==1 && show != undefined){
                $(show).addClass('error');
            }
            if(R.success==1){
                $(show).addClass('success').removeClass('error');
            }  
            loading.hide();
        }
    });
    return false;
}
function submit_contact(url,form,show){
    $.ajax({
        url:url,
        data:'&'+$(form).serialize(),
        type:'POST',
        dataType: 'json',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Ocurrió un error enviando su solicitud, por favor intente de nuevo');
            loading.hide();
        },
        success:function(R){
            eval(R.js);
            if(show == undefined) alert(R.html);
            else $(show).html(R.html);
            
            if(R.error==1 && show != undefined){
                $(show).addClass('error');
            }
            if(R.error != 1){
                $(show).addClass('Mensaje enviado con exito').removeClass('error');
                $(form).fadeOut();         
            }          
            loading.hide();
        }
    });
    return false;  
}
function change_gallery(type){
    if(type == 'flash'){
        $('.flash_field').show();
        $('.image_field').hide();
    }else{
        $('.flash_field').hide();
        $('.image_field').show();
    }
}
function load_popup_view(view){
    $.ajax({
        url:view,
        type:'GET',
        beforeSend:function(){
            loading.show();
        },
        error:function(){
            alert('Ocurrió un error enviando su solicitud, por favor intente de nuevo');
            loading.hide();
        },
        success:function(R){
            if(R.indexOf('@ERROR@ ') == -1){
                $('#pop').html(R);
   
                $("#pop").css("left","50%");
                $("#pop").css("top", "50%");

                $("#pop").css("margin-left","-"+($("#pop").width()/2)+"px");
                $("#pop").css("margin-top", "-"+($("#pop").height()/2)+"px");
 
                $("#pop").css("border-radius","4px 4px 4px 4px");
                $('#pop_back').show(100,function(){
                    $('#pop').fadeIn(200);
                });
            }else{
                R=R.replace('@ERROR@ ','');
                $('.error_display').html(R);
                $('.error_display').show();
                $.scrollTo('.error_display',50.0);
            }
            loading.hide();
        }
    });
    return false;
}
function close_pop_up(){
    $('#pop, #pop img,#img_th,#img_th_list ').hide(500,function(){
        $('#pop_back').hide(function(){
            $('#pop img ').hide();
        });
    });
}


