<div class="section">
    <div class="section_description">
        <label>Informe del porcentaje de rendiciones efectuadas en un periodo determinado</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildRenditionsPercentReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input required type="text" name="from" required class="form-control _datepicker_filter _general_number_input_control _general_amount_input_control _general_letters_input_control ui-autocomplete-input section_input" placeholder="Desde" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   
        <input required type="text" name="to" required class="form-control _datepicker_filter _general_number_input_control _general_amount_input_control _general_letters_input_control ui-autocomplete-input section_input" placeholder="Hasta" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>
