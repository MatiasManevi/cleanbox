<form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_user') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
    <input name="id" type="hidden" value="<?= (isset($row) && $row->id ) ? $row->id : '' ?>"/>
    <h2><?= isset($row) ? 'Editar' . ' ' . 'Usuario' : 'Agregar' . ' ' . 'Usuario' ?></h2>

    <div class="row">
        <div style="height: 206px;width:568px"class="bloque1">
            <div style="margin-left: 14px;margin-top: 6px;" id="" class="field span3">
                <label style=" float: left;">Nombre Usuario</label>
                <input value="<?= (isset($row) && $row->username ) ? $row->username : '' ?>" type="text" name="username" class="form-control ui-autocomplete-input"  placeholder="Usuario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>

            <div style="margin-left: 14px;" id="" class="field span3">
                <label style=" float: left;">Clave</label>
                <input value="<?= (isset($row) && $row->password ) ? $row->password : '' ?>" type="text" name="password" class="form-control ui-autocomplete-input"  placeholder="Clave" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div style=" margin-bottom: 25px;margin-left: 260px;margin-top: 23px;width: 294px;"id="row_but" class="row-fluid">
                <button style="line-height: 0px"id="buttons_cli" class="btn btn-primary"><?= isset($row) ? 'Guardar' : 'Agregar' ?></button>
                <a id="buttons_cli" class="btn" href="<?= site_url('admin') ?>"><?= 'Cancelar' ?></a>
            </div>      
            <div id="com_display">
                <span></span>
            </div>
        </div>
    </div>
</form>



