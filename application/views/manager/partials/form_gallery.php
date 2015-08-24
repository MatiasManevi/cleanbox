<?php
/*
  Document   : form_gallery
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>
<form action="<?= site_url('manager/save_gallery') ?>" class="addedit_form new_form" onsubmit="submit_edit(this);return false;" method="post">
    <input type="hidden" name="gall_id" value="<?= isset($gall_item) ? $gall_item->gall_id : '' ?>"/>
    <p class="title"><?= isset($gall_item) ? 'Editar Imagen' : 'Agregar Imagen' ?></p>
    
    <div class="field">
        <div id="section_image_container">
            <? if (isset($gall_item) && trim($gall_item->gall_image) != '') { ?>
                <span>Imagen actual</span>
                <img src="<?= img_url() . 'gallery/thumbs/' . $gall_item->gall_image ?>" alt="gallery image"/>
                <input type="hidden" name="gall_image" value="<?= $gall_item->gall_image ?>"/>
                <a href="javascript:;" onclick="request('<?= site_url('manager/del_gallery_image/' . $gall_item->gall_id) ?>','','#section_image_container');" title="Remove Image">[X]</a>
            <? } else { ?>
                <span>Imagen no encontrada</span>
            <? } ?>
        </div>
        <div id="button_uploader">
            <label for="Filedata">Cargar Imagen</label>
            <?php echo form_upload(array('name' => 'Filedata', 'id' => 'upload')); ?>
        </div>
    </div>
    <div class="field partial checkbox">
        <label>Estado</label>
        <div>
            <input type="radio" name="gall_enabled" value="1" <?= !isset($gall_item) || isset($gall_item) && $gall_item->gall_enabled ? 'checked="checked"' : '' ?>/>
            <label>Activa</label>
        </div>
        <div>
            <input type="radio" name="gall_enabled" value="0" <?= isset($gall_item) && $gall_item->gall_enabled == 0 ? 'checked="checked"' : '' ?>/>
            <label>Inactiva</label>
        </div>
    </div> 
    <div class="field partial">
        <label>Orden</label>
        <input type="text" name="gall_order" value="<?= isset($gall_item) ? $gall_item->gall_order : '' ?>"/>
    </div>
    <div class="language_links">
        <a href="javascript:;" onclick="$('.es').slideToggle()">Español</a>
        <a href="javascript:;" onclick="$('.en').slideToggle()">Inglés</a>
    </div>
    <div class="es lang_section" style="display:block;">
        <h3>Español</h3>
        <div class="field">
            <label>Nombre</label>
            <input type="text" name="gall_name_es" value="<?= isset($gall_item) ? $gall_item->gall_name_es : '' ?>"/>
        </div>
        <div class="field">
            <label>Descripción</label>
            <textarea id="gall_content_es" name="gall_content_es"><?= isset($gall_item) ? $gall_item->gall_content_es : '' ?></textarea>
            <?= form_ckeditor(array('id' => 'gall_content_es')); ?>
        </div>
    </div>
    <div class="en lang_section">
        <h3>Inglés</h3>
        <div class="field">
            <label>Nombre</label>
            <input type="text" name="gall_name_en" value="<?= isset($gall_item) ? $gall_item->gall_name_en : '' ?>"/>
        </div>
        <div class="field">
            <label>Descripción</label>
            <textarea id="gall_content_en" name="gall_content_en"><?= isset($gall_item) ? $gall_item->gall_content_en : '' ?></textarea>
            <?= form_ckeditor(array('id' => 'gall_content_en')); ?>
        </div>
    </div>


    <div class="field">
        <button><?= isset($gall_item) ? 'Guardar' : 'Agregar' ?></button>
    </div>
</form>


<script>
    $(document).ready(function(){
        $("#upload").uploadify({
            uploader: '<?php echo base_url(); ?>plugins/uploadify/uploadify.swf',
            script: '<?php echo base_url(); ?>plugins/uploadify/uploadify.php',
            cancelImg: '<?php echo base_url(); ?>plugins/uploadify/cancel.png',
            buttonImg: '<?php echo base_url(); ?>plugins/uploadify/button.jpg',
            folder: '/rooming4u/img/gallery/',
            scriptAccess: 'always',
            fileExt     : '*.jpg;*.gif;*.png',
            fileDesc    : 'Image Files',
            auto: true,
            multi: true,
            'onError' : function (a, b, c, d) {
                if (d.status == 404)
                    alert('Could not find upload script.');
                else if (d.type === "HTTP")
                    alert('error '+d.type+": "+d.status);
                else if (d.type ==="File Size")
                    alert(c.name+' '+d.type+' Limit: '+Math.round(d.sizeLimit/1024)+'KB');
                else
                    alert('error '+d.type+": "+d.text);
            },
            'onComplete'   : function (event, queueID, fileObj, response, data) {
                //Post response back to controller
                $.post('<?php echo site_url('manager/load_gallery_image' . (isset($gall_item) ? '/' . $gall_item->gall_id : '')); ?>',{filearray: response},function(info){
                    $("#section_image_container").html(info);  //Add response returned by controller
                });
            }
        });
        
    });
</script>
