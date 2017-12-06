<div class="section">
    <div class="section_description">
        <label>Informe del balance de cuentas para un mes determinado</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildAccountsBalanceReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input type="text" name="month" required class="form-control _month _general_number_input_control _general_amount_input_control _general_letters_input_control ui-autocomplete-input section_input" placeholder="Mes" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>

<script>
    $(function(){
        $('._month').datepicker( {
            monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
            monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
            changeMonth: true,
            changeYear: true,
            yearRange: '1940:2050',
            duration: "slow",
            maxDate: "+15y",
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                $('._month').blur();
            }
        }); 
    });
</script>