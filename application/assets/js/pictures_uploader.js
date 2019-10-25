var arr_pictures = [];

$(function(){
	
  if(!arr_pictures.length){
    capturePics();
  }

  $('#image_uploader').dmUploader({
    url: upload_image_from_file,
    maxFileSize: 4000000,
    allowedTypes: "image/*",
    extFilter: ["jpg", "jpeg", "png", "gif"],
    extraData: {
      "folder": $('#image_uploader').data('folder')
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

        var img = $('<img height="100px" width="100px" class="mr-3 mb-2 preview-img"/>');

        reader.onload = function (e) {
          img.attr('src', e.target.result);
        }

        reader.readAsDataURL(file);

        addPicture(id, file.name, img);
      }
      // When a new file is added using the file selector or the DnD area
    },
    onBeforeUpload: function(id){
      // about tho start uploading a file\
      updateStatusPicture(id, 'uploading', 'Cargando...');
      updateImageProgress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      updateStatusPicture(id, 'warning', 'Cancelado');
      updateImageProgress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      updateImageProgress(id, percent);
    },
    onUploadSuccess: function(id, data){

      // A file was successfully uploaded
      data = JSON.parse(data);
      updateStatusPicture(id, 'success', 'Imagen subida');
      addDataToPicture(id, data, 'onUploadSuccess');

      updateImageProgress(id, 100, 'success', false);
    },
    onUploadError: function(id, xhr, status, message){
      updateStatusPicture(id, 'danger', message);
      updateImageProgress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      cleanbox_alert.showAlertInfo('Este browser no soporta el plugin para cargar imagenes, recomendamos Chrome');
    },
    onFileSizeError: function(file){
      cleanbox_alert.showAlertInfo('El tamaño del archivo sobrepasa el limite de 4MB');
    }
  });
});

// Creates a new file and add it to our list
function addPicture(id, file, img){
  var template = $('#files-template').text();

  template = $(template);
  template.prop('id', 'uploaderFile' + id);
  template.data('file-id', id);

  var fancyid = 'fancy_uploaderFile_'+id;
  var a = $('<a class="picture_box" id="'+fancyid+'" data-fancybox="gallery" href="'+img.prop('src')+'">');
  a.append(img);
  
  template.prepend(a);
  
  $('#files').find('li.empty').fadeOut(); // remove the 'no files yet'
  $('#files').prepend(template);
}

// Changes the status messages on our list
function updateStatusPicture(id, status, message){
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

function capturePics() {
  var pictures = $('._pictures').val();

  if(typeof pictures !== 'undefined' && pictures.length){
    var added = '';
    // no se xq esta linea me duplica imagenes
    // con added lo contralmos
    arr_pictures = pictures.split(",");
    aux = [];
    for (var i = arr_pictures.length - 1; i >= 0; i--) {

      var file = arr_pictures[i];
      if(arr_pictures[i] && arr_pictures[i].length && !added.includes(file)){
        aux.push(arr_pictures[i]);
        var picture = img_url + arr_pictures[i];

        added = added + file;

        var img = $('<img height="64px" width="64px" class="mr-3 mb-2 preview-img"/>');

        img.attr('src', picture);

        addPicture(i, file, img);

        var data = {
          image:{
            path: file,
            full_path: picture
          }
        };

        updateStatusPicture(i, 'success', 'Imagen subida');
        addDataToPicture(i, data, 'capturePics');
      }
    }
    arr_pictures = aux;
  }else{
    arr_pictures = [];
  }
}

function addDataToPicture(id, data, from) {
  var x = -1;
  $('#fancy_uploaderFile_'+id).prop('href', data.image.full_path);

  arr_pictures.forEach(function(picture, i){
    if(data.image.full_path.includes(picture)){
      x = i;
    }
  });

  if(x === -1){
    arr_pictures.push(data.image.path);
  }

  $('._pictures').val('');
  $('._pictures').val(arr_pictures.join());
}

function removeImage($button){
  var $li = $button.parent('li.media');
  var url = $li.find('a').prop('href');
  
  arr_pictures.forEach(function(picture, i){
    if(picture.length > 1 && url.includes(picture)){
      index = i
    }
  });

  if(typeof index !== 'undefined'){
    $li.remove();
    arr_pictures.splice(index, 1);
    $('._pictures').val('');
    $('._pictures').val(arr_pictures.join());
  }
}