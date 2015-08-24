<div class="auth" style="display: block;">
    <label style="float: left;font-size: 15.2px;margin: 0 auto;text-align: center;width: 449px;margin-bottom: 25px;">
        Para modificar los días de mora es necesario ingresar un código de autoriación, solicitelo a su encargado</label>
    <input type="text" id="codigo" class="form-control ui-autocomplete-input" name="codigo" placeholder="Código" 
           style="clear: both;float: left;font-size: 16px;margin-left: 150px;text-align: center;width: 129px;">
    <input id="id" type="hidden" value="<?= $id ?>"/>
</div>
<div style="margin-bottom: 20px;margin-left: 98px;margin-top:58px;float:left">
    <button onclick="request_post_pop('<?= site_url('manager/autorizar') ?>'+'/'+$('#id').val()+'/'+$('#codigo').val(),this,'#popup');return false;" class="btn btn-primary" type="submit" id="buttons_cli1" style="margin-left:11px;float: left; line-height: 0;"><?= 'Autorizar' ?></button>
    <span id="spans" type="button" class="btn btn-default btn-lg">
        <a style=" margin: 0 auto;" onclick="$('#back_fader').hide();$('#popup').hide();"id="buttons_cli" class="btn" href="javascript:;"><?= 'Cancelar' ?></a>
    </span>
</div>    
<div id="com_display1"></div>
