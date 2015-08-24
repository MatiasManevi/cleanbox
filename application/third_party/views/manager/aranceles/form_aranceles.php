<form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post('<?= site_url('manager/save_aranceles') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
    <input name="aran_id" type="hidden" value="<?= (isset($row) && $row->aran_id ) ? $row->aran_id : '' ?>"/>
    <h2><?= isset($row) ? 'Editar' . ' ' . 'Arancel' : 'Agregar' . ' ' . 'Arancel' ?></h2>
    <div class="msg_display"></div>

    <div class="row">
        <div class="bloque1">
            <div id="cli_item" class="field span3">
                <label>Categoria</label>
                <input name="aran_cate" type="text" value="<?= (isset($row) && $row->aran_cate ) ? $row->aran_cate : '' ?>"/>
            </div>
            <div id="cli_item" class="field span3">
                <label>Precio</label>
                <input name="aran_price" type="text" value="<?= (isset($row) && $row->aran_price ) ? $row->aran_price : '' ?>"/>
            </div>
    </div>
    </div>
    <div style="margin-top:23px;width: 262px;"id="row_but" class="row-fluid">
        <button id="buttons_cli" class="btn btn-primary"><?= isset($row) ? 'Guardar' : 'Agregar' ?></button>
        <a id="buttons_cli" class="btn" href="<?= site_url('aranceles') ?>"><?= 'Cancelar' ?></a>
    </div>
</form>


