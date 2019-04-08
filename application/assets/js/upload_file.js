$(function(){

  $('#busines_logo').dmUploader({ //
    url: upload_image_from_file,
    maxFileSize: 8000000,
    multiple: false,
    allowedTypes: "image/*",
    extFilter: ["jpg", "jpeg", "png"],
    extraData: {
      "folder": $('#busines_logo').data('folder')
    }, 
    onDragEnter: function(){
      // Happens when dragging something over the DnD area
      this.addClass('active');
    },
    onDragLeave: function(){
      // Happens when dragging something OUT of the DnD area
      this.removeClass('active');
    },
    onNewFile: function(id, file){
      if (typeof FileReader !== 'undefined'){
      	var reader = new FileReader();

        $('._no_image').remove();
        $('._image_logo').remove();

        var img = $('<img height="200" width="200" class="img_shadow _image_logo" alt="logo"/>');

        reader.onload = function (e) {
          img.attr('src', e.target.result);
        };

        $('._logo').append(img);
 
        reader.readAsDataURL(file);
        
        cleanbox_alert.showAlertSuccess('Logo guardado!');

        // var $input_img = $('<input value="' + response.image.value + '" id="image" type="hidden" name="logo"/>');
        // var $img = $('<img src="' + response.image.src + '" class="img_shadow _image_logo" alt="logo"/>');
        // var $remove = $('<a onclick="general_scripts.removeImage(\''+folder+'\', \'._logo\');" title="Eliminar" class="close _remove_image" href="javascript:;">[&times;]</a>');
        // $(container).append($input_img);
        // $(container).append($img);
        // $(container).append($remove);
        // $('._remove_image').show();
      

        // addImage(id, file.name, img);
      }
      // When a new file is added using the file selector or the DnD area
    },
    onBeforeUpload: function(id){
      // about tho start uploading a file\
      updateStatusImage(id, 'uploading', 'Cargando...');
      updateImageProgress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      updateStatusImage(id, 'warning', 'Cancelado');
      updateImageProgress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      updateImageProgress(id, percent);
    },
    onUploadSuccess: function(id, data){
      // A file was successfully uploaded
      updateStatusImage(id, 'success', 'Imagen subida', data);
      updateImageProgress(id, 100, 'success', false);
    },
    onUploadError: function(id, xhr, status, message){
      updateStatusImage(id, 'danger', message);
      updateImageProgress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      alert('Este browser no soporta el plugin para cargar imagenes, recomendamos Chrome')
      // When the browser doesn't support this plugin :(
    },
    onFileSizeError: function(file){
      alert('El tamaño del archivo sobrepasa el limite de 4MB')
    }
  });


// Creates a new file and add it to our list
function addImage(id, file, img){
  var template = $('#files-template').text();
  template = template.replace('%%filename%%', file);

  template = $(template);
  template.prop('id', 'uploaderFile' + id);
  template.data('file-id', id);
  
  var fancyid = 'fancy_uploaderFile_'+id;
  var a = $('<a data-fancybox="gallery" href="'+img.attr('src')+'" id="'+fancyid+'">');
  a.append(img);
  
  template.prepend(a);
  
  var remove_button = template.find('._remove_image');
  remove_button.attr('data-id', id);
  remove_button.attr('onclick', 'removeImage($(this))')

  $('#files').find('li.empty').fadeOut(); // remove the 'no files yet'
  $('#files').prepend(template);
}

// Changes the status messages on our list
function updateStatusImage(id, status, message, data = false){
  if(data){
    if(data.status){
      var fancyid = 'fancy_uploaderFile_'+id;
      $('#' + fancyid).attr('href', data.url);
      updatePictureArray();
    }
  }

  $('#uploaderFile' + id).find('span').html(message).prop('class', 'status text-' + status);
  if(status == 'success'){
    $('#uploaderFile' + id).find('.progress-bar').css('background-color', 'limegreen');
  }
}

// Updates a file progress, depending on the parameters it may animate it or change the color.
function updateImageProgress(id, percent, color, active){
  color = (typeof color === 'undefined' ? false : color);
  active = (typeof active === 'undefined' ? true : active);

  var bar = $('#uploaderFile' + id).find('div.progress-bar');

  bar.width(percent + '%').attr('aria-valuenow', percent);
  bar.toggleClass('progress-bar-striped progress-bar-animated', active);

  if (percent === 0){
    bar.html('');
  } else {
    bar.html(percent + '%');
  }

  if (color !== false){
    bar.removeClass('bg-success bg-info bg-warning bg-danger');
    bar.addClass('bg-' + color);
  }
}

});

function removeImage($button){
  $button.parent('li.media').remove();
  updatePictureArray();
}

function updatePictureArray(){   
  pictures = [];
  $('[data-fancybox="gallery"]').each(function(index){
    if($(this).attr('href')){
      pictures.push($(this).attr('href'));
    }
  });
  return pictures;
}
