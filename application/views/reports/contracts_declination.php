<div class="section">
    <div class="section_description">
        <label>Informe de vencimientos de contratos a partir de una fecha deterinada a 60 dias en adelante</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildContractsDeclinationReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input type="text" name="from" required class="form-control _datepicker_filter _general_number_input_control _general_amount_input_control _general_letters_input_control ui-autocomplete-input section_input" placeholder="Fecha" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>
