<form style="overflow: visible;" action="javascript:;" onsubmit="request_post('<?= site_url('manager/save_conceptos_pop') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
    <input type="text" id="conc_desc" name="conc_desc" class="form-control"  placeholder="Nuevo Concepto" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 24px;font-size: 16px;width: 403px;float: left;">
    <div class="selectores">
        <label style="margin-left: 5px; margin-bottom: 0px;">Tipo de Concepto</label>
        <select class="form-control ui-autocomplete-input" name="conc_tipo">
            <option class="form-control ui-autocomplete-input" value="Entrada">Entrada</option>
            <option class="form-control ui-autocomplete-input" value="Salida">Salida</option>
        </select>
        <label style="margin-left: 5px; margin-bottom: 0px;">Cuenta</label>
        <select title="Cuenta en la cual trabajara este concepto. Ej: Concepto como Expensas en Secundaria, Rendicion en Principal" class="form-control ui-autocomplete-input" name="conc_cc">
            <option class="form-control ui-autocomplete-input" value="cc_saldo">Principal</option>
            <option class="form-control ui-autocomplete-input" value="cc_varios">Secundaria</option>
        </select>
        <label style="margin-left: 5px; margin-bottom: 0px;">Control Autorizacion</label>
        <select class="form-control ui-autocomplete-input" name="conc_control">
            <option class="form-control ui-autocomplete-input" value="1">Si</option>
            <option class="form-control ui-autocomplete-input" value="0">No</option>
        </select>
    </div>    
    <div class="_com_display"></div>
    <input name="conc_id" type="hidden"/>
    <div style="margin-bottom:14px;clear:both;margin-left:210px;">
        <button class="btn btn-primary" type="submit" id="buttons_cli1" style="margin-left:11px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
        <span id="spans" type="button" class="btn btn-default btn-lg">
            <a style=" margin: 0 auto;" onclick="$('#back_fader').hide();$('#popup').hide();$('#back_fader2').hide();$('#popu2').hide();"id="buttons_cli" class="btn" href="javascript:;"><?= 'Cancelar' ?></a>
        </span>
    </div>   
</form>
