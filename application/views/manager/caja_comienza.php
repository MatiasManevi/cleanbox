<div style="margin-right: 12px;float: left;width: 46%; padding-bottom: 20px;" class="alert alert-info" role="alert">  
    <div style="float: left">Hoy tu caja comenzo con:</div><span style="font-weight: bold; margin-right: 39px; margin-left: 10px; float: left;"><?= isset($dia) ? '$ ' . $dia['caj_saldo'] : '' ?></span>

    <div style="margin-left: 40px;float: left">Su estado actual es:</div>
    <span style="font-weight: bold; margin-right: 10px; margin-left: 10px; float: left;" id="mensual_progresivo"><?= isset($mensual_progresivo) ? '$ ' . $mensual_progresivo : '' ?></span>

</div>


<div class="estado">
    <button class="btn btn-primary" id="buttons_cli1" style="margin-top: -4px;float: left; line-height: 0;">< Transferir</button>
    <input type="text" name="cajafuerte" class="form-control ui-autocomplete-input" id="saldo" placeholder="Monto a Transferir" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="float: left;font-size: 16px;margin-left: 10px;margin-right: 6px;margin-top: -4px;width: 180px;">
    <button class="btn btn-primary" id="buttons_cli" style="margin-top: -4px;float: left; line-height: 0;">Transferir ></button>
    <div style="margin-left: 10px;">Caja fuerte:</div><span id="cf"><?= isset($caja_fuerte) ? '$ ' . round($caja_fuerte, 2) : '' ?></span>
</div>


<?= isset($comments) ? $comments : '' ?>
<script>
    $("#buttons_cli").click(function() {
        var saldo = $('#saldo').val();    
        request('<?= site_url() . 'pasar_caja_fuerte/' ?>'+saldo,'','.contenedor_centro');
        $('#saldo').val('');
    });
    $("#buttons_cli1").click(function() {
        var saldo = $('#saldo').val();    
        request('<?= site_url() . 'pasar_caja_diaria/' ?>'+saldo,'','.contenedor_centro');
        $('#saldo').val('');
    });
</script>

