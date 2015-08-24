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
$(document).ready(function(){
    /*Paginadores*/    
    var currentPosition = 0;
    var slideWidth = 453;
    var slides = $('.photo_slide');
    var numberOfSlides = slides.length;

    // Remove scrollbar in JS
    $('.gallery_cont').css('overflow', 'hidden');

    // Wrap all .slides with #slideInner div
    slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
    .css({
        'float' : 'left',
        'width' : slideWidth
    });

    // Set #slideInner width equal to total width of all slides
    $('#slideInner').css('width', slideWidth * numberOfSlides);

    // Hide left arrow control on first load
    manageControls(currentPosition);

    // Create event listeners for .controls clicks
    $('.control')
    .bind('click', function(){
        // Determine new position
        currentPosition = ($(this).attr('id')=='rightControl')
        ? currentPosition+1 : currentPosition-1;

        // Hide / show controls
        manageControls(currentPosition);
        // Move slideInner using margin-left
        $('#slideInner').animate({
            'marginLeft' : slideWidth*(-currentPosition)
        });
    });

    // manageControls: Hides and shows controls depending on currentPosition
    function manageControls(position){
        // Hide left arrow if position is first slide
        if(position==0){
            $('#leftControl').hide()
        }
        else{
            $('#leftControl').show()
        }
        // Hide right arrow if position is last slide
        if(position==numberOfSlides-1){
            $('#rightControl').hide()
        }
        else{
            $('#rightControl').show()
        }
    }
    
});

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
function ajax(url,show){
    $.ajax({
        url:url,
        type:'GET',
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
            if(R.error !=1){
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
                $(show).removeClass('error');
                $(form).fadeOut();         
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
        success:function(R){
            if(R.js != '') eval(R.js);
            if(R.error==1){ 
                $('.msg_display').html(R.html).addClass('error');
            }else{
                if (R.error==5){   
                    $('.msg_display span').html(R.mensaje_error);
                }else{
                    $(show).html(R.html);
                    $('.msg_display').addClass('success').removeClass('error');    
                }
                
            }            
        }
    });
    return false;
}
