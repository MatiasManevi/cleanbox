<label style="font-size: 15.2px;">Los débitos mayores a $ 1000 requieren un código de autorización. Para continuar con la operación solicite el código a su encargado</label>

<input value="" type="text" id="codigo" name="codigo" class="form-control ui-autocomplete-input"  placeholder="Código" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 24px;font-size: 16px;width: 403px;float: left;">
<div style="margin-top:22px;float:left">
    <button class="btn btn-primary" type="submit" onclick="guardar_debito()" id="buttons_cli1" style="margin-left:11px;float: left; line-height: 0;">Autorizar y Debitar</button>
    <span id="spans" type="button" class="btn btn-default btn-lg">
        <a style=" margin: 0 auto;" onclick="$('#back_fader').hide();$('#popup').hide();$('#back_fader2').hide();$('#popu2').hide();"id="buttons_cli" class="btn" href="javascript:;"><?= 'Cancelar' ?></a>
    </span>
</div>    
<div  id="com_display"></div>

<script> 
    function guardar_debito(){
        request_post_cuenta('<?= site_url('manager/saving_debs/') . $post . '/' ?>'+ $('#codigo').val() ,this,'.contenedor_centro');return false;
    }        
</script>    
