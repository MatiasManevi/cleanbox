<div class="section">
    <div class="section_description">
        <label>Informe que lista todos los balances de las cuentas corrientes en cada mes del año</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildAccountsAnualBalanceReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input type="text" name="year" required class="form-control     ui-autocomplete-input section_input" placeholder="Año" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>

<script>
    $(function(){
        $('._year').datepicker( {
            monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
            monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
            changeMonth: true,
            changeYear: true,
            yearRange: '1940:2050',
            duration: "slow",
            maxDate: "+15y",
            dateFormat: 'yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                $('._year').blur();
            }
        }); 
    });
</script>