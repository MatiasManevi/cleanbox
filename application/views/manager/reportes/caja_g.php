<h1 style="font-size: 18px;">Informe diario general del estado de la caja y sus movimientos</h1>
<input id="fecha" type="text" name="fecha" class="form-control ui-autocomplete-input" placeholder="Fecha Caja" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 31px;clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">         
<button style="clear: both;float: left;height: 42px;margin-left: 244px;" id="buttons_cli" class="btn btn-primary">Generar Informe</button>
    
<div id="com_display">
    <span></span>
</div>
<script>
    $("#buttons_cli").click(function() {
        var fecha = $('#fecha').val();    
        request_informe('<?= site_url() . 'informar_caja_general/' ?>'+fecha,'','.contenedor_centro');
    });
    var date_opt = {
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)
    }
    $('#fecha').datepicker(date_opt);
</script>
