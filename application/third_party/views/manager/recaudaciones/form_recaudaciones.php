<form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post('<?= site_url('manager/save_recaudacion') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
    <input name="rec_id" type="hidden" value="<?= (isset($row) && $row->rec_id ) ? $row->rec_id : '' ?>"/>
    <h2><?= isset($row) ? 'Editar' . ' ' . 'Recaudacion' : 'Agregar' . ' ' . 'Recaudacion' ?></h2>
    <div class="msg_display"></div>

    <div class="row">
        <div class="bloque1">
          
            <div id="cli_item" class="field span3">
                <label>Año</label>
                <input disabled="1" name="rec_ano" type="text" value="<?= (isset($row) && $row->rec_ano ) ? $row->rec_ano : '' ?>"/>
            </div>
            <div id="cli_item" class="field span3">
                <label>Recaudado ( $ )</label>
                <input disabled="1" name="rec_monto" type="text" value="<?= (isset($row) && $row->rec_monto ) ? $row->rec_monto : '' ?>"/>
            </div>
    </div>
    </div>
    <div style="margin-top:23px;  width: 294px;"id="row_but" class="row-fluid">
        <button id="buttons_cli" class="btn btn-primary"><?= isset($row) ? 'Guardar' : 'Agregar' ?></button>
        <a id="buttons_cli" class="btn" href="<?= site_url('recaudaciones') ?>"><?= 'Cancelar' ?></a>
    </div>
</form>


