<form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post('<?= site_url('manager/designar') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
    <h2><?= isset($row) ? 'Editar' . ' ' . 'Designacion' : 'Agregar' . ' ' . 'Designacion' ?></h2>

    <div class="msg_display"></div>
    <div class="pasos">
        <div class="pri_title">Primer paso: </div><span class="expli">Seleccione las categorias de la jornada</span>
        <div class="select_dest">
            <?
            if ($cates->num_rows() > 0) {
                foreach ($cates->result() as $row) {
                    ?>
                    <label class="checkbox inline">
                        <input onclick="change(this)"type="checkbox" id="inlineCheckbox1" name="<?= $row->aran_cate ?>"value="0"> <?= $row->aran_cate ?>
                    </label>
                <? }
            }
            ?>
        </div>

    </div>

    <div style="width: 301px !important;margin-top:23px;"id="row_but" class="row-fluid">
        <button id="buttons_cli" class="btn btn-primary">Designar</button>
        <a id="buttons_cli" class="btn" href="<?= site_url('designaciones') ?>"><?= 'Cancelar' ?></a>
    </div>
</form>
<script>
    function change(input){
        if($(input).val()==0){
            $(input).val(1);
        }else{
            $(input).val(0);
        }
    }
</script>    