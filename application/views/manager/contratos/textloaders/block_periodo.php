<div id="bloque_p<?= $num ?>" class="bloque_p" style="display: block;">
    <input value="<?= isset($periodo) ? $periodo['per_inicio'] : '' ?>" type="text" id="periodo_i<?= $num ?>" name="periodo_i<?= $num ?>" style="margin-right: 5px;font-size: 16px;width: 247px;float: left;" class="form-control ui-autocomplete-input hasDatepicker" placeholder="Fecha Inicio">
    <input value="<?= isset($periodo) ? $periodo['per_fin'] : '' ?>" type="text" id="periodo_f<?= $num ?>" name="periodo_f<?= $num ?>" style="margin-right: 5px;font-size: 16px;width: 247px;float: left;" class="form-control ui-autocomplete-input hasDatepicker" placeholder="Fecha Fin">
    <input value="<?= isset($periodo) ? $periodo['per_monto'] : '' ?>" type="text" id="monto<?= $num ?>" name="monto<?= $num ?>" autocomplete="off" style="margin-right: 5px;font-size: 16px;width: 247px;float: left;" class="form-control ui-autocomplete-input" placeholder="Monto">
    <? if ($contrato['con_tipo'] == 'Alquiler Comercial N') { ?>
        <input value="<?= isset($periodo) ? $periodo['per_iva'] : '' ?>" type="text" id="iva<?= $num ?>" name="iva<?= $num ?>" autocomplete="off" style="margin-right: 5px;font-size: 16px;width: 247px;float: left;" class="form-control ui-autocomplete-input" placeholder="IVA/Alquiler">
    <? } ?>
    <input id="per_id_<?= $num ?>" type="hidden" name="per_id_<?= $num ?>" value="<?= isset($periodo) ? $periodo['per_id'] : '' ?>">                      
    <span id="span_p<?= $num ?>" onclick="removeElement_period(<?= $num ?>)" style="height: 34px;" class="btn btn-default btn-lg">
        <a class="glyphicon glyphicon-minus-sign" style="text-decoration: none; margin-top: -3px;"></a>
    </span>
</div>

<script>
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
    };
    
    $('#periodo_i'+<?= $num ?>).datepicker(date_opt);
    $('#periodo_f'+<?= $num ?>).datepicker(date_opt);
  
</script>