<style>
    #ui-datepicker-div{
        top: 165px !important;
    }    
</style>
<h1 style="font-size: 18px;">Listado general de los creditos y debitos percibidos en cada concepto, en un rango de fechas determinado</h1>
<div style="margin-top: 30px">
    <input value="" class="form-control ui-autocomplete-input" name="desde" placeholder="Desde" type="text" id="d"autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
    <input value="" class="form-control ui-autocomplete-input" name="hasta" placeholder="Hasta" type="text" id="h"autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;"> 
    <button style="clear: both;float: left;height: 42px;margin-left: 650px;" id="buttons_cli" class="btn btn-primary">Generar Informe</button>
</div>
<div id="com_display">
    <span></span>
</div>
<script>
    $("#buttons_cli").click(function() {
        var desde = $('#d').val();
        var hasta = $('#h').val();     
       request_informe('<?= site_url() . 'informar_mensual/' ?>'+desde+'/'+hasta,'','.contenedor_centro');
    });
    $( "#d" ).datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)});
    $( "#h" ).datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)});
</script>
