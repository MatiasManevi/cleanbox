<form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post('<?= site_url('manager/save_arbitros') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
    <input name="arb_id" type="hidden" value="<?= (isset($row) && $row->arb_id ) ? $row->arb_id : '' ?>"/>
    <h2><?= isset($row) ? 'Editar' . ' ' . 'Arbitro' : 'Agregar' . ' ' . 'Arbitro' ?></h2>
    <div class="row">
        <div class="bloque1">

            <div id="cli_item" class="field span3">
                <label>Nombre</label>
                <input name="arb_name" type="text" value="<?= (isset($row) && $row->arb_name ) ? $row->arb_name : '' ?>"/>
            </div>
        </div>
    </div>
    <div style="margin-top:23px;  width: 294px;"id="row_but" class="row-fluid">
        <button id="buttons_cli" class="btn btn-primary"><?= isset($row) ? 'Guardar' : 'Agregar' ?></button>
        <a id="buttons_cli" class="btn" href="<?= site_url('arbitros') ?>"><?= 'Cancelar' ?></a>
    </div>
    
    

</form>
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
    });$( "#datepickerSup" ).datepicker({
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


