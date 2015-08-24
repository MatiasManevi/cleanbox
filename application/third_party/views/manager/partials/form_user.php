<?php
/*
  Document   : form_user
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>

<form  action="<?= site_url('manager/save_user') ?>" onsubmit="submit_edit(this,true);return false;" class="addedit_form" enctype="multipart/form-data">
    <input name="user_id" type="hidden" value="<?= (isset($user) && $user->user_id ) ? $user->user_id : '' ?>"/>
    
    <div class="field">
        <label>Nombre</label>
        <input name="user_firstname" type="text" value="<?= (isset($user) && $user->user_firstname ) ? $user->user_firstname : '' ?>"/>
    </div>
    
    <div class="field">
        <label>Apellido</label>
        <input name="user_lastname" type="text" value="<?= (isset($user) && $user->user_lastname ) ? $user->user_lastname : '' ?>"/>
    </div>
    <div class="field">
        <label>Email</label>
        <input name="user_email" type="text" value="<?= (isset($user) && $user->user_email) ? $user->user_email : '' ?>"/>
    </div>
    
    
    <button><?= isset($user) ? 'Guardar' : 'Agregar' ?></button>
</form>