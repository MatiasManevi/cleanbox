<?php
/*
  Document   : content
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>
<h2><?= $section->sect_name_es ?></h2>

<div class="msg_display"></div>

<div class="lang_section es " style="display:block;">
    <form action="<?= site_url('manager/save_section/' . $section->sect_id . '/es') ?>" onsubmit="submit_edit(this);return false;">
        <div class="field">
            <label for="sect_name_es">Titulo publicación</label>
            <input type="text" value="<?= $section->sect_name_es ?>" name="sect_name_es"/>
        </div>       
        <div class="">
            <button>Guardar</button>
        </div>
    </form>
</div>



