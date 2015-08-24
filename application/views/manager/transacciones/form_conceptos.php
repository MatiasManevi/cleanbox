<form style="overflow: visible;" action="javascript:;" onsubmit="request_post('<?= site_url('manager/save_conceptos_pop') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
    <input value="<?= (isset($row) && $row->conc_desc ) ? $row->conc_desc : '' ?>" type="text" id="conc_desc" name="conc_desc" class="form-control ui-autocomplete-input"  placeholder="Nuevo Concepto" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 24px;font-size: 16px;width: 403px;float: left;">
    <div class="selectores">
        <div class="forma_pago_select">
            <label style="margin-left: 5px; margin-bottom: 0px;">Tipo de Concepto</label>
            <select class="form-control ui-autocomplete-input" name="conc_tipo">
                <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->conc_tipo == 'Entrada' ? 'selected="selected"' : '' ) ?> value="Entrada">Entrada</option>
                <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->conc_tipo == 'Salida' ? 'selected="selected"' : '' ) ?>  value="Salida">Salida</option>
            </select>
        </div> 
        <div id="select" class="forma_pago_select">
            <label style="margin-left: 5px; margin-bottom: 0px;">Cuenta</label>
            <select class="form-control ui-autocomplete-input" name="conc_cc">
                <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_cc == 'cc_saldo' ? 'selected="selected"' : '' ) ?> value="cc_saldo">Principal</option>
                <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_cc == 'cc_varios' ? 'selected="selected"' : '' ) ?>  value="cc_varios">Secundaria</option>
            </select>
        </div>
        <div id="select" class="forma_pago_select">
            <label style="margin-left: 5px; margin-bottom: 0px;">Control Autorizacion</label>
            <select class="form-control ui-autocomplete-input" name="conc_control">
                <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_control == '1' ? 'selected="selected"' : '' ) ?> value="1">Si</option>
                <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_control == '0' ? 'selected="selected"' : '' ) ?>  value="0">No</option>
            </select>
        </div>
    </div>     
    <div id="tooltipInt">
        Cuenta en la cual trabajara este concepto. Ej: Concepto como Expensas en Secundaria, Rendicion en Principal
    </div>
    <input name="conc_id" type="hidden" value="<?= (isset($row) && $row->conc_id ) ? $row->conc_id : '' ?>"/>
    <div style="margin-top:22px;float:left">
        <button class="btn btn-primary" type="submit" id="buttons_cli1" style="margin-left:11px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
        <span id="spans" type="button" class="btn btn-default btn-lg">
            <a style=" margin: 0 auto;" onclick="$('#back_fader').hide();$('#popup').hide();$('#back_fader2').hide();$('#popu2').hide();"id="buttons_cli" class="btn" href="javascript:;"><?= 'Cancelar' ?></a>
        </span>
    </div>    
    <div  id="com_display"></div>
</form>
