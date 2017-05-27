<div class="contenedor_centro">
    <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post('<?= site_url('manager/show_informe_juez') . '/' . $id ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
        <div class="informe_juez">
            <label>Generar informe de ingresos y aportes del juez en un periodo dado</label>
            <div class="msg_display"></div>      
            <button id="buttons_cli" class="btn btn-primary" style="width: 168px;float:right;margin-left: 20px;">Generar Informe</button>
            <input placeholder="Fecha Comienzo" class="des_fecha" id="datepickerInf" type="text" name="fecha_inferior">
            <input placeholder="Fecha Fin" class="des_fecha" id="datepickerSup" type="text" name="fecha_superior">
        </div>
    </form>
</div>    
<script>
    $( "#datepickerInf" ).datepicker({
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
    });
    $( "#datepickerSup" ).datepicker({
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
    });
</script>